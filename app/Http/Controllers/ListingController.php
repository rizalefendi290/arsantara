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

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['category','images','user'])
                    ->latest()
                    ->get();

        return view('listings.index', compact('listings'));
    }

    public function home()
    {
        $categories = Category::with(['listings.images'])->get();
        $carousels = Carousel::all();

        return view('user.home', compact('categories','carousels'));
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
            'category_id' => 'required',
            'price' => 'required',
            'location' => 'required',
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
            ->with('success','Listing berhasil ditambahkan');
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
}