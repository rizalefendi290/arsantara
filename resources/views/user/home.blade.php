@extends('layouts.app')

@section('content')
@php
$homeHeroSlides = [
[
'image' => asset('images/hero.png'),
'label' => 'Arsantara Marketplace',
'title' => 'Temukan properti impian Anda',
'text' => 'Rumah, tanah, mobil hingga kebutuhan lainnya dalam satu platform terpercaya.',
],
[
'image' => asset('images/thumbnail_properti.png'),
'label' => 'Properti Pilihan',
'title' => 'Rumah dan tanah dalam satu tempat',
'text' => 'Cari properti aktif, lokasi strategis, dan pilihan harga yang mudah dibandingkan.',
],
[
'image' => asset('images/thumbnail_kendaraan.png'),
'label' => 'Autoshow Arsantara',
'title' => 'Mobil dan motor siap Anda bandingkan',
'text' => 'Jelajahi kendaraan berdasarkan harga, kondisi, kategori, dan kebutuhan harian Anda.',
],
[
'image' => asset('images/thumbnail_pinjam_dana.png'),
'label' => 'Pinjaman Dana',
'title' => 'Ajukan dana dengan jaminan BPKB',
'text' => 'Konsultasikan kebutuhan dana dan lanjutkan proses awal bersama admin Arsantara.',
],
];
$propertySearchCategories = $categories
    ->whereIn('id', [1, 2, 5, 6, 7, 8])
    ->sortBy(fn ($category) => array_search((int) $category->id, [1, 2, 5, 6, 7, 8], true));
$vehicleSearchCategories = $categories
    ->whereIn('id', [3, 4, 9])
    ->sortBy(fn ($category) => array_search((int) $category->id, [3, 4, 9], true));
@endphp

<x-hero-carousel :slides="$homeHeroSlides" height="min-h-screen" inner-height="min-h-[calc(100vh-6rem)]"
    content-width="max-w-2xl" content-class="md:-translate-x-[17rem] lg:translate-x-0" />

<!-- SEARCH BOX FLOAT -->
<div class="relative z-20 flex justify-center px-6 -mt-20">

    <form data-aos="zoom-in" method="GET" action="{{ route('search') }}"
        class="w-full max-w-5xl p-6 border shadow-2xl bg-white/95 backdrop-blur-xl border-white/30 rounded-3xl">

        <!-- TAB -->
        <div class="flex justify-center gap-3 mb-6">

            <button type="button" data-search-tab="property" onclick="setSearchType('property')" class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-full shadow-md tab-btn hover:bg-blue-700">
                Properti
            </button>

            <button type="button" data-search-tab="vehicle" onclick="setSearchType('vehicle')" class="px-5 py-2 text-sm font-medium text-blue-700 transition bg-blue-100 rounded-full tab-btn hover:bg-blue-200">
                Kendaraan
            </button>

        </div>

        <!-- INPUT -->
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">

            <input type="text" name="keyword" placeholder="Cari properti atau kendaraan..."
                class="px-4 py-3 border border-gray-200 outline-none md:col-span-2 rounded-xl focus:ring-2 focus:ring-blue-500">

            <x-price-filter-input name="min_price" placeholder="Harga minimum" />

            <x-price-filter-input name="max_price" placeholder="Harga maksimum" />

        </div>

        <div class="mt-4">
            <div data-filter-panel="property" class="grid grid-cols-1 gap-3 category-filter md:grid-cols-2">
                <div class="relative md:col-span-1">
                    <button type="button" data-property-dropdown-toggle
                        class="flex items-center justify-between w-full px-4 py-3 text-left text-gray-700 transition bg-white border border-gray-200 outline-none rounded-xl hover:border-blue-300 focus:ring-2 focus:ring-blue-500">
                        <span data-property-category-label>Semua Properti</span>
                        <svg class="w-4 h-4 text-blue-600 transition" data-property-dropdown-icon fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div data-property-dropdown class="absolute left-0 right-0 top-[calc(100%+0.5rem)] z-30 hidden rounded-2xl border border-gray-100 bg-white p-3 shadow-2xl md:w-[620px]">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <button type="button" data-property-option="" onclick="selectPropertyCategory('', 'Semua Properti')"
                                class="flex flex-col justify-between p-3 text-sm font-semibold text-left text-blue-700 transition border border-blue-600 property-option min-h-20 rounded-xl bg-blue-50 hover:border-blue-600">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-blue-700 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 10.5 12 4l8 6.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6 10v9h12v-9" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 19v-5h6v5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span>Semua Properti</span>
                            </button>

                            @foreach($propertySearchCategories as $category)
                                <button type="button" data-property-option="{{ $category->id }}" onclick="selectPropertyCategory('{{ $category->id }}', '{{ $category->name }}')"
                                    class="flex flex-col justify-between p-3 text-sm font-semibold text-left text-gray-700 transition bg-white border border-gray-200 property-option min-h-20 rounded-xl hover:border-blue-500 hover:text-blue-700">
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-blue-700 rounded-lg bg-blue-50">
                                        @if($category->id == 1)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 11 12 4l9 7" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 10v10h14V10" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 20v-6h6v6" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @elseif($category->id == 2)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 6h16M4 18h16M7 6v12M17 6v12" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="m7 12 5-3 5 3-5 3-5-3Z" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @elseif($category->id == 5)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 20h16V8L12 4 4 8v12Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 12h2M14 12h2M8 16h2M14 16h2" stroke-linecap="round" />
                                            </svg>
                                        @elseif($category->id == 6)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M5 20V4h14v16" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 8h2M13 8h2M9 12h2M13 12h2M9 16h2M13 16h2" stroke-linecap="round" />
                                            </svg>
                                        @elseif($category->id == 7)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 20h18V9l-9-5-9 5v11Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7 20v-7h10v7M7 13h10" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M5 8h14v12H5V8Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8 8V5h8v3M8 12h8M8 16h5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                    </span>
                                    <span>{{ $category->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <input type="text" name="location" placeholder="Lokasi properti"
                    class="px-4 py-3 border border-gray-200 outline-none rounded-xl focus:ring-2 focus:ring-blue-500">
            </div>

            <div data-filter-panel="vehicle" class="grid hidden grid-cols-1 gap-3 category-filter md:grid-cols-2 lg:grid-cols-5">
                <div class="relative md:col-span-2">
                    <button type="button" data-vehicle-dropdown-toggle disabled
                        class="flex items-center justify-between w-full px-4 py-3 text-left text-gray-700 transition bg-white border border-gray-200 outline-none rounded-xl hover:border-blue-300 focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-50">
                        <span data-vehicle-category-label>Semua Kendaraan</span>
                        <svg class="w-4 h-4 text-blue-600 transition" data-vehicle-dropdown-icon fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div data-vehicle-dropdown class="absolute left-0 right-0 top-[calc(100%+0.5rem)] z-30 hidden rounded-2xl border border-gray-100 bg-white p-3 shadow-2xl md:w-[620px]">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <button type="button" data-vehicle-option="" onclick="selectVehicleCategory('', 'Semua Kendaraan')"
                                class="flex flex-col justify-between p-3 text-sm font-semibold text-left text-blue-700 transition border border-blue-600 vehicle-option min-h-20 rounded-xl bg-blue-50 hover:border-blue-600">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-blue-700 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M3 13h2l2-4h10l2 4h2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5 13v5h14v-5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7 18h.01M17 18h.01" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span>Semua Kendaraan</span>
                            </button>

                            @foreach($vehicleSearchCategories as $category)
                                <button type="button" data-vehicle-option="{{ $category->id }}" onclick="selectVehicleCategory('{{ $category->id }}', @js($category->name))"
                                    class="flex flex-col justify-between p-3 text-sm font-semibold text-left text-gray-700 transition bg-white border border-gray-200 vehicle-option min-h-20 rounded-xl hover:border-blue-500 hover:text-blue-700">
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-blue-700 rounded-lg bg-blue-50">
                                        @if($category->id == 3)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 13h2l2-4h8l2 4h2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 13v5h14v-5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7 18h.01M17 18h.01" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @elseif($category->id == 4)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M5 17a3 3 0 1 0 6 0 3 3 0 0 0-6 0ZM16 17a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M11 17h5l-2-6h-3l-3 3M13 8h3" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 8h11v10H3V8Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14 12h4l3 3v3h-7v-6Z" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6 18h.01M17 18h.01" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                    </span>
                                    <span>{{ $category->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <input type="text" name="brand" placeholder="Merk kendaraan" disabled
                    class="px-4 py-3 border border-gray-200 outline-none rounded-xl focus:ring-2 focus:ring-blue-500">

                <select name="transmission" disabled class="px-4 py-3 border border-gray-200 outline-none rounded-xl focus:ring-2 focus:ring-blue-500">
                    <option value="">Transmisi</option>
                    <option value="manual">Manual</option>
                    <option value="matic">Matic</option>
                </select>

                <select name="condition" disabled class="px-4 py-3 border border-gray-200 outline-none rounded-xl focus:ring-2 focus:ring-blue-500">
                    <option value="">Kondisi</option>
                    <option value="baru">Baru</option>
                    <option value="bekas">Bekas</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="px-6 py-3 font-semibold text-white transition bg-blue-600 shadow-md rounded-xl hover:bg-blue-700">
                Cari
            </button>
        </div>

        <input type="hidden" name="product_type" id="product_type" value="property">
        <input type="hidden" name="category" id="category" value="">

    </form>
</div>

<div class="relative py-20 mt-10 overflow-hidden bg-gradient-to-b from-blue-50 via-white to-white">

    <!-- WAVE ATAS (LEBIH HALUS) -->
    <div class="absolute top-0 left-0 z-0 w-full overflow-hidden leading-none">
        <svg class="relative block w-full h-[140px]" viewBox="0 0 1440 320">
            <path fill="#e0f2fe" d="M0,160C240,80,480,80,720,160C960,240,1200,240,1440,160L1440,0L0,0Z">
            </path>
        </svg>
    </div>

    <!-- GRADIENT BULAT HALUS -->
    <div class="absolute top-20 left-[-100px] w-[400px] h-[400px] bg-blue-200 opacity-30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-[-120px] w-[450px] h-[450px] bg-blue-300 opacity-20 rounded-full blur-3xl">
    </div>

    <!-- DOT PATTERN (LEBIH HALUS) -->
    <div class="absolute grid grid-cols-6 gap-2 top-24 right-16 opacity-10">
        @for ($i = 0; $i < 24; $i++) <div class="w-2 h-2 bg-blue-500 rounded-full">
    </div>
    @endfor
</div>

<!-- CIRCLE LINE -->
<div class="absolute w-48 h-48 border border-blue-200 rounded-full bottom-16 left-16 opacity-20"></div>

<!-- CONTENT -->
<div data-aos="fade-up"
    class="relative z-10 grid max-w-6xl grid-cols-1 gap-6 px-6 mx-auto -mt-24 md:grid-cols-2 lg:grid-cols-3">

    <!-- CARD 1 -->
    <a href="{{ route('properti') }}"
        class="group relative block rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px] bg-white focus:outline-none focus:ring-4 focus:ring-blue-300"
        aria-label="Lihat properti">

        <img src="{{ asset('images/11.png') }}"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
            alt="Temukan properti impian Anda">
        <div class="absolute inset-x-0 top-0 h-[58%] bg-gradient-to-b from-white via-white/95 to-white/0"></div>

        <div class="relative z-10 pt-10 mt-4 px-7">
            <h2 class="text-[30px] font-bold leading-tight text-slate-950">
                Temukan
                Properti <span class="text-blue-600">Impian Anda</span>
            </h2>

            <p class="mt-4 max-w-[270px] text-sm font-medium leading-6 text-slate-700">
                Pilihan rumah, apartemen, dan lokasi strategis dengan harga terbaik.
            </p>

            <span
                class="inline-flex items-center gap-3 px-5 py-3 text-sm font-semibold text-white transition bg-blue-600 mt-7 rounded-xl group-hover:bg-blue-700">
                Lihat Properti <span aria-hidden="true">&rarr;</span>
            </span>
        </div>
    </a>


    <!-- CARD 2 -->
    <a href="{{ route('autoshow') }}"
        class="group relative block rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px] bg-white focus:outline-none focus:ring-4 focus:ring-emerald-300"
        aria-label="Lihat mobil bekas">

        <img src="{{ asset('images/22.png') }}"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
            alt="Mobil bekas berkualitas">
        <div class="absolute inset-x-0 top-0 h-[58%] bg-gradient-to-b from-white via-white/95 to-white/0"></div>

        <div class="relative z-10 pt-10 mt-3 px-7">
            <h2 class="text-[30px] font-bold leading-tight text-slate-950">
                Mobil Bekas <br>
                <span class="text-emerald-600">Berkualitas</span>
            </h2>

            <p class="mt-4 max-w-[270px] text-sm font-medium leading-6 text-slate-700">
                Unit terpilih, kondisi prima, dan <br>
                harga bersaing dengan garansi.
            </p>

            <span
                class="inline-flex items-center gap-3 px-5 py-3 text-sm font-semibold text-white transition mt-7 rounded-xl bg-emerald-600 group-hover:bg-emerald-700">
                Lihat Mobil <span aria-hidden="true">&rarr;</span>
            </span>
        </div>
    </a>


    <!-- CARD 3 -->
    <a href="{{ route('pinjaman.index') }}"
        class="group relative block rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px] bg-white focus:outline-none focus:ring-4 focus:ring-amber-300"
        aria-label="Ajukan pinjaman dana">

        <img src="{{ asset('images/33.png') }}"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
            alt="Pinjam dana jaminan BPKB motor dan mobil">
        <div class="absolute inset-x-0 top-0 h-[58%] bg-gradient-to-b from-amber-50 via-amber-50/95 to-amber-50/0"></div>

        <div class="relative z-10 pt-10 mt-6 px-7">
            <h2 class="text-[30px] font-bold leading-tight text-slate-950">
                Pinjam Dana <br>
                Jaminan BPKB <br>
                <span class="text-amber-500">Motor & Mobil</span>
            </h2>

            <p class="mt-4 max-w-[270px] text-sm font-medium leading-3 text-slate-700">
                Cair cepat, bunga kompetitif <br>
                dan proses mudah & aman.
            </p>

            <span
                class="inline-flex items-center gap-3 px-5 py-3 mt-6 text-sm font-semibold text-white transition rounded-xl bg-amber-400 group-hover:bg-amber-500">
                Ajukan Sekarang <span aria-hidden="true">&rarr;</span>
            </span>
        </div>
    </a>

</div>
</div>

<div data-aos="fade-up" class="container p-6 mx-auto -mt-20">

    <!-- CAROUSEL -->
    @if($carousels->count())
    <div data-aos="fade-up" id="default-carousel"
        class="relative w-full max-w-6xl mx-auto my-8 overflow-hidden rounded-2xl" data-carousel="slide">
        <!-- WRAPPER -->
        <div class="relative h-[450px] w-full overflow-hidden rounded-2xl">
            @foreach($carousels as $index => $item)
            <div class="{{ $index == 0 ? '' : 'hidden' }} absolute inset-0 h-full w-full overflow-hidden rounded-2xl duration-700 ease-in-out"
                data-carousel-item>
                @if($item->link_url)
                <a href="{{ $item->link_url }}" class="absolute inset-0 block overflow-hidden bg-black/20 rounded-2xl"
                    aria-label="{{ $item->title ?: 'Buka halaman carousel' }}">
                    <img src="{{ asset('storage/'.$item->image) }}"
                        class="absolute inset-0 block object-cover object-center w-full h-full rounded-2xl"
                        alt="{{ $item->title ?: 'Carousel' }}">
                </a>
                @else
                <img src="{{ asset('storage/'.$item->image) }}"
                    class="absolute inset-0 block object-contain object-center w-full h-full rounded-2xl"
                    alt="{{ $item->title ?: 'Carousel' }}">
                @endif
            </div>
            @endforeach
        </div>

        <!-- INDICATOR -->
        <div class="absolute z-30 flex space-x-2 -translate-x-1/2 bottom-3 left-1/2 sm:bottom-4 sm:space-x-3">
            @foreach($carousels as $index => $item)
            <button type="button" class="h-2.5 w-2.5 rounded-full bg-white/70 ring-1 ring-black/10 sm:h-3 sm:w-3"
                aria-current="{{ $index == 0 ? 'true' : 'false' }}" data-carousel-slide-to="{{ $index }}">
            </button>
            @endforeach
        </div>

        <!-- PREV BUTTON -->
        <button type="button"
            class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-2 cursor-pointer sm:px-4 group"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-8 h-8 text-sm text-white rounded-full bg-black/30 group-hover:bg-black/50 sm:h-10 sm:w-10 sm:text-base">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>

            </span>
        </button>

        <!-- NEXT BUTTON -->
        <button type="button"
            class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-2 cursor-pointer sm:px-4 group"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-8 h-8 text-sm text-white rounded-full bg-black/30 group-hover:bg-black/50 sm:h-10 sm:w-10 sm:text-base">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m9 5 7 7-7 7" />
                </svg>

            </span>
        </button>
    </div>
    @endif

    @if($recommendedListings->count())
    <section data-aos="fade-up" class="mb-14">
        <div class="flex flex-col gap-2 mb-6 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Produk Rekomendasi</h2>
            </div>

            <a href="{{ route('search') }}" class="font-semibold text-blue-600 hover:underline">
                Lihat Semua
            </a>
        </div>

        <div class="flex gap-4 pb-4 overflow-x-auto snap-x scroll-smooth no-scrollbar md:grid md:grid-cols-2 md:gap-6 md:overflow-visible md:pb-0 lg:grid-cols-4">
            @foreach($recommendedListings as $listing)
            <div data-aos="fade-up"
                class="w-[74vw] max-w-[280px] shrink-0 snap-start bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer md:w-auto md:max-w-none"
                onclick="window.location='{{ route('listing.show',$listing->id) }}'">

                <div class="relative">
                    <div class="relative overflow-hidden h-36 sm:h-40 md:h-48">
                        @forelse($listing->images as $index => $img)
                        <img src="{{ asset('storage/'.$img->image) }}"
                            class="card-slide absolute inset-0 w-full h-full object-cover transition duration-300 {{ $index == 0 ? '' : 'hidden' }}">
                        @empty
                        <img src="https://via.placeholder.com/300x200" class="object-cover w-full h-full">
                        @endforelse
                    </div>

                    <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] px-2 py-1 rounded sm:text-xs">
                        {{ $listing->category->name ?? 'Rekomendasi' }}
                    </span>

                    @if($listing->images->count() > 1)
                    <button onclick="event.stopPropagation(); prevSlide(this)"
                        class="absolute -translate-y-1/2 rounded-full left-2 top-1/2 bg-white/70 w-7 h-7 sm:h-8 sm:w-8">
                        <svg class="w-5 h-5 mx-auto text-gray-800 dark:text-white sm:h-6 sm:w-6" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </button>

                    <button onclick="event.stopPropagation(); nextSlide(this)"
                        class="absolute -translate-y-1/2 rounded-full right-2 top-1/2 bg-white/70 w-7 h-7 sm:h-8 sm:w-8">
                        <svg class="w-5 h-5 mx-auto text-gray-800 dark:text-white sm:h-6 sm:w-6" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </button>
                    @endif
                </div>

                <div class="p-3 sm:p-4">
                    <p class="mb-1 text-[10px] font-bold uppercase text-blue-600 sm:text-xs">
                        {{ $listing->product_code ?: $listing->buildProductCode() }}
                    </p>
                    <h3 class="text-sm font-semibold line-clamp-1 sm:text-base">{{ $listing->title }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-1 sm:text-sm">{{ $listing->location }}</p>
                    <div class="mt-1">
                        <x-listing-price :listing="$listing" />
                    </div>

                    @php
                    $phone = "62895347042844";
                    $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
                    @endphp

                    <a href="https://wa.me/{{ $phone }}?text={{ $message }}" onclick="event.stopPropagation()"
                        target="_blank" class="block mt-3 text-center bg-green-500 text-white py-1.5 rounded-lg text-sm sm:py-2 sm:text-base">
                        WhatsApp
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- MARKETPLACE -->
    <h1 data-aos="fade-up" class="mb-8 text-3xl font-bold text-gray-800">
        Marketplace
    </h1>


    @foreach($categories as $category)

    @php
    $routeUrl = route('search', ['category' => $category->id]);

    if(strtolower($category->name) == 'mobil'){
    $routeUrl = route('mobil.index');
    } elseif(strtolower($category->name) == 'motor'){
    $routeUrl = route('motor.index');
    } elseif(strtolower($category->name) == 'rumah'){
    $routeUrl = route('rumah.index');
    } elseif(strtolower($category->name) == 'tanah'){
    $routeUrl = route('tanah.index');
    }
    @endphp

    <div data-aos="fade-up" class="mb-10">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $category->name }}
            </h2>

            <div>
                <a href="{{ $routeUrl }}" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-full shadow-md hover:bg-blue-700">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- LISTING -->
        <div data-aos="fade-up" class="flex gap-4 pb-4 overflow-x-auto snap-x scroll-smooth no-scrollbar md:grid md:grid-cols-2 md:gap-6 md:overflow-visible md:pb-0 lg:grid-cols-4">

            @forelse($category->listings->take(4) as $listing)
            <div data-aos="fade-up"
                class="w-[74vw] max-w-[280px] shrink-0 snap-start bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer md:w-auto md:max-w-none"
                onclick="window.location='{{ route('listing.show',$listing->id) }}'">

                <!-- ================= CAROUSEL ================= -->
                <div class="relative">

                    @php
                    $images = $listing->images;
                    @endphp

                    <div class="relative overflow-hidden h-36 sm:h-40 md:h-48">

                        @forelse($images as $index => $img)
                        <img src="{{ asset('storage/'.$img->image) }}"
                            class="card-slide absolute inset-0 w-full h-full object-cover transition duration-300 {{ $index == 0 ? '' : 'hidden' }}">
                        @empty
                        <img src="https://via.placeholder.com/300x200" class="object-cover w-full h-full">
                        @endforelse

                    </div>

                    <!-- BUTTON -->
                    <button onclick="event.stopPropagation(); prevSlide(this)"
                        class="absolute -translate-y-1/2 rounded-full left-2 top-1/2 bg-white/70 w-7 h-7 sm:h-8 sm:w-8">
                        <svg class="w-5 h-5 mx-auto text-gray-800 dark:text-white sm:h-6 sm:w-6" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>

                    </button>

                    <button onclick="event.stopPropagation(); nextSlide(this)"
                        class="absolute -translate-y-1/2 rounded-full right-2 top-1/2 bg-white/70 w-7 h-7 sm:h-8 sm:w-8">
                        <svg class="w-5 h-5 mx-auto text-gray-800 dark:text-white sm:h-6 sm:w-6" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>

                    </button>

                </div>

                <!-- ================= CONTENT ================= -->
                <div class="p-3 sm:p-4">

                    <h3 class="text-sm font-semibold line-clamp-1 sm:text-base">
                        {{ $listing->title }}
                    </h3>

                    <p class="mt-1 text-[10px] font-bold uppercase text-blue-600 sm:text-xs">
                        {{ $listing->product_code ?: $listing->buildProductCode() }}
                    </p>

                    <p class="text-xs text-gray-500 line-clamp-1 sm:text-sm">
                        {{ $listing->location }}
                    </p>

                    <div class="mt-1">
                        <x-listing-price :listing="$listing" />
                    </div>

                    <!-- WA BUTTON -->
                    @php
                    $phone = "62895347042844";
                    $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
                    @endphp

                    <a href="https://wa.me/{{ $phone }}?text={{ $message }}" onclick="event.stopPropagation()"
                        target="_blank" class="block mt-3 text-center bg-green-500 text-white py-1.5 rounded-lg text-sm sm:py-2 sm:text-base">
                        WhatsApp
                    </a>

                </div>

            </div>
            @empty
            <p class="text-gray-400">Belum ada data</p>
            @endforelse

        </div>

    </div>

    @endforeach

    <section data-aos="fade-up" class="grid gap-8 mt-24 mb-24 lg:grid-cols-2">
        <a href="{{ route('ads.guide') }}"
            class="group relative block min-h-[300px] overflow-hidden rounded-xl bg-gray-900 text-left shadow transition hover:shadow-xl">
            <img src="{{ asset('images/thumbnail_properti.png') }}" alt="Daftar sebagai agen"
                class="absolute inset-0 object-cover w-full h-full transition duration-700 group-hover:scale-105">

            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/10"></div>

            <div class="relative flex min-h-[300px] items-center py-10">
                <div class="max-w-xl px-6 text-white md:px-10">
                    <p class="inline-flex px-4 py-1 mb-3 text-xs font-semibold uppercase border rounded-full border-white/40">
                        Peluang Mitra Arsantara
                    </p>

                    <h2 class="text-3xl font-extrabold leading-tight md:text-4xl">
                        Daftar Sebagai Agen
                    </h2>

                    <p class="max-w-md mt-3 text-sm text-white/85 md:text-base">
                        Jangkau lebih banyak calon pembeli dan pasarkan listing terbaik Anda bersama Arsantara.
                    </p>

                    <span class="inline-flex items-center px-5 py-3 mt-6 text-sm font-semibold text-white transition bg-blue-600 rounded-lg shadow-md group-hover:bg-blue-700">
                        Mulai Bergabung Sekarang
                    </span>
                </div>
            </div>
        </a>

        <a href="{{ route('careers.index') }}"
            class="group relative block min-h-[300px] overflow-hidden rounded-xl bg-gray-900 text-left shadow transition hover:shadow-xl">
            <img src="{{ asset('images/thumbnail_properti.png') }}" alt="Lowongan pekerjaan"
                class="absolute inset-0 object-cover w-full h-full transition duration-700 group-hover:scale-105">

            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/10"></div>

            <div class="relative flex min-h-[300px] items-center py-10">
                <div class="max-w-xl px-6 text-white md:px-10">
                    <p class="inline-flex px-4 py-1 mb-3 text-xs font-semibold uppercase border rounded-full border-white/40">
                        Peluang Karir
                    </p>

                    <h2 class="text-3xl font-extrabold leading-tight md:text-4xl">
                        Temukan Lowongan Pekerjaan
                    </h2>

                    <p class="max-w-md mt-3 text-sm text-white/85 md:text-base">
                        Jelajahi posisi terbaru dan bergabung bersama tim yang terus bertumbuh.
                    </p>

                    <span class="inline-flex items-center px-5 py-3 mt-6 text-sm font-semibold text-white transition bg-blue-600 rounded-lg shadow-md group-hover:bg-blue-700">
                        Lihat Lowongan Tersedia
                    </span>
                </div>
            </div>
        </a>
    </section>
    <section>
        <div class="mt-16" data-aos="fade-up">
            <h2 class="mb-6 text-2xl font-bold">Berita Terbaru</h2>

            <div data-aos="fade-up" class="flex gap-4 pb-4 overflow-x-auto snap-x scroll-smooth no-scrollbar md:grid md:grid-cols-2 md:gap-6 md:overflow-visible md:pb-0 lg:grid-cols-3">

                @foreach($posts as $post)
                <div data-aos="fade-up"
                    class="w-[78vw] max-w-[300px] shrink-0 snap-start bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer md:w-auto md:max-w-none"
                    onclick="window.location='{{ route('post.show',$post->id) }}'">

                    <img src="{{ $post->images->count()
                        ? asset('storage/'.$post->images->first()->image)
                        : 'https://via.placeholder.com/300x200' }}" class="object-cover w-full h-40 md:h-48">

                    <div class="p-3 sm:p-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 sm:text-base">
                            {{ $post->title }}
                        </h3>

                        <p class="mt-2 text-xs text-gray-500 line-clamp-2 sm:text-sm">
                            {{ Str::limit(strip_tags($post->content), 80) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            {{ $post->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
    </section>

    <section class="mt-20">
        <div data-aos="fade-up" class="flex items-center justify-between gap-4 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">
                Testimoni Mereka Tentang Arsantara
            </h2>
            <a href="{{ route('testimoni.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-full shadow-md shrink-0 hover:bg-blue-700 sm:px-5">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>

        <div class="relative" data-aos="fade-up">

            <!-- LEFT -->
            <button onclick="scrollTesti(-1)"
                class="absolute left-0 z-10 items-center justify-center hidden w-10 h-10 -translate-y-1/2 bg-white rounded-full shadow md:flex top-1/2">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m15 19-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- SLIDER -->
            <div id="testi-slider" class="flex gap-4 pb-4 overflow-x-auto snap-x scroll-smooth no-scrollbar md:gap-6">

                @foreach($testimonials as $item)

                @php
                $initial = strtoupper(substr($item->name,0,1));
                @endphp

                <div data-aos="fade-up"
                    class="w-[78vw] max-w-[300px] shrink-0 snap-start bg-white rounded-2xl p-5 shadow hover:shadow-lg transition relative md:w-[320px] md:max-w-[320px] md:p-6">

                    <!-- AVATAR BULAT -->
                    <img src="{{ $item->photo
                                ? asset('storage/'.$item->photo)
                                : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                        class="object-cover w-16 h-16 rounded-full md:h-20 md:w-20">

                    <!-- QUOTE ICON -->
                    <div class="absolute flex items-center justify-center w-8 h-8 bg-yellow-400 rounded top-4 right-4">
                        "
                    </div>

                    <!-- CONTENT -->
                    <div class="mt-5 md:mt-6">

                        <h3 class="font-bold text-gray-800 uppercase">
                            {{ $item->name }}
                        </h3>

                        <!-- RATING -->
                        <div class="flex gap-1 mb-3 text-yellow-400">
                            @for($i=1; $i<=5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $item->rating ? 'fill-current' : 'fill-none text-gray-300' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="m12 3.5 2.6 5.26 5.8.84-4.2 4.1.99 5.78L12 16.75l-5.19 2.73.99-5.78-4.2-4.1 5.8-.84L12 3.5Z" stroke-linejoin="round" />
                                </svg>
                            @endfor
                        </div>

                        <!-- MESSAGE -->
                        <p class="text-sm leading-relaxed text-gray-600 line-clamp-4">
                            {{ $item->message }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $item->created_at->translatedFormat('d M Y') }} •
                            {{ $item->created_at->diffForHumans() }}
                        </p>

                    </div>

                </div>

                @endforeach

            </div>

            <!-- RIGHT -->
            <button onclick="scrollTesti(1)"
                class="absolute right-0 z-10 items-center justify-center hidden w-10 h-10 -translate-y-1/2 bg-white rounded-full shadow md:flex top-1/2">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m9 5 7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

        </div>
    </section>

</div>

@endsection
<script>
let selectedPropertyCategory = '';
let selectedVehicleCategory = '';

function setSearchType(type) {
    const categoryInput = document.getElementById('category');
    const productTypeInput = document.getElementById('product_type');
    const activeClasses = ['bg-blue-600', 'text-white', 'shadow-md', 'hover:bg-blue-700'];
    const inactiveClasses = ['bg-blue-100', 'text-blue-700', 'hover:bg-blue-200'];

    if (productTypeInput) {
        productTypeInput.value = type === 'vehicle' ? 'vehicle' : 'property';
    }

    if (categoryInput) {
        categoryInput.value = type === 'vehicle' ? selectedVehicleCategory : selectedPropertyCategory;
    }

    document.querySelectorAll('[data-search-tab]').forEach((button) => {
        const isActive = button.dataset.searchTab === type;
        button.classList.remove(...activeClasses, ...inactiveClasses);
        button.classList.add(...(isActive ? activeClasses : inactiveClasses));
    });

    document.querySelectorAll('[data-filter-panel]').forEach((panel) => {
        const isActive = panel.dataset.filterPanel === type;
        panel.classList.toggle('hidden', !isActive);
        panel.querySelectorAll('input, select, textarea, button[data-vehicle-dropdown-toggle]').forEach((field) => {
            field.disabled = !isActive;
        });
    });
}

function toggleVehicleDropdown(forceOpen = null) {
    const dropdown = document.querySelector('[data-vehicle-dropdown]');
    const icon = document.querySelector('[data-vehicle-dropdown-icon]');
    if (!dropdown) return;

    const shouldOpen = forceOpen === null ? dropdown.classList.contains('hidden') : forceOpen;
    dropdown.classList.toggle('hidden', !shouldOpen);
    if (icon) {
        icon.classList.toggle('rotate-180', shouldOpen);
    }
}

function selectVehicleCategory(categoryId, label = 'Semua Kendaraan') {
    const categoryInput = document.getElementById('category');
    const labelEl = document.querySelector('[data-vehicle-category-label]');
    selectedVehicleCategory = String(categoryId);

    if (categoryInput) {
        categoryInput.value = selectedVehicleCategory;
    }

    if (labelEl) {
        labelEl.textContent = label;
    }

    document.querySelectorAll('[data-vehicle-option]').forEach((option) => {
        const isActive = option.dataset.vehicleOption === selectedVehicleCategory;
        option.classList.toggle('border-blue-600', isActive);
        option.classList.toggle('bg-blue-50', isActive);
        option.classList.toggle('text-blue-700', isActive);
        option.classList.toggle('border-gray-200', !isActive);
        option.classList.toggle('bg-white', !isActive);
        option.classList.toggle('text-gray-700', !isActive);
    });

    toggleVehicleDropdown(false);
}

function togglePropertyDropdown(forceOpen = null) {
    const dropdown = document.querySelector('[data-property-dropdown]');
    const icon = document.querySelector('[data-property-dropdown-icon]');
    if (!dropdown) return;

    const shouldOpen = forceOpen === null ? dropdown.classList.contains('hidden') : forceOpen;
    dropdown.classList.toggle('hidden', !shouldOpen);
    if (icon) {
        icon.classList.toggle('rotate-180', shouldOpen);
    }
}

function selectPropertyCategory(categoryId, label) {
    const categoryInput = document.getElementById('category');
    const labelEl = document.querySelector('[data-property-category-label]');
    selectedPropertyCategory = String(categoryId);

    if (categoryInput) {
        categoryInput.value = selectedPropertyCategory;
    }

    if (labelEl) {
        labelEl.textContent = label;
    }

    document.querySelectorAll('[data-property-option]').forEach((option) => {
        const isActive = option.dataset.propertyOption === selectedPropertyCategory;
        option.classList.toggle('border-blue-600', isActive);
        option.classList.toggle('bg-blue-50', isActive);
        option.classList.toggle('text-blue-700', isActive);
        option.classList.toggle('border-gray-200', !isActive);
        option.classList.toggle('bg-white', !isActive);
        option.classList.toggle('text-gray-700', !isActive);
    });

    togglePropertyDropdown(false);
}

document.addEventListener('DOMContentLoaded', () => {
    setSearchType('property');

    const dropdownToggle = document.querySelector('[data-property-dropdown-toggle]');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', () => togglePropertyDropdown());
    }

    const vehicleDropdownToggle = document.querySelector('[data-vehicle-dropdown-toggle]');
    if (vehicleDropdownToggle) {
        vehicleDropdownToggle.addEventListener('click', () => toggleVehicleDropdown());
    }

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-property-dropdown]') && !event.target.closest('[data-property-dropdown-toggle]')) {
            togglePropertyDropdown(false);
        }

        if (!event.target.closest('[data-vehicle-dropdown]') && !event.target.closest('[data-vehicle-dropdown-toggle]')) {
            toggleVehicleDropdown(false);
        }
    });
});

function scrollTesti(direction) {
    const container = document.getElementById('testi-slider');
    container.scrollBy({
        left: direction * 340,
        behavior: 'smooth'
    });
}

function nextSlide(btn) {
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');

    let activeIndex = 0;

    slides.forEach((img, i) => {
        if (!img.classList.contains('hidden')) {
            activeIndex = i;
        }
        img.classList.add('hidden');
    });

    let nextIndex = (activeIndex + 1) % slides.length;
    slides[nextIndex].classList.remove('hidden');
}

function prevSlide(btn) {
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');

    let activeIndex = 0;

    slides.forEach((img, i) => {
        if (!img.classList.contains('hidden')) {
            activeIndex = i;
        }
        img.classList.add('hidden');
    });

    let prevIndex = (activeIndex - 1 + slides.length) % slides.length;
    slides[prevIndex].classList.remove('hidden');
}
</script>
