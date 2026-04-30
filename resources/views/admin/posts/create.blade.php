@extends('layouts.admin')

@section('admin_content')
<div class="max-w-3xl">

    <h1 class="text-2xl font-bold mb-6">Tambah Berita</h1>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow space-y-4">

        @csrf

        <!-- JUDUL -->
        <div>
            <label class="block font-medium mb-1">Judul</label>
            <input type="text" name="title" 
                value="{{ old('title') }}"
                class="w-full border p-3 rounded-lg"
                placeholder="Masukkan judul berita">
        </div>

        <!-- KONTEN -->
        <div>
            <label class="block font-medium mb-1">Isi Berita</label>
            <textarea name="content" rows="6"
                class="w-full border p-3 rounded-lg"
                placeholder="Tulis isi berita...">{{ old('content') }}</textarea>
        </div>

        <!-- SOURCE (OPSIONAL) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Sumber (opsional)</label>
                <input type="text" name="source_name"
                    value="{{ old('source_name') }}"
                    class="w-full border p-3 rounded-lg"
                    placeholder="Contoh: Kompas">
            </div>

            <div>
                <label class="block font-medium mb-1">Link Sumber</label>
                <input type="text" name="source_url"
                    value="{{ old('source_url') }}"
                    class="w-full border p-3 rounded-lg"
                    placeholder="https://...">
            </div>
        </div>

        <!-- UPLOAD GAMBAR -->
        <div>
            <label class="block font-medium mb-2">Upload Gambar</label>
            <input type="file" name="images[]" multiple 
                id="imageInput"
                class="w-full border p-2 rounded">

            <!-- PREVIEW -->
            <div id="preview" class="grid grid-cols-3 gap-3 mt-3"></div>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                Simpan
            </button>

            <a href="{{ route('posts.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">
                Kembali
            </a>
        </div>

    </form>

</div>

{{-- PREVIEW SCRIPT --}}
<script>
const input = document.getElementById('imageInput');
const preview = document.getElementById('preview');

input.addEventListener('change', function() {
    preview.innerHTML = '';

    [...this.files].forEach(file => {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('w-full','h-24','object-cover','rounded');
            preview.appendChild(img);
        }

        reader.readAsDataURL(file);
    });
});
</script>

@endsection
