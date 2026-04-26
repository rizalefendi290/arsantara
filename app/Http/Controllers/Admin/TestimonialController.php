<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    // toggle tampil / tidak
    public function toggle($id)
    {
        $item = Testimonial::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        return back()->with('success', 'Status berhasil diubah');
    }

    public function destroy($id)
    {
        $item = Testimonial::findOrFail($id);

        if ($item->photo) {
            \Storage::delete($item->photo);
        }

        $item->delete();

        return back()->with('success', 'Testimoni dihapus');
    }
}