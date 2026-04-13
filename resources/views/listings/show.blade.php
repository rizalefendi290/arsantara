@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

<div class="bg-white shadow rounded p-6">

<div class="grid grid-cols-2 gap-6">

    <!-- GAMBAR -->
    <div>
        <img id="main-image"
            src="{{ $listing->images->count() ? asset('storage/'.$listing->images->first()->image) : 'https://via.placeholder.com/800x400' }}"
            class="w-full h-96 object-cover rounded">

        <div class="grid grid-cols-5 gap-2 mt-3">
            @foreach($listing->images as $img)
            <img src="{{ asset('storage/'.$img->image) }}"
                class="h-20 w-full object-cover rounded cursor-pointer border hover:border-blue-500"
                onclick="changeImage(this)">
            @endforeach
        </div>
    </div>

    <!-- DETAIL -->
    <div>

        <h1 class="text-2xl font-bold">
            {{ $listing->title }}
        </h1>

        <p class="text-gray-500 mt-1">
            {{ $listing->location }}
        </p>

        <p class="text-2xl text-blue-600 font-bold my-3">
            Rp {{ number_format($listing->price) }}
        </p>

        <!-- ================= RUMAH ================= -->
        @if($listing->category_id == 1)
        <div class="grid grid-cols-2 gap-2 mt-4">
            <p>Tipe: {{ $listing->property->house_type ?? '-' }}</p>
            <p>Luas Tanah: {{ $listing->property->land_area ?? '-' }} m²</p>
            <p>Luas Bangunan: {{ $listing->property->building_area ?? '-' }} m²</p>
            <p>Kamar Tidur: {{ $listing->property->bedrooms ?? '-' }}</p>
            <p>Kamar Mandi: {{ $listing->property->bathrooms ?? '-' }}</p>
            <p>Lantai: {{ $listing->property->floors ?? '-' }}</p>
            <p>Sertifikat: {{ $listing->property->certificate ?? '-' }}</p>
        </div>

        <div class="mt-3">
            <h3 class="font-semibold">Fasilitas</h3>
            <p>{{ $listing->property->facilities ?? '-' }}</p>
        </div>
        @endif

        <!-- ================= TANAH ================= -->
        @if($listing->category_id == 2)
        <div class="grid grid-cols-2 gap-2 mt-4">
            <p>Luas Tanah: {{ $listing->property->land_area ?? '-' }} m²</p>
            <p>Sertifikat: {{ $listing->property->certificate ?? '-' }}</p>
        </div>
        @endif

        <!-- ================= MOBIL ================= -->
        @if($listing->category_id == 3)
        <div class="grid grid-cols-2 gap-2 mt-4">
            <p>Merk: {{ $listing->car->brand ?? '-' }}</p>
            <p>Model: {{ $listing->car->model ?? '-' }}</p>
            <p>Tahun: {{ $listing->car->year ?? '-' }}</p>
            <p>Mesin: {{ $listing->car->engine ?? '-' }} cc</p>
            <p>Transmisi: {{ $listing->car->transmission ?? '-' }}</p>
        </div>
        @endif

        <!-- ================= MOTOR ================= -->
        @if($listing->category_id == 4)
        <div class="grid grid-cols-2 gap-2 mt-4">
            <p>Merk: {{ $listing->motorcycle->brand ?? '-' }}</p>
            <p>Model: {{ $listing->motorcycle->model ?? '-' }}</p>
            <p>Tahun: {{ $listing->motorcycle->year ?? '-' }}</p>
            <p>Mesin: {{ $listing->motorcycle->engine ?? '-' }} cc</p>
            <p>Transmisi: {{ $listing->motorcycle->transmission ?? '-' }}</p>
        </div>
        @endif

        <!-- DESKRIPSI -->
        <div class="border-t pt-4 mt-4">
            <h2 class="font-semibold mb-2">Deskripsi</h2>
            <p class="text-gray-700">
                {{ $listing->description }}
            </p>
        </div>

        <!-- TOMBOL -->
        <div class="mt-6">
            <a href="{{ url()->previous() }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

    </div>

</div>
</div>
</div>

<script>
function changeImage(el){
    document.getElementById('main-image').src = el.src;
}
</script>

@endsection