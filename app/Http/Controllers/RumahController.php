<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;

class RumahController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['images','property'])
            ->where('category_id', 1) // kategori rumah
            ->latest()
            ->paginate(6);

        return view('rumah.index', compact('listings'));
    }
}