<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Listing;

class RumahController extends Controller
{
    public function index()
    {
        // SEMUA RUMAH
        $listings = Listing::with(['images','property'])
            ->inCategorySlug(Category::HOUSE_SLUG)
            ->latest()
            ->get();

        // KPR
        $kpr = Listing::with(['images','property'])
            ->inCategorySlug(Category::HOUSE_SLUG)
            ->whereHas('property', function($q){
                $q->where('is_kpr', 1);
            })
            ->latest()
            ->get();

        // NON KPR
        $nonKpr = Listing::with(['images','property'])
            ->inCategorySlug(Category::HOUSE_SLUG)
            ->whereHas('property', function($q){
                $q->where('is_kpr', 0);
            })
            ->latest()
            ->get();

        $houseCategory = Category::where('slug', Category::HOUSE_SLUG)->first();

        return view('rumah.index', compact('listings','kpr','nonKpr','houseCategory'));
    }
}
