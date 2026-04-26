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

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['category','images','user'])
                    ->latest()
                    ->get();

        return view('rumah.index', compact('listings'));
    }

    public function home()
    {
        $categories = Category::with(['listings.images'])->get();
        $carousels = Carousel::all();
        $posts = Post::latest()->take(6)->get();
        $testimonials = Testimonial::where('is_active',1)->latest()->get();

        return view('user.home', compact('categories','carousels','posts','testimonials'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('listings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|in:1,2,3,4',
            'price' => 'required|numeric',
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

                $path = $image->store('listings','public');

                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success','Data berhasil ditambahkan');
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
        ])->findOrFail($id);

        $similar = Listing::with('images')
        ->where('category_id', $listing->category_id)
        ->where('id', '!=', $listing->id)
        ->latest()
        ->take(6)
        ->get();

        return view('listings.show', compact('listing','similar'));
    }

    public function edit($id)
    {
        $listing = Listing::findOrFail($id);
        $categories = Category::all();

        return view('listings.show', compact('listing','categories'));
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);

        $listing->update($request->all());

        return redirect()->route('listings.index')
            ->with('success','Listing berhasil diupdate');
    }

    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();

        return back()->with('success','Listing dihapus');
    }

    public function rumah(Request $request)
    {
        // Base query untuk semua rumah
        $listingsQuery = Listing::with(['images','propertyDetail'])
            ->where('category_id', 1);

        // rumah KPR query
        $kprQuery = Listing::with(['images','propertyDetail'])
            ->where('category_id', 1)
            ->whereHas('propertyDetail', function($q){
                $q->where('is_kpr', true);
            });

        // rumah NON KPR query
        $nonKprQuery = Listing::with(['images','propertyDetail'])
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
}