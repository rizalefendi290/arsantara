<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Carousel;
use App\Models\Post;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::latest()->take(6)->get();
        $testimonials = Testimonial::where('is_active', 1)
            ->latest()
            ->take(10)
            ->get();
        // Ambil parameter filter kategori (optional)
        $categoryId = $request->category;

        // Ambil semua kategori + relasi listing + image
        $categories = Category::active()->with([
            'listings' => fn ($query) => $query->active()->with('images')
        ])
            ->get();

        // Ambil listing (untuk filter halaman "lihat semua")
        $listings = Listing::with('images')
            ->active()
            ->inActiveCategory()
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(8);

        // Carousel
        $carousels = Carousel::latest()->get();

        return view('user.home', compact(
            'categories',
            'listings',
            'carousels',
            'posts',
            'testimonials'
        ));
    }
    
    public function autoshow(Request $request)
    {
        $query = Listing::with(['images','category','carDetail','motorcycleDetail'])
            ->active()
            ->inActiveCategory()
            ->whereIn('category_id', [3, 4]); // mobil & motor

        // 🔍 FILTER KEYWORD
        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%')
                    ->orWhereHas('carDetail', function ($car) use ($keyword) {
                        $car->where('brand', 'like', '%' . $keyword . '%')
                            ->orWhere('model', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('motorcycleDetail', function ($motorcycle) use ($keyword) {
                        $motorcycle->where('brand', 'like', '%' . $keyword . '%')
                            ->orWhere('model', 'like', '%' . $keyword . '%');
                    });
            });
        }

        // 🔍 FILTER HARGA
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🔍 FILTER KONDISI
        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        // 🔍 FILTER TRANSMISI (relasi)
        if ($request->transmission) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('carDetail', function ($car) use ($request) {
                    $car->where('transmission', $request->transmission);
                })->orWhereHas('motorcycleDetail', function ($motorcycle) use ($request) {
                    $motorcycle->where('transmission', $request->transmission);
                });
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $listings = $query->latest()->paginate(8)->appends($request->all());

        return view('autoshow.index', compact('listings'));
    }

    public function autoshowFilter(Request $request)
    {
        $query = Listing::with(['images','category','carDetail','motorcycleDetail'])
            ->active()
            ->inActiveCategory()
            ->whereIn('category_id', [3,4]);

        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title','like','%'.$keyword.'%')
                    ->orWhere('location','like','%'.$keyword.'%')
                    ->orWhereHas('carDetail', function ($car) use ($keyword) {
                        $car->where('brand','like','%'.$keyword.'%')
                            ->orWhere('model','like','%'.$keyword.'%');
                    })
                    ->orWhereHas('motorcycleDetail', function ($motorcycle) use ($keyword) {
                        $motorcycle->where('brand','like','%'.$keyword.'%')
                            ->orWhere('model','like','%'.$keyword.'%');
                    });
            });
        }

        if ($request->min_price) {
            $query->where('price','>=',$request->min_price);
        }

        if ($request->max_price) {
            $query->where('price','<=',$request->max_price);
        }

        if ($request->condition) {
            $query->where('condition',$request->condition);
        }

        if ($request->transmission) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('carDetail', function($car) use ($request){
                    $car->where('transmission',$request->transmission);
                })->orWhereHas('motorcycleDetail', function($motorcycle) use ($request){
                    $motorcycle->where('transmission',$request->transmission);
                });
            });
        }
        if ($request->category) {
            $query->where('category_id', $request->category);
        } else {
            $query->whereIn('category_id', [3,4]);
        }

        $listings = $query->latest()->paginate(8)->appends($request->all());

        return view('autoshow.partials.list', compact('listings'))->render();
    }

    public function properti(Request $request)
    {
        $rumahActive = Category::whereKey(1)->where('is_active', true)->exists();
        $tanahActive = Category::whereKey(2)->where('is_active', true)->exists();

        // RUMAH KPR
        $rumahKpr = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inActiveCategory()
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', true);
            })
            ->latest()
            ->take(8)
            ->get();

        // RUMAH NON KPR
        $rumahNonKpr = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inActiveCategory()
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', false);
            })
            ->latest()
            ->take(8)
            ->get();

        // TANAH
        $tanah = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inActiveCategory()
            ->where('category_id', 2)
            ->latest()
            ->take(8)
            ->get();

        $listings = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->inActiveCategory()
            ->whereIn('category_id', [1, 2])
            ->latest()
            ->paginate(8);

        return view('properti.index', compact('rumahKpr','rumahNonKpr','tanah','listings','rumahActive','tanahActive'));
    }

    public function propertiFilter(Request $request)
    {
        $query = Listing::with(['images','category','propertyDetail']);
        $query->active()->inActiveCategory();

        // kategori rumah / tanah
        if ($request->category) {
            $query->where('category_id', $request->category);
        } else {
            $query->whereIn('category_id', [1,2]);
        }

        // keyword
        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title','like','%'.$keyword.'%')
                    ->orWhere('location','like','%'.$keyword.'%')
                    ->orWhereHas('propertyDetail', function($property) use ($keyword) {
                        $property->where('house_type','like','%'.$keyword.'%')
                            ->orWhere('certificate','like','%'.$keyword.'%');
                    });
            });
        }

        // harga
        if ($request->min_price) {
            $query->where('price','>=',$request->min_price);
        }

        if ($request->max_price) {
            $query->where('price','<=',$request->max_price);
        }

        // sertifikat
        if ($request->certificate) {
            $query->whereHas('propertyDetail', function($q) use ($request){
                $q->where('certificate',$request->certificate);
            });
        }

        $listings = $query->latest()->paginate(8)->appends($request->all());

        return view('properti.partials.list', compact('listings'))->render();
    }

    public function category($slug)
    {
        $query = Listing::with(['images'])->active()->inActiveCategory();

        // FILTER BERDASARKAN SLUG
        if ($slug == 'mobil') {
            $this->abortIfCategoryInactive(3);
            $query->where('category_id', 3);
            $title = 'Mobil';
        } elseif ($slug == 'motor') {
            $this->abortIfCategoryInactive(4);
            $query->where('category_id', 4);
            $title = 'Motor';
        } elseif ($slug == 'rumah') {
            $this->abortIfCategoryInactive(1);
            $query->where('category_id', 1);
            $title = 'Rumah';
        } elseif ($slug == 'tanah') {
            $this->abortIfCategoryInactive(2);
            $query->where('category_id', 2);
            $title = 'Tanah';
        } else {
            abort(404);
        }

        $listings = $query->latest()->paginate(8);

        return view('category.index', compact('listings','title','slug'));
    }

    public function mobil(Request $request)
    {
        $this->abortIfCategoryInactive(3);

        $query = Listing::with(['images','category','carDetail'])
            ->active()
            ->where('category_id', 3);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhereHas('carDetail', function ($car) use ($search) {
                        $car->where('brand', 'like', '%'.$search.'%')
                            ->orWhere('model', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('carDetail', fn ($car) => $car->where('brand', $request->brand));
        }

        if ($request->filled('transmission')) {
            $query->whereHas('carDetail', fn ($car) => $car->where('transmission', $request->transmission));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->sort;
        if ($sort === 'low') {
            $query->orderBy('price');
        } elseif ($sort === 'high') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->appends($request->query());

        return view('mobil.index', compact('listings'));
    }

    public function motor(Request $request)
    {
        $this->abortIfCategoryInactive(4);

        $query = Listing::with(['images','category','motorcycleDetail'])
            ->active()
            ->where('category_id', 4);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhereHas('motorcycleDetail', function ($motorcycle) use ($search) {
                        $motorcycle->where('brand', 'like', '%'.$search.'%')
                            ->orWhere('model', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('motorcycleDetail', fn ($motorcycle) => $motorcycle->where('brand', $request->brand));
        }

        if ($request->filled('transmission')) {
            $query->whereHas('motorcycleDetail', fn ($motorcycle) => $motorcycle->where('transmission', $request->transmission));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->sort;
        if ($sort === 'low') {
            $query->orderBy('price');
        } elseif ($sort === 'high') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->appends($request->query());

        return view('motor.index', compact('listings'));
    }

    public function rumah()
    {
        $this->abortIfCategoryInactive(1);

        $listings = Listing::with(['images','property'])
            ->active()
            ->where('category_id', 1)
            ->latest()
            ->paginate(8);

        return view('rumah.index', compact('listings'));
    }

    public function tanah(Request $request)
    {
        $this->abortIfCategoryInactive(2);

        $query = Listing::with(['images','category','propertyDetail'])
            ->active()
            ->where('category_id', 2);

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->location.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_land')) {
            $query->whereHas('propertyDetail', fn ($property) => $property->where('land_area', '>=', $request->min_land));
        }

        if ($request->filled('certificate')) {
            $query->whereHas('propertyDetail', fn ($property) => $property->where('certificate', $request->certificate));
        }

        $sort = $request->sort;
        if ($sort === 'low') {
            $query->orderBy('price');
        } elseif ($sort === 'high') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12)->appends($request->query());

        return view('tanah.index', compact('listings'));
    }

    private function abortIfCategoryInactive(int $categoryId): void
    {
        abort_unless(Category::whereKey($categoryId)->where('is_active', true)->exists(), 404);
    }
}
