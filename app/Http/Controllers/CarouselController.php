<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();
        return view('admin.carousel.index', compact('carousels'));
    }

    public function store(Request $request)
    {
        if($request->hasFile('image')){
            $path = $request->file('image')->store('carousel','public');

            Carousel::create([
                'image' => $path,
                'title' => $request->title
            ]);
        }

        return back();
    }

    public function destroy($id)
    {
        Carousel::findOrFail($id)->delete();
        return back();
    }
}
