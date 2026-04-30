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
    public function index()
    {
        $listings = Listing::with(['category','images','user'])
                    ->latest()
                    ->paginate(15);

        return view('admin.listings.index', compact('listings'));
    }

    public function home()
    {
        $categories = Category::with([
            'listings' => fn ($query) => $query->active()->with('images')
        ])->get();
        $recommendedListings = Listing::with(['images', 'category'])
            ->active()
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
        $categories = Category::all();
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
        ])->active()->findOrFail($id);

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

        $request->validate([
            'discount_price' => 'nullable|numeric|min:0|lt:price',
        ]);

        $listing->update($request->except('images'));

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
            ->active();

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

        $categories = Category::all();

        return view('search.index', compact('listings', 'posts', 'categories', 'keyword', 'categoryId'));
    }
}
