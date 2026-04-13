<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalListings = Listing::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();

        $listings = Listing::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalListings',
            'totalUsers',
            'totalCategories',
            'listings'
        ));
    }
}
