@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="bg-green-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold mb-4">
            Arsantara Properti
        </h1>
        <p class="text-lg text-green-100">
            Temukan Rumah & Tanah terbaik dengan harga terbaik
        </p>
    </div>
</section>

<div class="container mx-auto p-6">

    <!-- ================= RUMAH KPR ================= -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Rumah Bisa KPR</h2>
            <a href="{{ route('rumah.index') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Tampilkan Semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($rumahKpr as $listing)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <img
                    src="{{ $listing->images->count()
                            ? asset('storage/'.$listing->images->first()->image)
                            : 'https://via.placeholder.com/300x200' }}"
                    class="w-full h-48 object-cover">

                <div class="p-4">
                    <h3 class="font-bold text-lg">
                        {{ $listing->title }}
                    </h3>

                    <p class="text-gray-500 text-sm">
                        {{ $listing->location }}
                    </p>

                    <p class="text-green-600 font-bold mt-2">
                        Rp {{ number_format($listing->price) }}
                    </p>

                    @if($listing->propertyDetail)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $listing->propertyDetail->land_area }} m² •
                        {{ $listing->propertyDetail->certificate }}
                    </p>
                    @endif

                    <a href="{{ route('listing.show',$listing->id) }}"
                       class="block mt-3 text-center bg-green-600 text-white py-2 rounded">
                        Detail
                    </a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 col-span-full">Belum ada rumah KPR</p>
            @endforelse
        </div>
    </div>

    <!-- ================= RUMAH NON KPR ================= -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Rumah Non KPR</h2>
            <a href="{{ route('rumah.index') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Tampilkan Semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($rumahNonKpr as $listing)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <img
                    src="{{ $listing->images->count()
                            ? asset('storage/'.$listing->images->first()->image)
                            : 'https://via.placeholder.com/300x200' }}"
                    class="w-full h-48 object-cover">

                <div class="p-4">
                    <h3 class="font-bold text-lg">
                        {{ $listing->title }}
                    </h3>

                    <p class="text-gray-500 text-sm">
                        {{ $listing->location }}
                    </p>

                    <p class="text-green-600 font-bold mt-2">
                        Rp {{ number_format($listing->price) }}
                    </p>

                    @if($listing->propertyDetail)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $listing->propertyDetail->land_area }} m² •
                        {{ $listing->propertyDetail->certificate }}
                    </p>
                    @endif

                    <a href="{{ route('listing.show',$listing->id) }}"
                       class="block mt-3 text-center bg-green-600 text-white py-2 rounded">
                        Detail
                    </a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 col-span-full">Belum ada rumah Non KPR</p>
            @endforelse
        </div>
    </div>

    <!-- ================= TANAH ================= -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Tanah</h2>
            <a href="{{ route('tanah.index') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Tampilkan Semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($tanah as $listing)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <img
                    src="{{ $listing->images->count()
                            ? asset('storage/'.$listing->images->first()->image)
                            : 'https://via.placeholder.com/300x200' }}"
                    class="w-full h-48 object-cover">

                <div class="p-4">
                    <h3 class="font-bold text-lg">
                        {{ $listing->title }}
                    </h3>

                    <p class="text-gray-500 text-sm">
                        {{ $listing->location }}
                    </p>

                    <p class="text-green-600 font-bold mt-2">
                        Rp {{ number_format($listing->price) }}
                    </p>

                    @if($listing->propertyDetail)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $listing->propertyDetail->land_area }} m² •
                        {{ $listing->propertyDetail->certificate }}
                    </p>
                    @endif

                    <a href="{{ route('listing.show',$listing->id) }}"
                       class="block mt-3 text-center bg-green-600 text-white py-2 rounded">
                        Detail
                    </a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 col-span-full">Belum ada tanah</p>
            @endforelse
        </div>
    </div>

</div>

@endsection