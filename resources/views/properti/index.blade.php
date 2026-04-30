@extends('layouts.app')

@section('content')
<section data-aos="fade-up" class="relative min-h-[520px] flex items-center bg-cover bg-center"
    style="background-image:url('{{ asset('images/thumbnail_properti.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-blue-900/70 to-blue-700/30"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-28 text-white">
        <div class="max-w-2xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-blue-200">Arsantara Properti</p>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                Rumah dan tanah pilihan dalam satu tempat.
            </h1>
            <p class="mt-5 text-lg text-blue-100 leading-relaxed">
                Temukan properti aktif, lokasi strategis, pilihan KPR, dan tanah siap bangun dengan tampilan listing yang mudah dipindai.
            </p>
        </div>
    </div>
</section>

<div class="relative z-20 -mt-16 px-6">
    <form id="filterForm" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="Cari rumah, tanah, lokasi..."
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <select name="category" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Properti</option>
                <option value="1" {{ request('category') == 1 ? 'selected' : '' }}>Rumah</option>
                <option value="2" {{ request('category') == 2 ? 'selected' : '' }}>Tanah</option>
            </select>

            <input type="number" name="min_price" value="{{ request('min_price') }}"
                placeholder="Harga Min"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <input type="number" name="max_price" value="{{ request('max_price') }}"
                placeholder="Harga Max"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <select name="certificate" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Sertifikat</option>
                <option value="SHM" {{ request('certificate') == 'SHM' ? 'selected' : '' }}>SHM</option>
                <option value="SHGB" {{ request('certificate') == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                <option value="AJB" {{ request('certificate') == 'AJB' ? 'selected' : '' }}>AJB</option>
            </select>
        </div>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">Filter berjalan otomatis saat kolom diubah.</p>
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
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase text-blue-600">Hasil Filter</p>
                    <h2 class="text-3xl font-bold text-gray-800">Jelajahi Properti</h2>
                </div>
            </div>

            <div id="listing-container">
                @include('properti.partials.list', ['listings' => $listings])
            </div>
        </section>

        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Rumah Bisa KPR</h2>
                <a href="{{ route('rumah.index') }}" class="font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($rumahKpr as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah KPR</p>
                @endforelse
            </div>
        </section>

        <section data-aos="fade-up" class="mb-14">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Rumah Non KPR</h2>
                <a href="{{ route('rumah.index') }}" class="font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($rumahNonKpr as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah Non KPR</p>
                @endforelse
            </div>
        </section>

        <section data-aos="fade-up">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Tanah</h2>
                <a href="{{ route('tanah.index') }}" class="font-semibold text-blue-600 hover:underline">Tampilkan Semua</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($tanah as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <p class="col-span-full text-gray-400">Belum ada tanah</p>
                @endforelse
            </div>
        </section>
    </div>
</main>

<script>
let debounceTimer;
let controller;

function loadData(url = null) {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    const fetchUrl = url ? url + (url.includes('?') ? '&' : '?') + params : "{{ route('properti.filter') }}?" + params;

    if (controller) controller.abort();
    controller = new AbortController();

    document.getElementById('listing-container').innerHTML = '<div class="rounded-xl bg-white p-10 text-center text-gray-500 shadow">Loading...</div>';

    fetch(fetchUrl, { signal: controller.signal })
        .then(response => response.text())
        .then(html => {
            document.getElementById('listing-container').innerHTML = html;
        })
        .catch(error => {
            if (error.name !== 'AbortError') console.error(error);
        });
}

document.getElementById('filterForm').addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadData(), 450);
});

document.getElementById('filterForm').addEventListener('submit', function(event) {
    event.preventDefault();
    loadData();
});

document.addEventListener('click', function(event) {
    const link = event.target.closest('#pagination-links a');
    if (!link) return;
    event.preventDefault();
    loadData(link.href);
});

function resetFilter() {
    document.getElementById('filterForm').reset();
    loadData();
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
