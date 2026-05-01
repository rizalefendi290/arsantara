@extends('layouts.app')

@section('content')
@php
    $isEdit = $listing->exists;
    $property = $listing->propertyDetail;
    $car = $listing->carDetail;
    $motorcycle = $listing->motorcycleDetail;
    $selectedCategory = old('category_id', $listing->category_id);
@endphp

<div class="max-w-5xl mx-auto px-4 py-24">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Listing {{ ucfirst(auth()->user()->role) }}</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                {{ $isEdit ? 'Edit Listing' : 'Tambah Listing' }}
            </h1>
        </div>

        <a href="{{ route('agent.dashboard') }}" class="text-blue-600 hover:underline">
            Kembali ke dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-5">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white border rounded-lg shadow-sm p-5 md:p-6">
        <form action="{{ $isEdit ? route('agent.listings.update', $listing->id) : route('agent.listings.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $listing->title) }}"
                        class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="category_id" id="category" class="w-full border rounded p-2" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) $selectedCategory === (string) $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Harga</label>
                    <input type="number" name="price" value="{{ old('price', $listing->price) }}"
                        class="w-full border rounded p-2" min="0" required>
                </div>

                <div>
                    <label class="block font-medium mb-1">Kondisi</label>
                    <select name="condition" class="w-full border rounded p-2" required>
                        <option value="baru" {{ old('condition', $listing->condition) === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="bekas" {{ old('condition', $listing->condition) === 'bekas' ? 'selected' : '' }}>Bekas</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $listing->location) }}"
                        class="w-full border rounded p-2" required>
                </div>
            </div>

            <div id="rumah-fields" class="mt-6 category-section hidden" data-category="1">
                <h2 class="font-semibold text-gray-800 mb-3">Detail Rumah</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="house_type" value="{{ old('house_type', $property->house_type ?? '') }}" placeholder="Tipe Rumah" class="border p-2 rounded">
                    <input type="number" name="land_area" value="{{ old('land_area', $property->land_area ?? '') }}" placeholder="Luas Tanah" class="border p-2 rounded">
                    <input type="number" name="building_area" value="{{ old('building_area', $property->building_area ?? '') }}" placeholder="Luas Bangunan" class="border p-2 rounded">
                    <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? '') }}" placeholder="Kamar Tidur" class="border p-2 rounded">
                    <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? '') }}" placeholder="Kamar Mandi" class="border p-2 rounded">
                    <input type="number" name="floors" value="{{ old('floors', $property->floors ?? '') }}" placeholder="Jumlah Lantai" class="border p-2 rounded">
                    <input type="text" name="certificate" value="{{ old('certificate', $property->certificate ?? '') }}" placeholder="Sertifikat" class="border p-2 rounded">
                </div>

                <div class="mt-3">
                    <label class="block font-medium mb-1">Jenis Rumah</label>
                    <select name="is_kpr" class="w-full border p-2 rounded">
                        <option value="1" {{ (string) old('is_kpr', (int) ($property->is_kpr ?? 0)) === '1' ? 'selected' : '' }}>KPR</option>
                        <option value="0" {{ (string) old('is_kpr', (int) ($property->is_kpr ?? 0)) === '0' ? 'selected' : '' }}>Non KPR</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block font-medium mb-1">Fasilitas</label>
                    <textarea name="facilities" rows="3" class="w-full border p-2 rounded">{{ old('facilities', $property->facilities ?? '') }}</textarea>
                </div>
            </div>

            <div id="tanah-fields" class="mt-6 category-section hidden" data-category="2">
                <h2 class="font-semibold text-gray-800 mb-3">Detail Tanah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="number" name="land_area" value="{{ old('land_area', $property->land_area ?? '') }}" placeholder="Luas Tanah" class="border p-2 rounded">
                    <input type="text" name="certificate" value="{{ old('certificate', $property->certificate ?? '') }}" placeholder="Sertifikat" class="border p-2 rounded">
                </div>
            </div>

            <div id="mobil-fields" class="mt-6 category-section hidden" data-category="3">
                <h2 class="font-semibold text-gray-800 mb-3">Detail Mobil</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="brand" value="{{ old('brand', $car->brand ?? '') }}" placeholder="Merk Mobil" class="border p-2 rounded">
                    <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" placeholder="Model" class="border p-2 rounded">
                    <input type="number" name="year" value="{{ old('year', $car->year ?? '') }}" min="1901" max="2155" placeholder="Tahun" class="border p-2 rounded">
                    <input type="number" name="engine" value="{{ old('engine', $car->engine ?? '') }}" placeholder="Mesin (cc)" class="border p-2 rounded">
                    <select name="transmission" class="border p-2 rounded">
                        <option value="">Pilih Transmisi</option>
                        <option value="manual" {{ old('transmission', $car->transmission ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="matic" {{ old('transmission', $car->transmission ?? '') === 'matic' ? 'selected' : '' }}>Matic</option>
                    </select>
                    <select name="fuel_type" class="border p-2 rounded">
                        <option value="">Pilih Bahan Bakar</option>
                        <option value="bensin" {{ old('fuel_type', $car->fuel_type ?? '') === 'bensin' ? 'selected' : '' }}>Bensin</option>
                        <option value="diesel" {{ old('fuel_type', $car->fuel_type ?? '') === 'diesel' ? 'selected' : '' }}>Solar</option>
                        <option value="listrik" {{ old('fuel_type', $car->fuel_type ?? '') === 'listrik' ? 'selected' : '' }}>Listrik</option>
                        <option value="hybrid" {{ old('fuel_type', $car->fuel_type ?? '') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    <input type="text" name="color" value="{{ old('color', $car->color ?? '') }}" placeholder="Warna Kendaraan" class="border p-2 rounded">
                    <input type="number" name="kilometer" value="{{ old('kilometer', $car->kilometer ?? '') }}" placeholder="Kilometer" class="border p-2 rounded">
                </div>
            </div>

            <div id="motor-fields" class="mt-6 category-section hidden" data-category="4">
                <h2 class="font-semibold text-gray-800 mb-3">Detail Motor</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="brand" value="{{ old('brand', $motorcycle->brand ?? '') }}" placeholder="Merk" class="border p-2 rounded">
                    <input type="text" name="model" value="{{ old('model', $motorcycle->model ?? '') }}" placeholder="Model" class="border p-2 rounded">
                    <input type="number" name="year" value="{{ old('year', $motorcycle->year ?? '') }}" min="1901" max="2155" placeholder="Tahun" class="border p-2 rounded">
                    <input type="number" name="engine" value="{{ old('engine', $motorcycle->engine ?? '') }}" placeholder="Mesin (cc)" class="border p-2 rounded">
                    <select name="transmission" class="border p-2 rounded">
                        <option value="">Pilih Transmisi</option>
                        <option value="manual" {{ old('transmission', $motorcycle->transmission ?? '') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="matic" {{ old('transmission', $motorcycle->transmission ?? '') === 'matic' ? 'selected' : '' }}>Matic</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="5" class="w-full border rounded p-2">{{ old('description', $listing->description) }}</textarea>
            </div>

            @if($isEdit && $listing->images->count())
                <div class="mt-6">
                    <h2 class="font-semibold text-gray-800 mb-3">Gambar Saat Ini</h2>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach($listing->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/'.$image->image) }}" class="h-28 w-full object-cover rounded" alt="Gambar listing">
                                <button type="submit"
                                    form="delete-image-{{ $image->id }}"
                                    class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded text-xs"
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
                <label class="block font-medium mb-2">Upload Gambar</label>
                <div id="image-wrapper">
                    <input type="file" name="images[]" class="w-full border p-2 rounded">
                </div>
                <button type="button" onclick="addImage()" class="mt-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                    Tambah Gambar
                </button>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    {{ $isEdit ? 'Update Listing' : 'Kirim Listing' }}
                </button>

                <a href="{{ route('agent.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded">
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
    ['rumah', 'tanah', 'mobil', 'motor'].forEach(function(section) {
        const el = document.getElementById(section + '-fields');
        if (!el) return;

        const active = String(el.dataset.category) === String(value);
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
