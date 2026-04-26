<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;

class RumahController extends Controller
{
    public function index()
    {
        // SEMUA RUMAH
        $listings = Listing::with(['images','property'])
            ->where('category_id', 1)
            ->latest()
            ->get();

        // KPR
        $kpr = Listing::with(['images','property'])
            ->where('category_id', 1)
            ->whereHas('property', function($q){
                $q->where('is_kpr', 1);
            })
            ->latest()
            ->get();

        // NON KPR
        $nonKpr = Listing::with(['images','property'])
            ->where('category_id', 1)
            ->whereHas('property', function($q){
                $q->where('is_kpr', 0);
            })
            ->latest()
            ->get();

        return view('rumah.index', compact('listings','kpr','nonKpr'));
    }
}