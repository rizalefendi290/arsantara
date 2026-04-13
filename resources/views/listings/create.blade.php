@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

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
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Harga</label>
        <input type="number" name="price" value="{{ old('price') }}" class="w-full border p-2 rounded">
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
<div id="rumah-fields" class="hidden mt-6">
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
    <label>Fasilitas</label>
    <textarea name="facilities" class="w-full border p-2 rounded" placeholder="Isi manual..."></textarea>
</div>
</div>

{{-- ================= TANAH ================= --}}
<div id="tanah-fields" class="hidden mt-6">
<h3 class="font-bold mb-3">Detail Tanah</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="number" name="land_area" placeholder="Luas Tanah" class="border p-2 rounded">
    <input type="text" name="certificate" placeholder="Sertifikat" class="border p-2 rounded">
</div>
</div>

{{-- ================= MOBIL ================= --}}
<div id="mobil-fields" class="hidden mt-6">
<h3 class="font-bold mb-3">Detail Mobil</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="text" name="brand" placeholder="Merk" class="border p-2 rounded">
    <input type="text" name="model" placeholder="Model" class="border p-2 rounded">
    <input type="number" name="year" placeholder="Tahun" class="border p-2 rounded">
    <input type="number" name="engine" placeholder="Mesin (cc)" class="border p-2 rounded">

    <select name="transmission" class="border p-2 rounded">
        <option value="manual">Manual</option>
        <option value="matic">Matic</option>
    </select>
</div>
</div>

{{-- ================= MOTOR ================= --}}
<div id="motor-fields" class="hidden mt-6">
<h3 class="font-bold mb-3">Detail Motor</h3>

<div class="grid grid-cols-3 gap-4">
    <input type="text" name="brand" placeholder="Merk" class="border p-2 rounded">
    <input type="text" name="model" placeholder="Model" class="border p-2 rounded">
    <input type="number" name="year" placeholder="Tahun" class="border p-2 rounded">
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
document.getElementById('category').addEventListener('change', function(){

    let val = this.value;

    document.getElementById('rumah-fields').classList.add('hidden');
    document.getElementById('tanah-fields').classList.add('hidden');
    document.getElementById('mobil-fields').classList.add('hidden');
    document.getElementById('motor-fields').classList.add('hidden');

    if(val == 1) document.getElementById('rumah-fields').classList.remove('hidden');
    if(val == 2) document.getElementById('tanah-fields').classList.remove('hidden');
    if(val == 3) document.getElementById('mobil-fields').classList.remove('hidden');
    if(val == 4) document.getElementById('motor-fields').classList.remove('hidden');

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