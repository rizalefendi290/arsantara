<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PostImage;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(8);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // SIMPAN DATA POST
        $post = \App\Models\Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'source_name' => $request->source_name,
            'source_url' => $request->source_url,
        ]);

        // 🔥 SIMPAN MULTIPLE IMAGE
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $path = $image->store('posts', 'public');

                $post->images()->create([
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('posts.index')
            ->with('success','Berhasil tambah berita');
    }

    public function edit($id)
    {
        $post = \App\Models\Post::with('images')->findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = \App\Models\Post::findOrFail($id);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'source_url' => $request->source_url,
            'source_name' => $request->source_name,
        ]);

        // upload gambar baru
        if($request->hasFile('images')){
            foreach($request->file('images') as $file){
                $path = $file->store('posts','public');

                $post->images()->create([
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('posts.index')
            ->with('success','Berita berhasil diupdate');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success','Berita berhasil dihapus');
    }

    public function show($id)
    {
        $post = \App\Models\Post::latest()->findOrFail($id);

        // ambil berita terkait (kategori sama kalau ada)
        $related = \App\Models\Post::where('id', '!=', $id)
            ->latest()
            ->take(4)
            ->get();

        // sidebar berita terbaru
        $latest = \App\Models\Post::latest()->take(5)->get();

        return view('posts.show', compact('post','related','latest'));
    }

    public function deleteImage($id)
    {
        $image = \App\Models\PostImage::findOrFail($id);

        // hapus file dari storage
        \Storage::disk('public')->delete($image->image);

        $image->delete();

        return back()->with('success','Gambar dihapus');
    }
}