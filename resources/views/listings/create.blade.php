@extends('layouts.admin')

@section('admin_content')
<div>

<h1 class="text-2xl font-bold mb-6">Tambah Listing</h1>

<div class="bg-white shadow rounded p-6">

<form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- ERROR --}}
@if($errors->any())
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
    @foreach($errors->all() as $error)
        <p>• {{ $error }}</p>
    @endforeach
</div>
@endif

<div class="grid grid-cols-2 gap-4">

    <div>
        <label>Judul</label>
        <input type="text" name="title" value="{{ old('title') }}" class="w-full border p-2 rounded">
    </div>

    <div>
        <label>Kategori</label>
        <select name="category_id" id="category" class="w-full border p-2 rounded">
            <option value="">Pilih</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Harga</label>
        <input type="text" data-rupiah-input data-target="price" value="{{ old('price') ? 'Rp '.number_format((int) old('price'), 0, ',', '.') : '' }}" class="w-full border p-2 rounded" placeholder="Contoh: Rp 250.000.000">
        <input type="hidden" name="price" id="price" value="{{ old('price') }}">
    </div>

    <div>
        <label>Harga Diskon</label>
        <input type="text" data-rupiah-input data-target="discount_price" value="{{ old('discount_price') ? 'Rp '.number_format((int) old('discount_price'), 0, ',', '.') : '' }}" class="w-full border p-2 rounded" placeholder="Contoh: Rp 225.000.000">
        <input type="hidden" name="discount_price" id="discount_price" value="{{ old('discount_price') }}">
    </div>

    <div>
        <label>Kondisi</label>
        <select name="condition" class="w-full border p-2 rounded">
            <option value="baru">Baru</option>
            <option value="bekas">Bekas</option>
        </select>
    </div>

    <div class="col-span-2">
        <label>Alamat</label>
        <input type="text" name="location" value="{{ old('location') }}" class="w-full border p-2 rounded">
    </div>

</div>

{{-- ================= RUMAH ================= --}}
<div id="rumah-fields" class="mt-6 category-section {{ old('category_id') == 1 ? '' : 'hidden' }}" data-category="1">
<h3 class="font-bold mb-3">Detail Rumah</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="text" name="house_type" placeholder="Tipe Rumah" class="border p-2 rounded">
    <input type="number" name="land_area" placeholder="Luas Tanah" class="border p-2 rounded">
    <input type="number" name="building_area" placeholder="Luas Bangunan" class="border p-2 rounded">
    <input type="number" name="bedrooms" placeholder="Kamar Tidur" class="border p-2 rounded">
    <input type="number" name="bathrooms" placeholder="Kamar Mandi" class="border p-2 rounded">
    <input type="number" name="floors" placeholder="Jumlah Lantai" class="border p-2 rounded">
    <input type="text" name="certificate" placeholder="Sertifikat" class="border p-2 rounded">
</div>

<div class="mt-3">
    <label>Jenis Rumah</label>
    <select name="is_kpr" class="w-full border p-2 rounded">
        <option value="1" {{ old('is_kpr') == '1' ? 'selected' : '' }}>KPR</option>
        <option value="0" {{ old('is_kpr') == '0' ? 'selected' : '' }}>Non KPR</option>
    </select>
</div>

<div class="mt-3">
    <label>Fasilitas</label>
    <textarea name="facilities" class="w-full border p-2 rounded" placeholder="Isi manual..."></textarea>
</div>
</div>

{{-- ================= TANAH ================= --}}
<div id="tanah-fields" class="mt-6 category-section {{ old('category_id') == 2 ? '' : 'hidden' }}" data-category="2">
<h3 class="font-bold mb-3">Detail Tanah</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="number" name="land_area" placeholder="Luas Tanah" class="border p-2 rounded">
    <input type="text" name="certificate" placeholder="Sertifikat" class="border p-2 rounded">
</div>
</div>

{{-- ================= MOBIL ================= --}}
<div id="mobil-fields" class="mt-6 category-section {{ old('category_id') == 3 ? '' : 'hidden' }}" data-category="3">
    <h3 class="font-bold mb-3">Detail Mobil</h3>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

        <!-- BRAND -->
        <input type="text" name="brand"
            value="{{ old('brand') }}"
            class="w-full border p-2 rounded"
            placeholder="Merk Mobil">

        <!-- MODEL -->
        <input type="text" name="model"
            value="{{ old('model') }}"
            class="border p-2 rounded"
            placeholder="Model">

        <!-- YEAR -->
        <input type="number" name="year"
            value="{{ old('year') }}"
            min="1901"
            max="2155"
            class="border p-2 rounded"
            placeholder="Tahun">

        <!-- ENGINE -->
        <input type="number" name="engine"
            value="{{ old('engine') }}"
            class="border p-2 rounded"
            placeholder="Mesin (cc)">

        <!-- TRANSMISSION -->
        <select name="transmission" class="border p-2 rounded">
            <option value="">Pilih Transmisi</option>
            <option value="manual" {{ old('transmission')=='manual'?'selected':'' }}>Manual</option>
            <option value="matic" {{ old('transmission')=='matic'?'selected':'' }}>Matic</option>
        </select>

        <!-- FUEL -->
        <select name="fuel_type" class="border p-2 rounded">
            <option value="">Pilih Bahan Bakar</option>
            <option value="bensin" {{ old('fuel_type')=='bensin'?'selected':'' }}>Bensin</option>
            <option value="diesel" {{ old('fuel_type')=='diesel'?'selected':'' }}>Solar</option>
        </select>

        <!-- WARNA -->
        <input type="text" name="color"
            value="{{ old('color') }}"
            class="border p-2 rounded"
            placeholder="Warna Kendaraan">

        <input type="number" name="kilometer"
            value="{{ old('kilometer') }}"
            class="border p-2 rounded"
            placeholder="Kilometer (km)">
    </div>
</div>

{{-- ================= MOTOR ================= --}}
<div id="motor-fields" class="mt-6 category-section {{ old('category_id') == 4 ? '' : 'hidden' }}" data-category="4">
<h3 class="font-bold mb-3">Detail Motor</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="text" name="brand" placeholder="Merk" class="border p-2 rounded">
    <input type="text" name="model" placeholder="Model" class="border p-2 rounded">
    <input type="number" name="year" min="1901" max="2155" placeholder="Tahun" class="border p-2 rounded">
    <input type="number" name="engine" placeholder="Mesin (cc)" class="border p-2 rounded">

    <select name="transmission" class="border p-2 rounded">
        <option value="manual">Manual</option>
        <option value="matic">Matic</option>
    </select>
</div>
</div>

{{-- DESKRIPSI --}}
<div class="mt-6">
    <label>Deskripsi</label>
    <textarea name="description" class="w-full border p-2 rounded"></textarea>
</div>

{{-- GAMBAR --}}
<div class="mt-6">
    <label>Upload Gambar</label>

    <div id="image-wrapper">
        <input type="file" name="images[]" class="w-full border p-2 rounded">
    </div>

    <button type="button" onclick="addImage()" class="mt-2 bg-green-500 text-white px-3 py-1 rounded">
        + Tambah Gambar
    </button>
</div>

<div class="mt-6">
    <button class="bg-blue-600 text-white px-6 py-2 rounded">
        Simpan
    </button>
</div>

</form>
</div>
</div>

<script>
function formatRupiah(value) {
    let number = String(value).replace(/\D/g, '');

    if (!number) {
        return '';
    }

    return 'Rp ' + number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function bindRupiahInputs() {
    document.querySelectorAll('[data-rupiah-input]').forEach(function(input) {
        const target = document.getElementById(input.dataset.target);
        if (!target) return;

        input.addEventListener('input', function() {
            const raw = this.value.replace(/\D/g, '');
            target.value = raw;
            this.value = formatRupiah(raw);
        });
    });
}

function toggleCategoryFields(value) {
    ['rumah', 'tanah', 'mobil', 'motor'].forEach(function(section) {
        let el = document.getElementById(section + '-fields');
        if (!el) return;

        let active = String(el.dataset.category) === String(value);
        el.classList.toggle('hidden', !active);

        el.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.disabled = !active;
        });
    });
}

document.getElementById('category').addEventListener('change', function(){
    toggleCategoryFields(this.value);
});

document.addEventListener('DOMContentLoaded', function() {
    toggleCategoryFields(document.getElementById('category').value);
    bindRupiahInputs();
});

function addImage(){
    let wrapper = document.getElementById('image-wrapper');

    let input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.className = 'w-full border p-2 rounded mt-2';

    wrapper.appendChild(input);
}
</script>

@endsection
