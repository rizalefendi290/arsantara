@extends('layouts.app')

@section('content')
@php
    $isEdit = $listing->exists;
    $property = $listing->propertyDetail;
    $car = $listing->carDetail;
    $motorcycle = $listing->motorcycleDetail;
    $selectedCategory = old('category_id', $listing->category_id);
    $categoryId = fn (string $slug) => optional($categories->firstWhere('slug', $slug))->id;
    $houseCategoryId = $categoryId(\App\Models\Category::HOUSE_SLUG);
    $landCategoryId = $categoryId(\App\Models\Category::LAND_SLUG);
    $motorcycleCategoryId = $categoryId(\App\Models\Category::MOTORCYCLE_SLUG);
    $commercialCategoryIds = $categories->whereIn('slug', \App\Models\Category::COMMERCIAL_PROPERTY_SLUGS)->pluck('id')->map(fn ($id) => (string) $id)->all();
    $carLikeCategoryIds = $categories->whereIn('slug', \App\Models\Category::CAR_LIKE_SLUGS)->pluck('id')->map(fn ($id) => (string) $id)->all();
@endphp

<div class="max-w-5xl px-4 py-24 mx-auto">
    <div class="flex flex-col gap-3 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-gray-500">Listing {{ ucfirst(auth()->user()->role) }}</p>
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">
                {{ $isEdit ? 'Edit Listing' : 'Tambah Listing' }}
            </h1>
        </div>

        <a href="{{ route('agent.dashboard') }}" class="text-blue-600 hover:underline">
            Kembali ke dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 mb-5 text-red-700 border border-red-200 rounded-lg bg-red-50">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="p-5 bg-white border rounded-lg shadow-sm md:p-6">
        <form action="{{ $isEdit ? route('agent.listings.update', $listing->id) : route('agent.listings.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-1 font-medium">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $listing->title) }}"
                        class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Kategori</label>
                    <select name="category_id" id="category" class="w-full p-2 border rounded" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) $selectedCategory === (string) $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Harga</label>
                    <input type="number" name="price" value="{{ old('price', $listing->price) }}"
                        class="w-full p-2 border rounded" min="0" required>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Kondisi</label>
                    <select name="condition" class="w-full p-2 border rounded" required>
                        <option value="baru" {{ old('condition', $listing->condition) === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="bekas" {{ old('condition', $listing->condition) === 'bekas' ? 'selected' : '' }}>Bekas</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $listing->location) }}"
                        class="w-full p-2 border rounded" required>
                </div>
            </div>

            <div id="rumah-fields" class="hidden mt-6 category-section" data-category="{{ $houseCategoryId }}">
                <h2 class="mb-3 font-semibold text-gray-800">Detail Rumah</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="house_type" value="{{ old('house_type', $property->house_type ?? '') }}" placeholder="Tipe Rumah" class="p-2 border rounded">
                    <input type="number" name="land_area" value="{{ old('land_area', $property->land_area ?? '') }}" placeholder="Luas Tanah" class="p-2 border rounded">
                    <input type="number" name="building_area" value="{{ old('building_area', $property->building_area ?? '') }}" placeholder="Luas Bangunan" class="p-2 border rounded">
                    <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? '') }}" placeholder="Kamar Tidur" class="p-2 border rounded">
                    <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? '') }}" placeholder="Kamar Mandi" class="p-2 border rounded">
                    <input type="number" name="floors" value="{{ old('floors', $property->floors ?? '') }}" placeholder="Jumlah Lantai" class="p-2 border rounded">
                    <input type="text" name="certificate" value="{{ old('certificate', $property->certificate ?? '') }}" placeholder="Sertifikat" class="p-2 border rounded">
                </div>

                <div class="mt-3">
                    <label class="block mb-1 font-medium">Jenis Rumah</label>
                    <select name="is_kpr" class="w-full p-2 border rounded">
                        <option value="1" {{ (string) old('is_kpr', (int) ($property->is_kpr ?? 0)) === '1' ? 'selected' : '' }}>KPR</option>
                        <option value="0" {{ (string) old('is_kpr', (int) ($property->is_kpr ?? 0)) === '0' ? 'selected' : '' }}>Non KPR</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block mb-1 font-medium">Fasilitas</label>
                    <textarea name="facilities" rows="3" class="w-full p-2 border rounded">{{ old('facilities', $property->facilities ?? '') }}</textarea>
                </div>
            </div>

            <div id="tanah-fields" class="hidden mt-6 category-section" data-category="{{ $landCategoryId }}">
                <h2 class="mb-3 font-semibold text-gray-800">Detail Tanah</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <input type="number" name="land_area" value="{{ old('land_area', $property->land_area ?? '') }}" placeholder="Luas Tanah" class="p-2 border rounded">
                    <input type="text" name="certificate" value="{{ old('certificate', $property->certificate ?? '') }}" placeholder="Sertifikat" class="p-2 border rounded">
                </div>
            </div>

            <div id="komersial-fields" class="hidden mt-6 category-section" data-categories="{{ implode(',', $commercialCategoryIds) }}">
                <h2 class="mb-3 font-semibold text-gray-800">Detail Properti Komersial</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="house_type" value="{{ old('house_type', $property->house_type ?? '') }}" placeholder="Tipe / Nama Unit" class="p-2 border rounded">
                    <input type="number" name="land_area" value="{{ old('land_area', $property->land_area ?? '') }}" placeholder="Luas Tanah" class="p-2 border rounded">
                    <input type="number" name="building_area" value="{{ old('building_area', $property->building_area ?? '') }}" placeholder="Luas Bangunan" class="p-2 border rounded">
                    <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? '') }}" placeholder="Kamar Mandi / Toilet" class="p-2 border rounded">
                    <input type="number" name="floors" value="{{ old('floors', $property->floors ?? '') }}" placeholder="Jumlah Lantai" class="p-2 border rounded">
                    <input type="text" name="certificate" value="{{ old('certificate', $property->certificate ?? '') }}" placeholder="Sertifikat" class="p-2 border rounded">
                </div>

                <div class="mt-3">
                    <label class="block mb-1 font-medium">Fasilitas</label>
                    <textarea name="facilities" rows="3" class="w-full p-2 border rounded" placeholder="Parkir, akses truk, listrik, keamanan, dan lainnya...">{{ old('facilities', $property->facilities ?? '') }}</textarea>
                </div>
            </div>

            <div id="mobil-fields" class="hidden mt-6 category-section" data-categories="{{ implode(',', $carLikeCategoryIds) }}">
                <h2 class="mb-3 font-semibold text-gray-800">Detail Kendaraan</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="brand" value="{{ old('brand', $car->brand ?? '') }}" placeholder="Merk Kendaraan" class="p-2 border rounded">
                    <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" placeholder="Model" class="p-2 border rounded">
                    <input type="number" name="year" value="{{ old('year', $car->year ?? '') }}" min="1901" max="2155" placeholder="Tahun" class="p-2 border rounded">
                    <input type="number" name="engine" value="{{ old('engine', $car->engine ?? '') }}" placeholder="Mesin (cc)" class="p-2 border rounded">
                    <select name="transmission" class="p-2 border rounded">
                        <option value="">Pilih Transmisi</option>
                        <option value="manual" {{ old('transmission', $car->transmission ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="matic" {{ old('transmission', $car->transmission ?? '') === 'matic' ? 'selected' : '' }}>Matic</option>
                    </select>
                    <select name="fuel_type" class="p-2 border rounded">
                        <option value="">Pilih Bahan Bakar</option>
                        <option value="bensin" {{ old('fuel_type', $car->fuel_type ?? '') === 'bensin' ? 'selected' : '' }}>Bensin</option>
                        <option value="diesel" {{ old('fuel_type', $car->fuel_type ?? '') === 'diesel' ? 'selected' : '' }}>Solar</option>
                        <option value="listrik" {{ old('fuel_type', $car->fuel_type ?? '') === 'listrik' ? 'selected' : '' }}>Listrik</option>
                        <option value="hybrid" {{ old('fuel_type', $car->fuel_type ?? '') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    <input type="text" name="color" value="{{ old('color', $car->color ?? '') }}" placeholder="Warna Kendaraan" class="p-2 border rounded">
                    <input type="number" name="kilometer" value="{{ old('kilometer', $car->kilometer ?? '') }}" placeholder="Kilometer" class="p-2 border rounded">
                </div>
            </div>

            <div id="motor-fields" class="hidden mt-6 category-section" data-category="{{ $motorcycleCategoryId }}">
                <h2 class="mb-3 font-semibold text-gray-800">Detail Motor</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="brand" value="{{ old('brand', $motorcycle->brand ?? '') }}" placeholder="Merk" class="p-2 border rounded">
                    <input type="text" name="model" value="{{ old('model', $motorcycle->model ?? '') }}" placeholder="Model" class="p-2 border rounded">
                    <input type="number" name="year" value="{{ old('year', $motorcycle->year ?? '') }}" min="1901" max="2155" placeholder="Tahun" class="p-2 border rounded">
                    <input type="number" name="engine" value="{{ old('engine', $motorcycle->engine ?? '') }}" placeholder="Mesin (cc)" class="p-2 border rounded">
                    <select name="transmission" class="p-2 border rounded">
                        <option value="">Pilih Transmisi</option>
                        <option value="manual" {{ old('transmission', $motorcycle->transmission ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="matic" {{ old('transmission', $motorcycle->transmission ?? '') === 'matic' ? 'selected' : '' }}>Matic</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block mb-1 font-medium">Deskripsi</label>
                <textarea name="description" rows="5" class="w-full p-2 border rounded">{{ old('description', $listing->description) }}</textarea>
            </div>

            @if($isEdit && $listing->images->count())
                <div class="mt-6">
                    <h2 class="mb-3 font-semibold text-gray-800">Gambar Saat Ini</h2>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-5">
                        @foreach($listing->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/'.$image->image) }}" class="object-cover w-full rounded h-28" alt="Gambar listing">
                                <button type="submit"
                                    form="delete-image-{{ $image->id }}"
                                    class="absolute px-2 py-1 text-xs text-white bg-red-600 rounded top-2 right-2"
                                    data-swal-confirm="Hapus gambar ini?"
                                    data-swal-confirm-button="Ya, hapus">
                                    Hapus
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <label class="block mb-2 font-medium">Upload Gambar</label>
                <div id="image-wrapper">
                    <input type="file" name="images[]" class="w-full p-2 border rounded">
                </div>
                <button type="button" onclick="addImage()" class="px-3 py-1 mt-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Tambah Gambar
                </button>
            </div>

            <div class="flex flex-wrap gap-3 mt-6">
                <button class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    {{ $isEdit ? 'Update Listing' : 'Kirim Listing' }}
                </button>

                <a href="{{ route('agent.dashboard') }}" class="px-4 py-2 text-gray-800 bg-gray-100 rounded hover:bg-gray-200">
                    Batal
                </a>
            </div>
        </form>

        @if($isEdit)
            @foreach($listing->images as $image)
                <form id="delete-image-{{ $image->id }}" action="{{ route('agent.listings.images.destroy', $image->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>
</div>

<script>
function toggleCategoryFields(value) {
    ['rumah', 'tanah', 'komersial', 'mobil', 'motor'].forEach(function(section) {
        const el = document.getElementById(section + '-fields');
        if (!el) return;

        const categories = (el.dataset.categories || el.dataset.category || '').split(',').filter(Boolean);
        const active = categories.includes(String(value));
        el.classList.toggle('hidden', !active);

        el.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.disabled = !active;
        });
    });
}

document.getElementById('category').addEventListener('change', function() {
    toggleCategoryFields(this.value);
});

document.addEventListener('DOMContentLoaded', function() {
    toggleCategoryFields(document.getElementById('category').value);
});

function addImage() {
    const wrapper = document.getElementById('image-wrapper');
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.className = 'w-full border p-2 rounded mt-2';
    wrapper.appendChild(input);
}
</script>
@endsection
