@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Edit Listing</h1>

    <div class="bg-white shadow rounded p-6">

        <form action="{{ route('listings.update',$listing->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block font-medium mb-1">Judul</label>
                <input type="text" name="title"
                    value="{{ $listing->title }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Kategori</label>
                <select name="category_id" class="w-full border rounded p-2">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $listing->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Harga</label>
                <input type="number" name="price"
                    value="{{ $listing->price }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Lokasi</label>
                <input type="text" name="location"
                    value="{{ $listing->location }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Kondisi</label>
                <select name="condition" class="w-full border rounded p-2">
                    <option value="baru" {{ $listing->condition=='baru'?'selected':'' }}>Baru</option>
                    <option value="bekas" {{ $listing->condition=='bekas'?'selected':'' }}>Bekas</option>
                </select>
            </div>

        </div>

        <div class="mt-4">
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="description"
                class="w-full border rounded p-2"
                rows="4">{{ $listing->description }}</textarea>
        </div>

        <!-- Gambar lama -->
        <div class="mt-6">
            <h3 class="font-bold mb-2">Gambar Saat Ini</h3>

            <div class="grid grid-cols-5 gap-2">
                @foreach($listing->images as $img)
                <div class="relative">
                    <img src="{{ asset('storage/'.$img->image) }}"
                        class="h-24 w-full object-cover rounded">

                    <a href="{{ route('images.delete',$img->id) }}"
                        onclick="return confirm('Hapus gambar?')"
                        class="absolute top-1 right-1 bg-red-500 text-white px-2 py-1 text-xs rounded">
                        X
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Upload baru -->
        <div class="mt-6">
            <label class="block font-medium mb-2">Tambah Gambar Baru</label>

            <div id="image-wrapper">
                <input type="file" name="images[]" class="w-full border p-2 rounded mb-2">
            </div>

            <button type="button" onclick="addImage()"
                class="bg-green-500 text-white px-3 py-1 rounded">
                + Tambah Gambar
            </button>
        </div>

        <div class="mt-6 flex gap-2">
            <button class="bg-blue-600 text-white px-6 py-2 rounded">
                Update
            </button>

            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

        </form>

    </div>
</div>

<script>
function addImage(){
    let wrapper = document.getElementById('image-wrapper');

    let input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.className = 'w-full border p-2 rounded mb-2';

    wrapper.appendChild(input);
}
</script>

@endsection