<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['images','car'])
            ->where('category_id', 3); // mobil

        // 🔍 FILTER HARGA
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🔍 FILTER TAHUN
        if ($request->year) {
            $query->whereHas('car', function($q) use ($request){
                $q->where('year', $request->year);
            });
        }

        // 🔍 FILTER MERK
        if ($request->brand) {
            $query->whereHas('car', function($q) use ($request){
                $q->where('brand', $request->brand);
            });
        }

        // 🔍 FILTER TRANSMISI
        if ($request->transmission) {
            $query->whereHas('car', function($q) use ($request){
                $q->where('transmission', $request->transmission);
            });
        }

        // 🔍 FILTER LOKASI
        if ($request->location) {
            $query->where('location', 'like', '%'.$request->location.'%');
        }

        // 🔄 SORTING
        if ($request->sort == 'termurah') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'termahal') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(8)->withQueryString();

        // ambil data filter unik
        $brands = \App\Models\Car::select('brand')->distinct()->pluck('brand');
        $years = \App\Models\Car::select('year')->distinct()->orderBy('year','desc')->pluck('year');

        return view('mobil.index', compact('listings','brands','years'));
    }
}
