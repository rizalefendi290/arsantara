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
use App\Models\JobVacancy;
use App\Models\User;
use App\Services\ImageWatermarkService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    private const LISTING_CATEGORY_SLUGS = ['rumah', 'tanah', 'mobil', 'motor', 'ruko', 'perkantoran', 'gudang', 'kios', 'truk-kendaraan-komersil'];
    private const PROPERTY_CATEGORY_SLUGS = ['rumah', 'tanah', 'ruko', 'perkantoran', 'gudang', 'kios'];
    private const VEHICLE_CATEGORY_SLUGS = ['mobil', 'motor', 'truk-kendaraan-komersil'];

    private array $adminListingStatuses = [
        'pending' => 'Pending',
        'aktif' => 'Aktif',
        'sold' => 'Terjual',
    ];

    private array $adminListingOwnerRoles = [
        'agen' => 'Agen',
        'pemilik' => 'Pemilik Produk',
    ];

    public function index(Request $request)
    {
        $listings = $this->adminListingQuery($request)
                    ->paginate(15)
                    ->appends($request->query());

        $categories = Category::orderBy('name')->get();
        $statuses = $this->adminListingStatuses;
        $ownerRoles = $this->adminListingOwnerRoles;
        $owners = User::whereIn('role', array_keys($this->adminListingOwnerRoles))
            ->orderBy('role')
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return view('admin.listings.index', compact('listings', 'categories', 'statuses', 'ownerRoles', 'owners'));
    }

    public function exportExcel(Request $request)
    {
        $listings = $this->adminListingQuery($request)->get();
        $filename = $this->adminListingExportFilename($request, 'xls');

        return response($this->buildExcelTable($listings), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $listings = $this->adminListingQuery($request)->get();
        $filename = $this->adminListingExportFilename($request, 'pdf');

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
            ->when($request->filled('owner_role') && array_key_exists($request->owner_role, $this->adminListingOwnerRoles), function ($query) use ($request) {
                $query->whereHas('user', fn ($user) => $user->where('role', $request->owner_role));
            })
            ->when($request->filled('owner_id'), function ($query) use ($request) {
                $query->whereHas('user', function ($user) use ($request) {
                    $user->whereKey($request->owner_id)
                        ->whereIn('role', array_keys($this->adminListingOwnerRoles));
                });
            })
            ->when($request->filled('month_start') || $request->filled('month_end'), function ($query) use ($request) {
                [$startDate, $endDate] = $this->adminListingMonthRange($request);

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('created_at', '<=', $endDate);
                }
            })
            ->when($request->filled('is_kpr'), function ($query) use ($request) {
                $query->inCategorySlug(Category::HOUSE_SLUG)
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

    private function adminListingExportFilename(Request $request, string $extension): string
    {
        $parts = ['katalog-arsantara'];

        if ($request->filled('owner_role') && isset($this->adminListingOwnerRoles[$request->owner_role])) {
            $parts[] = Str::slug($this->adminListingOwnerRoles[$request->owner_role]);
        } else {
            $parts[] = 'semua-sumber';
        }

        if ($request->filled('owner_id')) {
            $owner = User::whereIn('role', array_keys($this->adminListingOwnerRoles))->find($request->owner_id);
            if ($owner) {
                $parts[] = Str::slug($owner->name);
            }
        }

        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);
            $parts[] = $category ? Str::slug($category->name) : 'kategori';
        } else {
            $parts[] = 'semua-kategori';
        }

        $parts[] = $this->adminListingExportPeriodLabel($request);
        $parts[] = 'export-'.now()->format('Ymd-His');

        return implode('-', array_filter($parts)).'.'.$extension;
    }

    private function adminListingExportPeriodLabel(Request $request): string
    {
        $start = $request->input('month_start');
        $end = $request->input('month_end');

        $validStart = is_string($start) && preg_match('/^\d{4}-\d{2}$/', $start);
        $validEnd = is_string($end) && preg_match('/^\d{4}-\d{2}$/', $end);

        if ($validStart && $validEnd) {
            return $start === $end ? 'periode-'.$start : 'periode-'.$start.'-sd-'.$end;
        }

        if ($validStart) {
            return 'periode-'.$start;
        }

        if ($validEnd) {
            return 'sampai-'.$end;
        }

        return 'semua-periode';
    }

    private function adminListingMonthRange(Request $request): array
    {
        $startDate = $this->adminListingMonthDate($request->input('month_start'), true);
        $endDate = $this->adminListingMonthDate($request->input('month_end'), false);

        if ($startDate && ! $endDate) {
            $endDate = $startDate->copy()->endOfMonth();
        }

        if ($startDate && $endDate && $startDate->gt($endDate)) {
            return [$endDate->copy()->startOfMonth(), $startDate->copy()->endOfMonth()];
        }

        return [$startDate, $endDate];
    }

    private function adminListingMonthDate(?string $month, bool $startOfMonth): ?Carbon
    {
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            return null;
        }

        $date = Carbon::createFromFormat('!Y-m', $month);

        return $startOfMonth ? $date->startOfMonth() : $date->endOfMonth();
    }

    private function buildExcelTable($listings): string
    {
        $sections = [
            'Data Katalog Motor' => [
                'listings' => $listings->filter(fn ($listing) => $listing->category?->isMotorcycle()),
                'headers' => $this->motorExportHeaders(),
                'row' => fn ($listing, $index) => $this->motorExportRow($listing, $index),
            ],
            'Data Mitra Showroom' => [
                'listings' => $listings->filter(fn ($listing) => $listing->category?->isCarLike() && $listing->user?->role === 'agen'),
                'headers' => $this->showroomVehicleExportHeaders(),
                'row' => fn ($listing, $index) => $this->showroomVehicleExportRow($listing, $index),
            ],
            'Data Katalog Mobil' => [
                'listings' => $listings->filter(fn ($listing) => $listing->category?->isCarLike() && $listing->user?->role !== 'agen'),
                'headers' => $this->vehicleExportHeaders(),
                'row' => fn ($listing, $index) => $this->vehicleExportRow($listing, $index),
            ],
            'Data Katalog Rumah' => [
                'listings' => $listings->filter(fn ($listing) => $listing->category?->isHouse()),
                'headers' => $this->houseExportHeaders(),
                'row' => fn ($listing, $index) => $this->houseExportRow($listing, $index),
            ],
            'Data Katalog Tanah' => [
                'listings' => $listings->filter(fn ($listing) => $listing->category?->isLand()),
                'headers' => $this->landExportHeaders(),
                'row' => fn ($listing, $index) => $this->landExportRow($listing, $index),
            ],
        ];

        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h2>Katalog Listing Arsantara</h2>';

        foreach ($sections as $title => $section) {
            $sectionListings = $section['listings']->values();

            if ($sectionListings->isEmpty()) {
                continue;
            }

            $html .= '<h3>'.e($title).'</h3>';
            $html .= '<table border="1"><thead><tr>';

            foreach ($section['headers'] as $header) {
                $html .= '<th style="background:#0b6623;color:#ffffff;font-weight:bold;">'.e($header).'</th>';
            }

            $html .= '</tr></thead><tbody>';

            foreach ($sectionListings as $index => $listing) {
                $html .= '<tr>';
                foreach ($section['row']($listing, $index) as $value) {
                    $html .= '<td>'.e((string) $value).'</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</tbody></table><br>';
        }

        if (! collect($sections)->contains(fn ($section) => $section['listings']->isNotEmpty())) {
            $html .= '<p>Belum ada data listing sesuai filter.</p>';
        }

        $html .= '</body></html>';

        return "\xEF\xBB\xBF".$html;
    }

    private function motorExportHeaders(): array
    {
        return ['No.', 'Nama Kendaraan', 'Tahun Kendaraan', 'Atas Nama Kepemilikan Unit', 'Kelengkapan Surat', 'Status Pajak Tahunan', 'Status Pajak 5 Tahunan', 'Status Kepemilikan', 'TNKB (Plat Nomor)', 'Deskripsi', 'Harga Penjual', 'Harga Penawaran', 'Harga Terjual', 'Komisi', 'Keterangan', 'Progress Status'];
    }

    private function vehicleExportHeaders(): array
    {
        return ['No.', 'Nama Kendaraan', 'Tipe Kendaraan', 'Tahun Kendaraan', 'Atas Nama Kepemilikan Unit', 'Kelengkapan Surat', 'Status Pajak Tahunan', 'Status Pajak 5 Tahunan', 'Status Kepemilikan', 'TNKB (Plat Nomor)', 'Deskripsi', 'Harga Penjual', 'Harga Penawaran', 'Harga Terjual', 'Komisi', 'Keterangan', 'Progress Status'];
    }

    private function showroomVehicleExportHeaders(): array
    {
        return ['No.', 'Nama Kendaraan', 'Tipe Kendaraan', 'Tahun Kendaraan', 'Atas Nama Kepemilikan Unit', 'Kelengkapan Surat', 'Status Pajak Tahunan', 'Status Pajak 5 Tahunan', 'Minimal DP Dari Showroom', 'DP Masuk', 'Harga', 'Harga Nego (Max)', 'Angsuran', 'Tenor', 'Komisi', 'Keterangan', 'Progres Status'];
    }

    private function houseExportHeaders(): array
    {
        return ['No.', 'Nama Mitra', 'Atas Nama Yang Dihubungi', 'Atas Nama Kepemilikan unit', 'Nomor Yang Dihubungi', 'Status Unit (KPR/NON-KPR)', 'Kelengkapan Surat', 'Luas Tanah', 'Luas Bangunan', 'Jumlah Lantai', 'Jumlah Kamar Tidur', 'Jumlah Kamar Mandi', 'Fasilitas', 'Lokasi', 'Detail Lokasi', 'Harga', 'Minimal DP', 'Minimal Nego Harga / Minimal Harga', 'Angsuran', 'Tenor', 'Keterangan', 'Status', 'Komisi'];
    }

    private function landExportHeaders(): array
    {
        return ['No.', 'Atas Nama yang Dihubungi', 'Atas Nama Kepemilikan Unit', 'Nomor yang dihubungi', 'Kelengkapan Surat', 'Luas Tanah', 'Lokasi', 'Harga', 'Minimal DP', 'Minimal Nego Harga / Minimal Harga', 'Keterangan', 'Status', 'Komisi'];
    }

    private function motorExportRow(Listing $listing, int $index): array
    {
        $detail = $listing->motorcycleDetail;

        return [
            $index + 1,
            $listing->title,
            $detail?->year,
            $this->valueOrDash($listing->ownership_name),
            $this->valueOrDash($listing->document_status),
            $this->valueOrDash($listing->annual_tax_status),
            $this->valueOrDash($listing->five_year_tax_status),
            $this->valueOrDash($listing->ownership_status),
            $this->valueOrDash($listing->plate_number),
            $this->valueOrDash($listing->description),
            $this->exportMoney($listing->seller_price ?? $listing->price),
            $this->exportMoney($listing->offer_price ?? $listing->discount_price),
            $this->exportMoney($listing->sold_price),
            $this->exportMoney($listing->commission),
            $this->valueOrDash($listing->notes),
            $this->valueOrDash($listing->progress_status ?? ucfirst($listing->status)),
        ];
    }

    private function vehicleExportRow(Listing $listing, int $index): array
    {
        $detail = $listing->carDetail;

        return [
            $index + 1,
            $listing->title,
            $this->vehicleType($detail),
            $detail?->year,
            $this->valueOrDash($listing->ownership_name),
            $this->valueOrDash($listing->document_status),
            $this->valueOrDash($listing->annual_tax_status),
            $this->valueOrDash($listing->five_year_tax_status),
            $this->valueOrDash($listing->ownership_status),
            $this->valueOrDash($listing->plate_number),
            $this->valueOrDash($listing->description),
            $this->exportMoney($listing->seller_price ?? $listing->price),
            $this->exportMoney($listing->offer_price ?? $listing->discount_price),
            $this->exportMoney($listing->sold_price),
            $this->exportMoney($listing->commission),
            $this->valueOrDash($listing->notes),
            $this->valueOrDash($listing->progress_status ?? ucfirst($listing->status)),
        ];
    }

    private function showroomVehicleExportRow(Listing $listing, int $index): array
    {
        $detail = $listing->carDetail;

        return [
            $index + 1,
            $listing->title,
            $this->vehicleType($detail),
            $detail?->year,
            $this->valueOrDash($listing->ownership_name),
            $this->valueOrDash($listing->document_status),
            $this->valueOrDash($listing->annual_tax_status),
            $this->valueOrDash($listing->five_year_tax_status),
            $this->exportMoney($listing->minimum_dp),
            $this->exportMoney($listing->showroom_dp_in),
            $this->exportMoney($listing->price),
            $this->exportMoney($listing->minimum_nego_price ?? $listing->offer_price ?? $listing->discount_price),
            $this->exportMoney($listing->installment),
            $this->valueOrDash($listing->tenor),
            $this->exportMoney($listing->commission),
            $this->valueOrDash($listing->notes),
            $this->valueOrDash($listing->progress_status ?? ucfirst($listing->status)),
        ];
    }

    private function houseExportRow(Listing $listing, int $index): array
    {
        $detail = $listing->propertyDetail;

        return [
            $index + 1,
            $listing->user?->role === 'agen' ? $this->valueOrDash($listing->user?->name) : '-',
            $this->valueOrDash($listing->contact_name ?? $listing->user?->name),
            $this->valueOrDash($listing->ownership_name),
            $this->valueOrDash($listing->contact_phone ?? $listing->user?->phone),
            $detail?->is_kpr ? 'KPR' : 'NON-KPR',
            $this->valueOrDash($listing->document_status ?? $detail?->certificate),
            $this->valueOrDash($detail?->land_area),
            $this->valueOrDash($detail?->building_area),
            $this->valueOrDash($detail?->floors),
            $this->valueOrDash($detail?->bedrooms),
            $this->valueOrDash($detail?->bathrooms),
            $this->valueOrDash($detail?->facilities),
            $this->valueOrDash($listing->location),
            $this->valueOrDash($listing->description),
            $this->exportMoney($listing->price),
            $this->exportMoney($listing->minimum_dp),
            $this->exportMoney($listing->minimum_nego_price ?? $listing->discount_price),
            $this->exportMoney($listing->installment),
            $this->valueOrDash($listing->tenor),
            $this->valueOrDash($listing->notes),
            $this->valueOrDash($listing->progress_status ?? ucfirst($listing->status)),
            $this->exportMoney($listing->commission),
        ];
    }

    private function landExportRow(Listing $listing, int $index): array
    {
        $detail = $listing->propertyDetail;

        return [
            $index + 1,
            $this->valueOrDash($listing->contact_name ?? $listing->user?->name),
            $this->valueOrDash($listing->ownership_name),
            $this->valueOrDash($listing->contact_phone ?? $listing->user?->phone),
            $this->valueOrDash($listing->document_status ?? $detail?->certificate),
            $this->valueOrDash($detail?->land_area),
            $this->valueOrDash($listing->location),
            $this->exportMoney($listing->price),
            $this->exportMoney($listing->minimum_dp),
            $this->exportMoney($listing->minimum_nego_price ?? $listing->discount_price),
            $this->valueOrDash($listing->notes),
            $this->valueOrDash($listing->progress_status ?? ucfirst($listing->status)),
            $this->exportMoney($listing->commission),
        ];
    }

    private function vehicleType(?CarDetail $detail): string
    {
        return $this->valueOrDash(collect([$detail?->brand, $detail?->model])->filter()->implode(' '));
    }

    private function valueOrDash($value): string
    {
        return filled($value) ? (string) $value : '-';
    }

    private function exportMoney($value): string
    {
        return filled($value) ? (string) $value : '-';
    }

    private function exportRow(Listing $listing): array
    {
        $detail = '-';
        $jenisRumah = '-';

        if ($listing->category?->isHouse() && $listing->propertyDetail) {
            $jenisRumah = $listing->propertyDetail->is_kpr ? 'KPR' : 'Non KPR';
            $detail = collect([
                'LT '.$listing->propertyDetail->land_area.' m2',
                'LB '.$listing->propertyDetail->building_area.' m2',
                $listing->propertyDetail->bedrooms.' KT',
                $listing->propertyDetail->bathrooms.' KM',
            ])->filter(fn ($value) => !str_contains($value, '  m2') && !str_starts_with($value, ' KT') && !str_starts_with($value, ' KM'))->implode(', ');
        } elseif ($listing->category?->isLand() && $listing->propertyDetail) {
            $detail = 'LT '.$listing->propertyDetail->land_area.' m2, '.$listing->propertyDetail->certificate;
        } elseif ($listing->category?->isCommercialProperty() && $listing->propertyDetail) {
            $jenisRumah = $listing->category->name ?? '-';
            $detail = collect([
                $listing->propertyDetail->house_type,
                'LT '.$listing->propertyDetail->land_area.' m2',
                'LB '.$listing->propertyDetail->building_area.' m2',
                $listing->propertyDetail->floors.' lantai',
                $listing->propertyDetail->certificate,
            ])->filter(fn ($value) => filled($value) && !str_contains($value, '  m2') && !str_starts_with($value, ' lantai'))->implode(', ');
        } elseif ($listing->category?->isCarLike() && $listing->carDetail) {
            $detail = collect([$listing->carDetail->brand, $listing->carDetail->model, $listing->carDetail->year, ucfirst($listing->carDetail->transmission ?? '')])->filter()->implode(', ');
        } elseif ($listing->category?->isMotorcycle() && $listing->motorcycleDetail) {
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
            ->inCategorySlugs(self::LISTING_CATEGORY_SLUGS)
            ->latest()
            ->take(8)
            ->get();
        $carousels = Carousel::content()->active()->orderBy('sort_order')->latest()->get();
        $posts = Post::latest()->take(6)->get();
        $testimonials = Testimonial::where('is_active',1)->latest()->get();
        $careerVacancies = JobVacancy::active()->ordered()->take(3)->get();

        return view('user.home', compact('categories','recommendedListings','carousels','posts','testimonials','careerVacancies'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('listings.create', compact('categories'));
    }

    public function store(Request $request, ImageWatermarkService $watermarkService)
    {
        $category = Category::active()->find($request->input('category_id'));

        $request->validate([
            'title' => 'required',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'location' => 'required',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
            'house_type' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'string', 'max:255'],
            'land_area' => [Rule::requiredIf(fn () => $category?->isProperty()), 'nullable', 'numeric'],
            'building_area' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'numeric'],
            'bedrooms' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'bathrooms' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'floors' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'certificate' => [Rule::requiredIf(fn () => $category?->isProperty()), 'nullable', 'string', 'max:255'],
            'is_kpr' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'boolean'],
            'facilities' => 'nullable|string',
            'brand' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'string', 'max:100'],
            'model' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'string', 'max:100'],
            'year' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'integer', 'between:1901,2155'],
            'engine' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'integer'],
            'transmission' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'in:manual,matic'],
            'fuel_type' => 'nullable|in:bensin,diesel,listrik,hybrid',
            'color' => 'nullable|string|max:100',
            'kilometer' => 'nullable|integer',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'ownership_name' => 'nullable|string|max:255',
            'document_status' => 'nullable|string|max:255',
            'annual_tax_status' => 'nullable|string|max:255',
            'five_year_tax_status' => 'nullable|string|max:255',
            'ownership_status' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:50',
            'seller_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sold_price' => 'nullable|numeric|min:0',
            'minimum_dp' => 'nullable|numeric|min:0',
            'minimum_nego_price' => 'nullable|numeric|min:0',
            'showroom_dp_in' => 'nullable|numeric|min:0',
            'installment' => 'nullable|numeric|min:0',
            'tenor' => 'nullable|string|max:100',
            'commission' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'progress_status' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $category = Category::active()->findOrFail($request->category_id);

        // SIMPAN LISTING UTAMA
        $listing = Listing::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'seller_price' => $request->seller_price,
            'offer_price' => $request->offer_price,
            'sold_price' => $request->sold_price,
            'minimum_dp' => $request->minimum_dp,
            'minimum_nego_price' => $request->minimum_nego_price,
            'showroom_dp_in' => $request->showroom_dp_in,
            'installment' => $request->installment,
            'tenor' => $request->tenor,
            'commission' => $request->commission,
            'location' => $request->location,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'ownership_name' => $request->ownership_name,
            'document_status' => $request->document_status,
            'annual_tax_status' => $request->annual_tax_status,
            'five_year_tax_status' => $request->five_year_tax_status,
            'ownership_status' => $request->ownership_status,
            'plate_number' => $request->plate_number,
            'condition' => $request->condition,
            'status' => 'aktif',
            'notes' => $request->notes,
            'progress_status' => $request->progress_status,
        ]);
        $listing->assignProductCode();

        // ================= RUMAH =================
        if ($category->isHouse()) {
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
        if ($category->isLand()) {
            PropertyDetail::create([
                'listing_id' => $listing->id,
                'land_area' => $request->land_area,
                'certificate' => $request->certificate,
            ]);
        }

        if ($category->isCommercialProperty()) {
            PropertyDetail::create([
                'listing_id' => $listing->id,
                'house_type' => $request->house_type,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'bathrooms' => $request->bathrooms,
                'floors' => $request->floors,
                'certificate' => $request->certificate,
                'facilities' => $request->facilities,
                'is_kpr' => 0,
            ]);
        }

        // ================= MOBIL =================
        if ($category->isCarLike()) {
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
        if ($category->isMotorcycle()) {
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
            'seller_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sold_price' => 'nullable|numeric|min:0',
            'minimum_dp' => 'nullable|numeric|min:0',
            'minimum_nego_price' => 'nullable|numeric|min:0',
            'showroom_dp_in' => 'nullable|numeric|min:0',
            'installment' => 'nullable|numeric|min:0',
            'tenor' => 'nullable|string|max:100',
            'commission' => 'nullable|numeric|min:0',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'ownership_name' => 'nullable|string|max:255',
            'document_status' => 'nullable|string|max:255',
            'annual_tax_status' => 'nullable|string|max:255',
            'five_year_tax_status' => 'nullable|string|max:255',
            'ownership_status' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'progress_status' => 'nullable|string|max:255',
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
            ->inCategorySlugs(self::LISTING_CATEGORY_SLUGS);

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
        $this->abortIfCategoryInactive(Category::HOUSE_SLUG);

        // Base query untuk semua rumah
        $listingsQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inCategorySlug(Category::HOUSE_SLUG);

        // rumah KPR query
        $kprQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inCategorySlug(Category::HOUSE_SLUG)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', true);
            });

        // rumah NON KPR query
        $nonKprQuery = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inCategorySlug(Category::HOUSE_SLUG)
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
        $houseCategory = Category::where('slug', Category::HOUSE_SLUG)->first();

        return view('rumah.index', compact('listings','kpr','nonKpr','houseCategory'));
    }

    public function tanah(Request $request)
    {
        $this->abortIfCategoryInactive(Category::LAND_SLUG);

        $listings = Listing::with(['images','propertyDetail'])
            ->active()
            ->inCategorySlug(Category::LAND_SLUG);

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
        $this->abortIfCategoryInactive(Category::CAR_SLUG);

        $listings = Listing::with(['images','car'])
            ->active()
            ->inCategorySlug(Category::CAR_SLUG);

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
        $productType = $request->input('product_type');
        $categories = Category::active()->get();
        $naturalSearch = $this->parseNaturalListingSearch($keyword, $categories);
        $listingTerms = $naturalSearch['terms'];
        $postTerms = $this->searchTermsFromKeyword($keyword);

        if (!$categoryId && $naturalSearch['category_id']) {
            $categoryId = $naturalSearch['category_id'];
        }

        $listingsQuery = Listing::with(['images', 'category', 'propertyDetail', 'carDetail', 'motorcycleDetail'])
            ->active()
            ->inActiveCategory();

        if ($categoryId) {
            $listingsQuery->where('category_id', $categoryId);
        } elseif ($productType === 'property') {
            $listingsQuery->inCategorySlugs(self::PROPERTY_CATEGORY_SLUGS);
        } elseif (in_array($productType, ['car', 'vehicle'], true)) {
            $listingsQuery->inCategorySlugs(self::VEHICLE_CATEGORY_SLUGS);
        }

        if ($listingTerms->isNotEmpty()) {
            $listingsQuery->where(function ($tokenQuery) use ($listingTerms) {
                foreach ($listingTerms as $term) {
                    $tokenQuery->where(function ($termQuery) use ($term) {
                        $this->applyListingSearchTerm($termQuery, $term);
                    });
                }
            });

            $listingsQuery->orderByRaw(
                'CASE
                    WHEN product_code = ? THEN 0
                    WHEN title LIKE ? THEN 1
                    WHEN title LIKE ? THEN 2
                    WHEN location LIKE ? THEN 3
                    WHEN description LIKE ? THEN 4
                    ELSE 5
                END',
                [$keyword, $keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%']
            );
        }

        $minPrice = $request->filled('min_price') ? (int) $request->min_price : $naturalSearch['min_price'];
        $maxPrice = $request->filled('max_price') ? (int) $request->max_price : $naturalSearch['max_price'];
        $this->applyFinalPriceFilter($listingsQuery, $minPrice, $maxPrice);

        if ($request->filled('location')) {
            $listingsQuery->where('location', 'like', '%'.$request->location.'%');
        }

        if ($request->filled('condition')) {
            $listingsQuery->where('condition', $request->condition);
        }

        if ($request->filled('certificate')) {
            $listingsQuery->whereHas('propertyDetail', function ($property) use ($request) {
                $property->where('certificate', $request->certificate);
            });
        }

        if ($request->filled('min_land')) {
            $listingsQuery->whereHas('propertyDetail', function ($property) use ($request) {
                $property->where('land_area', '>=', $request->min_land);
            });
        }

        $selectedCategory = $categoryId ? $categories->firstWhere('id', (int) $categoryId) : null;

        if ($selectedCategory?->isHouse()) {
            if ($request->filled('bedrooms')) {
                $listingsQuery->whereHas('propertyDetail', function ($property) use ($request) {
                    $property->where('bedrooms', '>=', $request->bedrooms);
                });
            }

            if ($request->filled('is_kpr')) {
                $listingsQuery->whereHas('propertyDetail', function ($property) use ($request) {
                    $property->where('is_kpr', (bool) $request->is_kpr);
                });
            }
        }

        if ($selectedCategory?->isCarLike()) {
            if ($request->filled('brand')) {
                $listingsQuery->whereHas('carDetail', function ($car) use ($request) {
                    $car->where('brand', 'like', '%'.$request->brand.'%');
                });
            }

            if ($request->filled('transmission')) {
                $listingsQuery->whereHas('carDetail', function ($car) use ($request) {
                    $car->where('transmission', $request->transmission);
                });
            }

            if ($request->filled('fuel_type')) {
                $listingsQuery->whereHas('carDetail', function ($car) use ($request) {
                    $car->where('fuel_type', $request->fuel_type);
                });
            }
        } elseif ($selectedCategory?->isMotorcycle()) {
            if ($request->filled('brand')) {
                $listingsQuery->whereHas('motorcycleDetail', function ($motorcycle) use ($request) {
                    $motorcycle->where('brand', 'like', '%'.$request->brand.'%');
                });
            }

            if ($request->filled('transmission')) {
                $listingsQuery->whereHas('motorcycleDetail', function ($motorcycle) use ($request) {
                    $motorcycle->where('transmission', $request->transmission);
                });
            }
        } elseif (in_array($productType, ['car', 'vehicle'], true)) {
            if ($request->filled('brand')) {
                $listingsQuery->where(function ($vehicle) use ($request) {
                    $vehicle->whereHas('carDetail', function ($car) use ($request) {
                        $car->where('brand', 'like', '%'.$request->brand.'%');
                    })->orWhereHas('motorcycleDetail', function ($motorcycle) use ($request) {
                        $motorcycle->where('brand', 'like', '%'.$request->brand.'%');
                    });
                });
            }

            if ($request->filled('transmission')) {
                $listingsQuery->where(function ($vehicle) use ($request) {
                    $vehicle->whereHas('carDetail', function ($car) use ($request) {
                        $car->where('transmission', $request->transmission);
                    })->orWhereHas('motorcycleDetail', function ($motorcycle) use ($request) {
                        $motorcycle->where('transmission', $request->transmission);
                    });
                });
            }

            if ($request->filled('fuel_type')) {
                $listingsQuery->whereHas('carDetail', function ($car) use ($request) {
                    $car->where('fuel_type', $request->fuel_type);
                });
            }
        }

        $listings = $listingsQuery->latest()->paginate(12, ['*'], 'listings_page')->appends($request->query());

        $posts = Post::with('images')
            ->when($keyword !== '', function ($query) use ($keyword, $postTerms) {
                $query->where(function ($postQuery) use ($keyword, $postTerms) {
                    $this->applyPostSearchTerm($postQuery, $keyword);

                    if ($postTerms->count() > 1) {
                        $postQuery->orWhere(function ($tokenQuery) use ($postTerms) {
                            foreach ($postTerms as $term) {
                                $tokenQuery->where(function ($termQuery) use ($term) {
                                    $this->applyPostSearchTerm($termQuery, $term);
                                });
                            }
                        });
                    }
                });

                $query->orderByRaw(
                    'CASE
                        WHEN title LIKE ? THEN 0
                        WHEN title LIKE ? THEN 1
                        WHEN source_name LIKE ? THEN 2
                        ELSE 3
                    END',
                    [$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%']
                );
            })
            ->latest()
            ->paginate(6, ['*'], 'posts_page')
            ->appends($request->query());

        return view('search.index', compact('listings', 'posts', 'categories', 'keyword', 'categoryId'));
    }

    private function parseNaturalListingSearch(string $keyword, $categories): array
    {
        $normalized = $this->normalizeSearchText($keyword);
        $categoryId = $this->detectCategoryIdFromSearch($normalized, $categories);
        [$minPrice, $maxPrice] = $this->detectPriceRangeFromSearch($normalized);
        $terms = $this->meaningfulListingSearchTerms($normalized, $categories);

        return [
            'category_id' => $categoryId,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'terms' => $terms,
        ];
    }

    private function applyFinalPriceFilter($query, ?int $minPrice, ?int $maxPrice): void
    {
        $finalPriceSql = 'CASE WHEN discount_price IS NOT NULL AND discount_price > 0 AND discount_price < price THEN discount_price ELSE price END';

        if ($minPrice) {
            $query->whereRaw($finalPriceSql.' >= ?', [$minPrice]);
        }

        if ($maxPrice) {
            $query->whereRaw($finalPriceSql.' <= ?', [$maxPrice]);
        }
    }

    private function normalizeSearchText(string $keyword): string
    {
        $text = mb_strtolower($keyword);
        $text = str_replace(['rp.', 'rp', ','], ['', '', '.'], $text);
        $text = preg_replace('/(\d)\s*-\s*(\d)/', '$1 - $2', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text ?? '');
    }

    private function detectCategoryIdFromSearch(string $keyword, $categories): ?int
    {
        $aliases = [
            1 => ['rumah', 'hunian'],
            2 => ['tanah', 'lahan', 'kavling'],
            3 => ['mobil', 'car'],
            4 => ['motor', 'motorcycle', 'sepeda motor'],
            5 => ['ruko', 'rumah toko'],
            6 => ['perkantoran', 'kantor', 'ruang kantor'],
            7 => ['gudang', 'warehouse'],
            8 => ['kios', 'lapak'],
            9 => ['truk', 'truck', 'kendaraan komersil', 'kendaraan komersial', 'komersil'],
        ];

        foreach ($categories as $category) {
            $aliases[(int) $category->id][] = mb_strtolower($category->name);
            $aliases[(int) $category->id][] = mb_strtolower($category->slug);
        }

        foreach ($aliases as $id => $words) {
            foreach (array_unique($words) as $word) {
                if ($word !== '' && preg_match('/\b'.preg_quote($word, '/').'\b/u', $keyword)) {
                    return (int) $id;
                }
            }
        }

        return null;
    }

    private function detectPriceRangeFromSearch(string $keyword): array
    {
        if ($keyword === '') {
            return [null, null];
        }

        $numberPattern = '(\d+(?:[.,]\d+)?)';
        $unitPattern = '(miliar|milyar|m|juta|jt|ribu|rb)?';

        if (preg_match('/(?:di\s*bawah|dibawah|kurang\s+dari|maksimal|max|sampai)\s+'.$numberPattern.'\s*'.$unitPattern.'/u', $keyword, $match)) {
            return [null, $this->priceToInteger($match[1], $match[2] ?? null)];
        }

        if (preg_match('/(?:di\s*atas|diatas|lebih\s+dari|minimal|min)\s+'.$numberPattern.'\s*'.$unitPattern.'/u', $keyword, $match)) {
            return [$this->priceToInteger($match[1], $match[2] ?? null), null];
        }

        if (preg_match('/'.$numberPattern.'\s*'.$unitPattern.'\s*(?:-|sampai|hingga|sd|s\/d)\s*'.$numberPattern.'\s*'.$unitPattern.'/u', $keyword, $match)) {
            $leftUnit = $match[2] ?: ($match[4] ?? null);
            $rightUnit = $match[4] ?: $leftUnit;

            $min = $this->priceToInteger($match[1], $leftUnit);
            $max = $this->priceToInteger($match[3], $rightUnit);

            return $min <= $max ? [$min, $max] : [$max, $min];
        }

        if (preg_match('/(?:harga|budget|anggaran)\s*'.$numberPattern.'\s*'.$unitPattern.'/u', $keyword, $match)
            || preg_match('/'.$numberPattern.'\s+(miliar|milyar|juta|jt|ribu|rb)\b/u', $keyword, $match)) {
            $price = $this->priceToInteger($match[1], $match[2] ?? null);

            if ($price) {
                $margin = (int) max(50000000, round($price * 0.15));
                return [max(0, $price - $margin), $price + $margin];
            }
        }

        return [null, null];
    }

    private function priceToInteger(string $number, ?string $unit): ?int
    {
        $value = (float) str_replace(',', '.', $number);
        $unit = $unit ? mb_strtolower($unit) : null;

        $multiplier = match ($unit) {
            'miliar', 'milyar', 'm' => 1000000000,
            'juta', 'jt' => 1000000,
            'ribu', 'rb' => 1000,
            default => $value < 10000 ? 1000000 : 1,
        };

        return (int) round($value * $multiplier);
    }

    private function meaningfulListingSearchTerms(string $keyword, $categories)
    {
        $text = preg_replace('/\d+(?:[.,]\d+)?\s*(?:miliar|milyar|m|juta|jt|ribu|rb)?/u', ' ', $keyword);
        $text = preg_replace('/\b(?:harga|budget|anggaran|dengan|yang|untuk|cari|carikan|saya|mau|ingin|di|ke|dan|atau|antara|sampai|hingga|sd|s\/d|kurang|lebih|dari|minimal|min|maksimal|max|dibawah|diatas|bawah|atas)\b/u', ' ', $text);

        $categoryWords = $categories
            ->flatMap(fn ($category) => [$category->name, $category->slug])
            ->merge(['rumah', 'hunian', 'tanah', 'lahan', 'kavling', 'mobil', 'motor', 'ruko', 'kantor', 'perkantoran', 'gudang', 'kios'])
            ->map(fn ($word) => preg_quote(mb_strtolower($word), '/'))
            ->filter()
            ->implode('|');

        if ($categoryWords !== '') {
            $text = preg_replace('/\b(?:'.$categoryWords.')\b/u', ' ', $text);
        }

        return $this->searchTermsFromKeyword($text);
    }

    private function searchTermsFromKeyword(string $keyword)
    {
        return collect(preg_split('/\s+/', trim($keyword)))
            ->map(fn ($term) => trim($term, " \t\n\r\0\x0B.,;:!?()[]{}'\""))
            ->filter(fn ($term) => mb_strlen($term) > 1)
            ->unique()
            ->take(8)
            ->values();
    }

    private function applyListingSearchTerm($query, string $term): void
    {
        $query->where('title', 'like', '%'.$term.'%')
            ->orWhere('product_code', 'like', '%'.$term.'%')
            ->orWhere('location', 'like', '%'.$term.'%')
            ->orWhere('description', 'like', '%'.$term.'%')
            ->orWhereHas('category', function ($category) use ($term) {
                $category->where('name', 'like', '%'.$term.'%')
                    ->orWhere('slug', 'like', '%'.$term.'%');
            })
            ->orWhereHas('user', function ($user) use ($term) {
                $user->where('name', 'like', '%'.$term.'%');
            })
            ->orWhereHas('propertyDetail', function ($property) use ($term) {
                $property->where('house_type', 'like', '%'.$term.'%')
                    ->orWhere('certificate', 'like', '%'.$term.'%')
                    ->orWhere('facilities', 'like', '%'.$term.'%');
            })
            ->orWhereHas('carDetail', function ($car) use ($term) {
                $car->where('brand', 'like', '%'.$term.'%')
                    ->orWhere('model', 'like', '%'.$term.'%')
                    ->orWhere('color', 'like', '%'.$term.'%')
                    ->orWhere('fuel_type', 'like', '%'.$term.'%');
            })
            ->orWhereHas('motorcycleDetail', function ($motorcycle) use ($term) {
                $motorcycle->where('brand', 'like', '%'.$term.'%')
                    ->orWhere('model', 'like', '%'.$term.'%');
            });
    }

    private function applyPostSearchTerm($query, string $term): void
    {
        $query->where('title', 'like', '%'.$term.'%')
            ->orWhere('content', 'like', '%'.$term.'%')
            ->orWhere('source_name', 'like', '%'.$term.'%');
    }

    private function abortIfCategoryInactive(string $slug): void
    {
        abort_unless(Category::where('slug', $slug)->where('is_active', true)->exists(), 404);
    }
}
