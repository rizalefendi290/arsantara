<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();
        return view('admin.carousel.index', compact('carousels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:4096'],
            'title' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $path = $request->file('image')->store('carousel','public');

            Carousel::create([
                'image' => $path,
                'title' => $request->title,
                'link_url' => $this->normalizeLink($request->link_url),
            ]);
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:4096'],
            'title' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $carousel = Carousel::findOrFail($id);

        $data = [
            'title' => $request->title,
            'link_url' => $this->normalizeLink($request->link_url),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($carousel->image);
            $data['image'] = $request->file('image')->store('carousel', 'public');
        }

        $carousel->update($data);

        return back()->with('success', 'Carousel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);
        Storage::disk('public')->delete($carousel->image);
        $carousel->delete();

        return back();
    }

    private function normalizeLink(?string $link): ?string
    {
        $link = trim((string) $link);

        if ($link === '') {
            return null;
        }

        if (
            str_starts_with($link, 'http://') ||
            str_starts_with($link, 'https://') ||
            str_starts_with($link, '/') ||
            str_starts_with($link, '#')
        ) {
            return $link;
        }

        return '/'.$link;
    }
}
