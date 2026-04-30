<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    // ================= USER =================

    public function index()
    {
        $testimonials = Testimonial::where('is_active',1)->latest()->paginate(9);
        return view('testimoni.index', compact('testimonials'));
    }

    public function create()
    {
        return view('testimoni.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'job' => 'nullable|string|max:100',
            'message' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photo = null;

        if($request->hasFile('photo')){
            $photo = $request->file('photo')->store('testimonials','public');
        }

        Testimonial::create([
            'name' => $request->name,
            'job' => $request->job,
            'message' => $request->message,
            'rating' => $request->rating,
            'photo' => $photo,
            'is_active' => 1 // tunggu admin approve
        ]);

        return redirect()->route('testimoni.index')
            ->with('success','Testimoni berhasil dikirim!');
    }

    // ================= ADMIN =================

    public function adminIndex()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function update(Request $request, $id)
    {
        $testi = Testimonial::findOrFail($id);

        $testi->update([
            'is_active' => $request->is_active
        ]);

        return back()->with('success','Status berhasil diupdate');
    }

    public function destroy($id)
    {
        $testi = Testimonial::findOrFail($id);
        $testi->delete();

        return back()->with('success','Berhasil dihapus');
    }
}
