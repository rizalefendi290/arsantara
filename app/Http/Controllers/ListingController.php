<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;
use App\Models\PropertyDetail;
use App\Models\CarDetail;
use App\Models\MotorcycleDetail;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Carousel;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\ListingView;
use App\Services\ImageWatermarkService;

class ListingController extends Controller
{
    private array $adminListingStatuses = [
        'pending' => 'Pending',
        'aktif' => 'Aktif',
        'sold' => 'Terjual',
    ];

    public function index(Request $request)
    {
        $listings = $this->adminListingQuery($request)
                    ->paginate(15)
                    ->appends($request->query());

        $categories = Category::orderBy('name')->get();
        $statuses = $this->adminListingStatuses;

        return view('admin.listings.index', compact('listings', 'categories', 'statuses'));
    }

    public function exportExcel(Request $request)
    {
        $listings = $this->adminListingQuery($request)->get();
        $filename = 'katalog-listing-'.now()->format('Ymd-His').'.xls';

        return response($this->buildExcelTable($listings), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $listings = $this->adminListingQuery($request)->get();
        $filename = 'katalog-listing-'.now()->format('Ymd-His').'.pdf';

        return response($this->buildSimplePdf($listings), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function adminListingQuery(Request $request)
    {
        return Listing::with(['category','images','user','propertyDetail','carDetail','motorcycleDetail'])
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('condition'), fn ($query) => $query->where('condition', $request->condition))
            ->when($request->filled('is_kpr'), function ($query) use ($request) {
                $query->where('category_id', 1)
                    ->whereHas('propertyDetail', fn ($property) => $property->where('is_kpr', $request->is_kpr));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('product_code', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', '%'.$search.'%'))
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->latest();
    }

    private function buildExcelTable($listings): string
    {
        $rows = $listings->map(fn ($listing) => $this->exportRow($listing));
        $headers = ['ID', 'Kode Produk', 'Judul', 'Pemilik', 'Kategori', 'Status', 'Kondisi', 'Harga', 'Harga Diskon', 'Harga Final', 'Lokasi', 'Jenis Rumah', 'Detail', 'Tanggal Dibuat'];

        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h2>Katalog Listing Arsantara</h2>';
        $html .= '<table border="1"><thead><tr>';

        foreach ($headers as $header) {
            $html .= '<th style="background:#dbeafe;font-weight:bold;">'.e($header).'</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>'.e((string) $value).'</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return "\xEF\xBB\xBF".$html;
    }

    private function exportRow(Listing $listing): array
    {
        $detail = '-';
        $jenisRumah = '-';

        if ($listing->category_id == 1 && $listing->propertyDetail) {
            $jenisRumah = $listing->propertyDetail->is_kpr ? 'KPR' : 'Non KPR';
            $detail = collect([
                'LT '.$listing->propertyDetail->land_area.' m2',
                'LB '.$listing->propertyDetail->building_area.' m2',
                $listing->propertyDetail->bedrooms.' KT',
                $listing->propertyDetail->bathrooms.' KM',
            ])->filter(fn ($value) => !str_contains($value, '  m2') && !str_starts_with($value, ' KT') && !str_starts_with($value, ' KM'))->implode(', ');
        } elseif ($listing->category_id == 2 && $listing->propertyDetail) {
            $detail = 'LT '.$listing->propertyDetail->land_area.' m2, '.$listing->propertyDetail->certificate;
        } elseif ($listing->category_id == 3 && $listing->carDetail) {
            $detail = collect([$listing->carDetail->brand, $listing->carDetail->model, $listing->carDetail->year, ucfirst($listing->carDetail->transmission ?? '')])->filter()->implode(', ');
        } elseif ($listing->category_id == 4 && $listing->motorcycleDetail) {
            $detail = collect([$listing->motorcycleDetail->brand, $listing->motorcycleDetail->model, $listing->motorcycleDetail->year, ucfirst($listing->motorcycleDetail->transmission ?? '')])->filter()->implode(', ');
        }

        return [
            $listing->id,
            $listing->product_code ?: $listing->buildProductCode(),
            $listing->title,
            $listing->user->name ?? '-',
            $listing->category->name ?? '-',
            ucfirst($listing->status),
            ucfirst($listing->condition ?? '-'),
            $listing->price,
            $listing->discount_price ?: '-',
            $listing->finalPrice(),
            $listing->location,
            $jenisRumah,
            $detail ?: '-',
            optional($listing->created_at)->format('d/m/Y H:i'),
        ];
    }

    private function buildSimplePdf($listings): string
    {
        $lines = [
            'Katalog Listing Arsantara',
            'Dicetak: '.now()->format('d/m/Y H:i'),
            'Total data: '.$listings->count(),
            '',
        ];

        foreach ($listings as $index => $listing) {
            $row = $this->exportRow($listing);
            $lines[] = ($index + 1).'. ['.$row[1].'] '.$row[2];
            $lines[] = '   Kategori: '.$row[4].' | Status: '.$row[5].' | Kondisi: '.$row[6];
            $lines[] = '   Harga: Rp '.number_format((int) $row[9], 0, ',', '.').' | Pemilik: '.$row[3];
            $lines[] = '   Lokasi: '.$row[10];
            if ($row[11] !== '-') {
                $lines[] = '   Jenis Rumah: '.$row[11];
            }
            $lines[] = '   Detail: '.$row[12];
            $lines[] = '';
        }

        return $this->makeTextPdf($lines);
    }

    private function makeTextPdf(array $lines): string
    {
        $fontSize = 9;
        $lineHeight = 13;
        $left = 36;
        $top = 560;
        $pageLineLimit = 40;
        $pages = array_chunk($lines, $pageLineLimit);
        $objects = [];

        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $kids = [];

        foreach ($pages as $pageIndex => $pageLines) {
            $pageObjectNumber = 3 + ($pageIndex * 2);
            $contentObjectNumber = $pageObjectNumber + 1;
            $kids[] = $pageObjectNumber.' 0 R';

            $content = "BT\n/F1 {$fontSize} Tf\n";
            foreach ($pageLines as $lineIndex => $line) {
                $y = $top - ($lineIndex * $lineHeight);
                $content .= "1 0 0 1 {$left} {$y} Tm (".$this->pdfEscape($line).") Tj\n";
            }
            $content .= "ET";

            $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 ".(3 + (count($pages) * 2))." 0 R >> >> /Contents {$contentObjectNumber} 0 R >>";
            $objects[] = "<< /Length ".strlen($content)." >>\nstream\n{$content}\nendstream";
        }

        array_splice($objects, 1, 0, '<< /Type /Pages /Kids ['.implode(' ', $kids).'] /Count '.count($pages).' >>');
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }
        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function pdfEscape(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^\x20-\x7E]/', '', $text);
        $text = mb_strimwidth($text, 0, 130, '...');

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    public function home()
    {
        $categories = Category::active()->with([
            'listings' => fn ($query) => $query->active()->with('images')
        ])->get();
        $recommendedListings = Listing::with(['images', 'category'])
            ->active()
            ->inActiveCategory()
            ->where('is_featured', true)
            ->whereIn('category_id', [1, 2, 3, 4])
            ->latest()
            ->take(8)
            ->get();
        $carousels = Carousel::all();
        $posts = Post::latest()->take(6)->get();
        $testimonials = Testimonial::where('is_active',1)->latest()->get();

        return view('user.home', compact('categories','recommendedListings','carousels','posts','testimonials'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('listings.create', compact('categories'));
    }

    public function store(Request $request, ImageWatermarkService $watermarkService)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|in:1,2,3,4',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'location' => 'required',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
            'house_type' => 'required_if:category_id,1|nullable|string|max:255',
            'land_area' => 'required_if:category_id,1,2|nullable|numeric',
            'building_area' => 'required_if:category_id,1|nullable|numeric',
            'bedrooms' => 'required_if:category_id,1|nullable|integer',
            'bathrooms' => 'required_if:category_id,1|nullable|integer',
            'floors' => 'required_if:category_id,1|nullable|integer',
            'certificate' => 'required_if:category_id,1,2|nullable|string|max:255',
            'is_kpr' => 'required_if:category_id,1|nullable|boolean',
            'facilities' => 'nullable|string',
            'brand' => 'required_if:category_id,3,4|nullable|string|max:100',
            'model' => 'required_if:category_id,3,4|nullable|string|max:100',
            'year' => 'required_if:category_id,3,4|nullable|integer|between:1901,2155',
            'engine' => 'required_if:category_id,3,4|nullable|integer',
            'transmission' => 'required_if:category_id,3,4|nullable|in:manual,matic',
            'fuel_type' => 'nullable|in:bensin,diesel,listrik,hybrid',
            'color' => 'nullable|string|max:100',
            'kilometer' => 'nullable|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        abort_unless(Category::whereKey($request->category_id)->where('is_active', true)->exists(), 422);

        // SIMPAN LISTING UTAMA
        $listing = Listing::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'location' => $request->location,
            'condition' => $request->condition,
            'status' => 'aktif'
        ]);
        $listing->assignProductCode();

        // ================= RUMAH =================
        if ($request->category_id == 1) {
            PropertyDetail::create([
                'listing_id' => $listing->id,
                'house_type' => $request->house_type,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'floors' => $request->floors,
                'certificate' => $request->certificate,
                'facilities' => $request->facilities,
                'is_kpr' => $request->is_kpr ?? 0,
            ]);
        }

        // ================= TANAH =================
        if ($request->category_id == 2) {
            PropertyDetail::create([
                'listing_id' => $listing->id,
                'land_area' => $request->land_area,
                'certificate' => $request->certificate,
            ]);
        }

        // ================= MOBIL =================
        if ($request->category_id == 3) {
            CarDetail::create([
                'listing_id' => $listing->id,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'engine' => $request->engine,
                'transmission' => $request->transmission,
                'fuel_type' => $request->fuel_type,
                'color' => $request->color,
                'kilometer' => $request->kilometer,
            ]);
        }

        // ================= MOTOR =================
        if ($request->category_id == 4) {
            MotorcycleDetail::create([
                'listing_id' => $listing->id,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'engine' => $request->engine,
                'transmission' => $request->transmission,
            ]);
        }

        // ================= IMAGE =================
        if($request->hasFile('images')){
            foreach($request->file('images') as $image){

                $path = $watermarkService->storeListingImage($image);

                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image' => $path
                ]);
            }
        }

        $shareUrl = route('listing.show', $listing->id);

        return redirect()->route('admin.dashboard')
            ->with('success','Data berhasil ditambahkan')
            ->with('share_url', $shareUrl)
            ->with('share_title', $listing->title)
            ->with('share_text', 'Lihat listing terbaru di Arsantara: '.$listing->title);
    }

    public function show($id)
    {
        $listing = Listing::with([
            'category',
            'images',
            'propertyDetail',
            'carDetail',
            'motorcycleDetail',
            'property',
            'car',
            'motorcycle'
        ])->active()->inActiveCategory()->findOrFail($id);

        ListingView::create([
            'listing_id' => $listing->id,
            'user_id' => optional(request()->user())->id,
            'session_id' => optional(request()->session())->getId(),
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 1000),
            'viewed_at' => now(),
        ]);

        $similar = Listing::with('images')
        ->active()
        ->inActiveCategory()
        ->where('category_id', $listing->category_id)
        ->where('id', '!=', $listing->id)
        ->latest()
        ->take(6)
        ->get();

        return view('listings.show', compact('listing','similar'));
    }

    public function edit($id)
    {
        $listing = Listing::with('images')->findOrFail($id);
        $categories = Category::all();

        return view('listings.edit', compact('listing','categories'));
    }

    public function update(Request $request, $id, ImageWatermarkService $watermarkService)
    {
        $listing = Listing::findOrFail($id);
        $oldCategoryId = $listing->category_id;

        $request->validate([
            'discount_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        $listing->update($request->except('images'));
        $listing->refresh();

        if ((int) $oldCategoryId !== (int) $listing->category_id || blank($listing->product_code)) {
            $listing->assignProductCode(true);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (!$image) {
                    continue;
                }

                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image' => $watermarkService->storeListingImage($image),
                ]);
            }
        }

        return redirect()->route('listings.index')
            ->with('success','Listing berhasil diupdate');
    }

    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();

        return back()->with('success','Listing dihapus');
    }

    public function approve($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update(['status' => 'aktif']);

        return back()->with('success', 'Listing berhasil dipublikasikan');
    }

    public function recommendations(Request $request)
    {
        $query = Listing::with(['images', 'category', 'user'])
            ->active()
            ->whereIn('category_id', [1, 2, 3, 4]);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        }

        $listings = $query->orderByDesc('is_featured')
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $categories = Category::all();
        $totalRecommended = Listing::active()->where('is_featured', true)->count();

        return view('admin.listings.recommendations', compact('listings', 'categories', 'totalRecommended'));
    }

    public function toggleRecommendation($id)
    {
        $listing = Listing::findOrFail($id);

        $listing->update([
            'is_featured' => !$listing->is_featured,
        ]);

        return back()->with('success', 'Status rekomendasi listing berhasil diperbarui');
    }

    public function deleteImage($id)
    {
        $image = ListingImage::findOrFail($id);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }

    public function rumah(Request $request)
    {
        $this->abortIfCategoryInactive(1);

        // Base query untuk semua rumah
        $listingsQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->where('category_id', 1);

        // rumah KPR query
        $kprQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', true);
            });

        // rumah NON KPR query
        $nonKprQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', false);
            });

        // Apply filters
        if ($request->min_price) {
            $listingsQuery->where('price', '>=', $request->min_price);
            $kprQuery->where('price', '>=', $request->min_price);
            $nonKprQuery->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $listingsQuery->where('price', '<=', $request->max_price);
            $kprQuery->where('price', '<=', $request->max_price);
            $nonKprQuery->where('price', '<=', $request->max_price);
        }

        if ($request->location) {
            $listingsQuery->where('location', 'like', '%' . $request->location . '%');
            $kprQuery->where('location', 'like', '%' . $request->location . '%');
            $nonKprQuery->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->bedrooms) {
            $listingsQuery->whereHas('propertyDetail', function($q) use ($request) {
                $q->where('bedrooms', '>=', $request->bedrooms);
            });
            $kprQuery->whereHas('propertyDetail', function($q) use ($request) {
                $q->where('bedrooms', '>=', $request->bedrooms);
            });
            $nonKprQuery->whereHas('propertyDetail', function($q) use ($request) {
                $q->where('bedrooms', '>=', $request->bedrooms);
            });
        }

        // Apply sorting
        $sort = $request->sort;
        if ($sort == 'low') {
            $listingsQuery->orderBy('price', 'asc');
            $kprQuery->orderBy('price', 'asc');
            $nonKprQuery->orderBy('price', 'asc');
        } elseif ($sort == 'high') {
            $listingsQuery->orderBy('price', 'desc');
            $kprQuery->orderBy('price', 'desc');
            $nonKprQuery->orderBy('price', 'desc');
        } else {
            $listingsQuery->latest();
            $kprQuery->latest();
            $nonKprQuery->latest();
        }

        // Execute queries with error handling
        $listings = $listingsQuery->get();

        $kpr = collect();
        try {
            $kpr = $kprQuery->get();
        } catch (\Exception $e) {
            $kpr = collect();
        }

        $nonKpr = collect();
        try {
            $nonKpr = $nonKprQuery->get();
        } catch (\Exception $e) {
            $nonKpr = $listings;
        }

        // 🔥 kirim semua
        return view('rumah.index', compact('listings','kpr','nonKpr'));
    }

    public function tanah(Request $request)
    {
        $this->abortIfCategoryInactive(2);

        $listings = Listing::with(['images','propertyDetail'])
            ->active()
            ->where('category_id', 2);

        // Apply filters
        if ($request->min_price) {
            $listings->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $listings->where('price', '<=', $request->max_price);
        }

        if ($request->location) {
            $listings->where('location', 'like', '%' . $request->location . '%');
        }

        // Apply sorting
        $sort = $request->sort;
        if ($sort == 'low') {
            $listings->orderBy('price', 'asc');
        } elseif ($sort == 'high') {
            $listings->orderBy('price', 'desc');
        } else {
            $listings->latest();
        }

        $listings = $listings->paginate(12);

        return view('tanah.index', compact('listings'));
    }

    public function mobil(Request $request)
    {
        $this->abortIfCategoryInactive(3);

        $listings = Listing::with(['images','car'])
            ->active()
            ->where('category_id', 3);

        // Apply filters
        if ($request->min_price) {
            $listings->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $listings->where('price', '<=', $request->max_price);
        }

        if ($request->location) {
            $listings->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->transmission) {
            $listings->whereHas('carDetail', function($q) use ($request) {
                $q->where('transmission', $request->transmission);
            });
        }

        // Apply sorting
        $sort = $request->sort;
        if ($sort == 'low') {
            $listings->orderBy('price', 'asc');
        } elseif ($sort == 'high') {
            $listings->orderBy('price', 'desc');
        } else {
            $listings->latest();
        }

        $listings = $listings->paginate(12);

        return view('mobil.index', compact('listings'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', $request->input('search', '')));
        $categoryId = $request->input('category', $request->input('category_id'));

        $listingsQuery = Listing::with(['images', 'category', 'propertyDetail', 'carDetail', 'motorcycleDetail'])
            ->active()
            ->inActiveCategory();

        if ($categoryId) {
            $listingsQuery->where('category_id', $categoryId);
        }

        if ($keyword !== '') {
            $listingsQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('location', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%')
                    ->orWhereHas('category', function ($category) use ($keyword) {
                        $category->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('user', function ($user) use ($keyword) {
                        $user->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('propertyDetail', function ($property) use ($keyword) {
                        $property->where('house_type', 'like', '%'.$keyword.'%')
                            ->orWhere('certificate', 'like', '%'.$keyword.'%')
                            ->orWhere('facilities', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('carDetail', function ($car) use ($keyword) {
                        $car->where('brand', 'like', '%'.$keyword.'%')
                            ->orWhere('model', 'like', '%'.$keyword.'%')
                            ->orWhere('color', 'like', '%'.$keyword.'%')
                            ->orWhere('fuel_type', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('motorcycleDetail', function ($motorcycle) use ($keyword) {
                        $motorcycle->where('brand', 'like', '%'.$keyword.'%')
                            ->orWhere('model', 'like', '%'.$keyword.'%');
                    });
            });
        }

        if ($request->min_price) {
            $listingsQuery->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $listingsQuery->where('price', '<=', $request->max_price);
        }

        $listings = $listingsQuery->latest()->paginate(12, ['*'], 'listings_page')->appends($request->query());

        $posts = Post::with('images')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', '%'.$keyword.'%')
                        ->orWhere('content', 'like', '%'.$keyword.'%')
                        ->orWhere('source_name', 'like', '%'.$keyword.'%');
                });
            })
            ->latest()
            ->paginate(6, ['*'], 'posts_page')
            ->appends($request->query());

        $categories = Category::active()->get();

        return view('search.index', compact('listings', 'posts', 'categories', 'keyword', 'categoryId'));
    }

    private function abortIfCategoryInactive(int $categoryId): void
    {
        abort_unless(Category::whereKey($categoryId)->where('is_active', true)->exists(), 404);
    }
}
