@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Arsantara Properti',
            'title' => 'Katalog properti pilihan dalam satu tempat.',
            'text' => 'Temukan rumah, tanah, ruko, perkantoran, gudang, dan kios aktif dengan tampilan listing yang mudah dipindai.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Properti Arsantara',
            'title' => 'Pilihan properti yang mudah dibandingkan',
            'text' => 'Lihat lokasi, harga, sertifikat, dan detail utama dari satu tampilan yang rapi.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Rumah Arsantara',
            'title' => 'Cari Rumah Yang Nyaman',
            'text' => 'Rumah Nyaman Di Hati Anda, Temukan Rumah Impian Anda dengan Mudah di Arsantara.',
        ],
        [
            'image' => asset('images/thumbnail_pinjam_dana.png'),
            'label' => 'Konsultasi Gratis',
            'title' => 'Konsultasikan Hunian dan Tanah Impian Anda',
            'text' => 'Hubungi admin untuk konsultasi gratis tentang rumah dan tanah impian Anda, termasuk opsi KPR dan proses pembelian.',
        ],
    ];
    $houseCategory = $propertyCategories->firstWhere('slug', \App\Models\Category::HOUSE_SLUG);
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[520px]" inner-height="min-h-[520px]" content-width="max-w-2xl" />

<div class="relative z-20 -mt-16 px-6">
    <form id="filterForm" method="GET" action="{{ route('search') }}" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="Cari rumah, tanah, ruko, gudang..."
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <select name="category" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Properti</option>
                @foreach($propertyCategories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <x-price-filter-input name="min_price" :value="request('min_price')" placeholder="Harga Min" />

            <x-price-filter-input name="max_price" :value="request('max_price')" placeholder="Harga Max" />

            <select name="certificate" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Sertifikat</option>
                <option value="SHM" {{ request('certificate') == 'SHM' ? 'selected' : '' }}>SHM</option>
                <option value="SHGB" {{ request('certificate') == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                <option value="AJB" {{ request('certificate') == 'AJB' ? 'selected' : '' }}>AJB</option>
            </select>
        </div>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">Cari properti berdasarkan kategori, harga, dan sertifikat.</p>
            <div class="flex gap-2">
                <button type="button" onclick="resetFilter()"
                    class="rounded-xl bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-200">
                    Reset
                </button>
                <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                    Terapkan Filter
                </button>
            </div>
        </div>
        <input type="hidden" name="product_type" value="property">
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        @if($rumahActive)
        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Rumah Bisa KPR</h2>
                <a href="{{ route('search', ['category' => $houseCategory?->id, 'is_kpr' => 1]) }}" class="shrink-0 font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="flex snap-x gap-4 overflow-x-auto pb-4 scroll-smooth no-scrollbar sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:pb-0 lg:grid-cols-4 lg:gap-6">
                @forelse($rumahKpr as $listing)
                    <div class="w-[74vw] max-w-[280px] shrink-0 snap-start sm:w-auto sm:max-w-none">
                        <x-card-listing :listing="$listing" />
                    </div>
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah KPR</p>
                @endforelse
            </div>
        </section>

        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Rumah Non KPR</h2>
                <a href="{{ route('search', ['category' => $houseCategory?->id, 'is_kpr' => 0]) }}" class="shrink-0 font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="flex snap-x gap-4 overflow-x-auto pb-4 scroll-smooth no-scrollbar sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:pb-0 lg:grid-cols-4 lg:gap-6">
                @forelse($rumahNonKpr as $listing)
                    <div class="w-[74vw] max-w-[280px] shrink-0 snap-start sm:w-auto sm:max-w-none">
                        <x-card-listing :listing="$listing" />
                    </div>
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah Non KPR</p>
                @endforelse
            </div>
        </section>
        @endif

        @if($tanahActive)
        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Tanah</h2>
                <a href="{{ route('tanah.index') }}" class="shrink-0 font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="flex snap-x gap-4 overflow-x-auto pb-4 scroll-smooth no-scrollbar sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:pb-0 lg:grid-cols-4 lg:gap-6">
                @forelse($tanah as $listing)
                    <div class="w-[74vw] max-w-[280px] shrink-0 snap-start sm:w-auto sm:max-w-none">
                        <x-card-listing :listing="$listing" />
                    </div>
                @empty
                    <p class="col-span-full text-gray-400">Belum ada tanah</p>
                @endforelse
            </div>
        </section>
        @endif

        @foreach($commercialSections as $section)
        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $section['category']->name }}</h2>
                <a href="{{ route('search', ['category' => $section['category']->id]) }}" class="shrink-0 font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="flex snap-x gap-4 overflow-x-auto pb-4 scroll-smooth no-scrollbar sm:grid sm:grid-cols-3 sm:gap-4 sm:overflow-visible sm:pb-0 lg:grid-cols-4 lg:gap-6">
                @forelse($section['listings'] as $listing)
                    <div class="w-[74vw] max-w-[280px] shrink-0 snap-start sm:w-auto sm:max-w-none">
                        <x-card-listing :listing="$listing" />
                    </div>
                @empty
                    <p class="col-span-full text-gray-400">Belum ada {{ strtolower($section['category']->name) }}</p>
                @endforelse
            </div>
        </section>
        @endforeach
    </div>
</main>

<script>
function resetFilter() {
    window.location.href = "{{ route('properti') }}";
}

function nextSlide(btn) {
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');
    let activeIndex = 0;

    slides.forEach((img, i) => {
        if (!img.classList.contains('hidden')) activeIndex = i;
        img.classList.add('hidden');
    });

    slides[(activeIndex + 1) % slides.length].classList.remove('hidden');
}

function prevSlide(btn) {
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');
    let activeIndex = 0;

    slides.forEach((img, i) => {
        if (!img.classList.contains('hidden')) activeIndex = i;
        img.classList.add('hidden');
    });

    slides[(activeIndex - 1 + slides.length) % slides.length].classList.remove('hidden');
}
</script>
@endsection
