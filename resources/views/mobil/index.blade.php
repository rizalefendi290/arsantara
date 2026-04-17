@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    <!-- ================= HEADER ================= -->
    <h1 class="text-2xl font-bold mb-6">Daftar Mobil</h1>

    <!-- ================= FILTER + SEARCH ================= -->
    <form method="GET" class="bg-white p-4 rounded-xl shadow mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- SEARCH -->
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari mobil..."
            class="border rounded-lg px-4 py-2 w-full">

        <!-- BRAND -->
        <select name="brand" class="border rounded-lg px-3 py-2">
            <option value="">Semua Merek</option>
            <option value="Toyota" {{ request('brand')=='Toyota'?'selected':'' }}>Toyota</option>
            <option value="Honda" {{ request('brand')=='Honda'?'selected':'' }}>Honda</option>
            <option value="Suzuki" {{ request('brand')=='Suzuki'?'selected':'' }}>Suzuki</option>
        </select>

        <!-- TRANSMISI -->
        <select name="transmission" class="border rounded-lg px-3 py-2">
            <option value="">Transmisi</option>
            <option value="Manual" {{ request('transmission')=='Manual'?'selected':'' }}>Manual</option>
            <option value="Matic" {{ request('transmission')=='Matic'?'selected':'' }}>Matic</option>
        </select>

        <!-- HARGA -->
        <select name="price" class="border rounded-lg px-3 py-2">
            <option value="">Range Harga</option>
            <option value="1" {{ request('price')=='1'?'selected':'' }}>< 100jt</option>
            <option value="2" {{ request('price')=='2'?'selected':'' }}>100jt - 300jt</option>
            <option value="3" {{ request('price')=='3'?'selected':'' }}>> 300jt</option>
        </select>

        <!-- SORT -->
        <select name="sort" class="border rounded-lg px-3 py-2">
            <option value="">Urutkan</option>
            <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Terbaru</option>
            <option value="low" {{ request('sort')=='low'?'selected':'' }}>Harga Termurah</option>
            <option value="high" {{ request('sort')=='high'?'selected':'' }}>Harga Tertinggi</option>
        </select>

        <!-- BUTTON -->
        <div class="md:col-span-5 flex gap-3">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Filter
            </button>

            <a href="{{ route('mobil.index') }}"
               class="bg-gray-200 px-6 py-2 rounded-lg">
               Reset
            </a>
        </div>

    </form>

    <!-- ================= GRID ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($listings as $listing)
        <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden relative">

            <!-- BADGE -->
            <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                {{ $listing->car->year ?? '-' }}
            </span>

            <!-- IMAGE -->
            <img 
                src="{{ $listing->images->count() 
                        ? asset('storage/'.$listing->images->first()->image) 
                        : 'https://via.placeholder.com/300x200' }}"
                class="w-full h-48 object-cover hover:scale-105 transition duration-300">

            <!-- CONTENT -->
            <div class="p-4">

                <h3 class="font-semibold text-gray-900 line-clamp-1">
                    {{ $listing->title }}
                </h3>

                <!-- DETAIL -->
                <p class="text-gray-500 text-sm">
                    {{ $listing->car->brand ?? '-' }}
                </p>

                <p class="text-gray-500 text-sm">
                    {{ $listing->location }}
                </p>

                <!-- INFO TAMBAHAN -->
                <div class="flex gap-2 mt-2 text-xs">
                    <span class="bg-gray-100 px-2 py-1 rounded">
                        {{ $listing->car->transmission ?? '-' }}
                    </span>
                    <span class="bg-gray-100 px-2 py-1 rounded">
                        {{ $listing->car->engine ?? '-' }} cc
                    </span>
                </div>

                <!-- PRICE -->
                <p class="text-blue-600 font-bold mt-2">
                    Rp {{ number_format($listing->price) }}
                </p>

                <!-- BUTTON -->
                <a href="{{ route('listing.show',$listing->id) }}"
                   class="block mt-3 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                   Detail
                </a>

            </div>

        </div>
        @empty
        <p class="text-gray-400 col-span-4 text-center">Belum ada data mobil</p>
        @endforelse

    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="mt-6">
        {{ $listings->withQueryString()->links() }}
    </div>

</div>

@endsection