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
        $categories = Category::with(['listings.images'])
            ->get();

        // Ambil listing (untuk filter halaman "lihat semua")
        $listings = Listing::with('images')
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
        $query = Listing::with('images')
            ->whereIn('category_id', [3, 4]); // mobil & motor

        // 🔍 FILTER KEYWORD
        if ($request->keyword) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
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
            $query->whereHas('carDetail', function ($q) use ($request) {
                $q->where('transmission', $request->transmission);
            });
        }

        $listings = $query->latest()->paginate(8);

        return view('autoshow.index', compact('listings'));
    }

    public function autoshowFilter(Request $request)
    {
        $query = Listing::with(['images','carDetail','motorcycleDetail'])
            ->whereIn('category_id', [3,4]);

        if ($request->keyword) {
            $query->where('title','like','%'.$request->keyword.'%');
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
            $query->whereHas('carDetail', function($q) use ($request){
                $q->where('transmission',$request->transmission);
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
        // RUMAH KPR
        $rumahKpr = Listing::with(['images','propertyDetail'])
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', true);
            })
            ->latest()
            ->take(8)
            ->get();

        // RUMAH NON KPR
        $rumahNonKpr = Listing::with(['images','propertyDetail'])
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', false);
            })
            ->latest()
            ->take(8)
            ->get();

        // TANAH
        $tanah = Listing::with(['images','propertyDetail'])
            ->where('category_id', 2)
            ->latest()
            ->take(8)
            ->get();

        return view('properti.index', compact('rumahKpr','rumahNonKpr','tanah'));
    }

    public function propertiFilter(Request $request)
    {
        $query = Listing::with(['images','propertyDetail']);

        // kategori rumah / tanah
        if ($request->category) {
            $query->where('category_id', $request->category);
        } else {
            $query->whereIn('category_id', [1,2]);
        }

        // keyword
        if ($request->keyword) {
            $query->where('title','like','%'.$request->keyword.'%');
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
        $query = Listing::with(['images']);

        // FILTER BERDASARKAN SLUG
        if ($slug == 'mobil') {
            $query->where('category_id', 3);
            $title = 'Mobil';
        } elseif ($slug == 'motor') {
            $query->where('category_id', 4);
            $title = 'Motor';
        } elseif ($slug == 'rumah') {
            $query->where('category_id', 1);
            $title = 'Rumah';
        } elseif ($slug == 'tanah') {
            $query->where('category_id', 2);
            $title = 'Tanah';
        } else {
            abort(404);
        }

        $listings = $query->latest()->paginate(8);

        return view('category.index', compact('listings','title','slug'));
    }

    public function mobil()
    {
        $listings = Listing::with(['images','car'])
            ->where('category_id', 3)
            ->latest()
            ->paginate(8);

        return view('mobil.index', compact('listings'));
    }

    public function motor()
    {
        $listings = Listing::with(['images','motorcycle'])
            ->where('category_id', 4)
            ->latest()
            ->paginate(8);

        return view('motor.index', compact('listings'));
    }

    public function rumah()
    {
        $listings = Listing::with(['images','property'])
            ->where('category_id', 1)
            ->latest()
            ->paginate(8);

        return view('rumah.index', compact('listings'));
    }

    public function tanah()
    {
        $listings = Listing::with(['images','property'])
            ->where('category_id', 2)
            ->latest()
            ->paginate(8);

        return view('tanah.index', compact('listings'));
    }
}