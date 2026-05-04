@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero mobil.png'),
            'label' => 'Arsantara Autoshow',
            'title' => 'Mobil dan motor pilihan, siap Anda bandingkan.',
            'text' => 'Cari unit berdasarkan harga, kondisi, kategori, dan transmisi. Kartu listing memakai tampilan yang konsisten dengan halaman beranda.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Properti Arsantara',
            'title' => 'Pilihan rumah dan tanah yang mudah dibandingkan',
            'text' => 'Lihat lokasi, harga, sertifikat, dan detail utama dari satu tampilan yang rapi.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Autoshow Arsantara',
            'title' => 'Mobil dan motor pilihan dalam satu platform',
            'text' => 'Bandingkan kendaraan berdasarkan kategori, harga, kondisi, dan kebutuhan Anda.',
        ],
        [
            'image' => asset('images/thumbnail_pinjam_dana.png'),
            'label' => 'Layanan Dana',
            'title' => 'Konsultasi pinjam dana dengan jaminan BPKB',
            'text' => 'Hubungi admin untuk informasi awal dan pendampingan proses pengajuan.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[520px]" inner-height="min-h-[520px]" content-width="max-w-2xl" />

<div class="relative z-20 -mt-16 px-6">
    <form id="filterForm" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="Cari mobil, motor, merk..."
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <select name="category" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Kendaraan</option>
                <option value="3" {{ request('category') == 3 ? 'selected' : '' }}>Mobil</option>
                <option value="4" {{ request('category') == 4 ? 'selected' : '' }}>Motor</option>
            </select>

            <input type="number" name="min_price" value="{{ request('min_price') }}"
                placeholder="Harga Min"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <input type="number" name="max_price" value="{{ request('max_price') }}"
                placeholder="Harga Max"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

            <select name="condition" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Kondisi</option>
                <option value="baru" {{ request('condition') == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="bekas" {{ request('condition') == 'bekas' ? 'selected' : '' }}>Bekas</option>
            </select>

            <select name="transmission" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500 md:col-span-2">
                <option value="">Semua Transmisi</option>
                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="matic" {{ request('transmission') == 'matic' ? 'selected' : '' }}>Matic</option>
            </select>

            <button class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700 md:col-span-2">
                Terapkan Filter
            </button>

            <button type="button" onclick="resetFilter()"
                class="rounded-xl bg-gray-100 px-5 py-3 font-semibold text-gray-700 hover:bg-gray-200 md:col-span-2">
                Reset
            </button>
        </div>
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase text-blue-600">Marketplace Kendaraan</p>
                <h2 class="text-3xl font-bold text-gray-800">Jelajahi Autoshow</h2>
            </div>
            <p class="text-sm text-gray-500">Filter berjalan otomatis saat kolom diubah.</p>
        </div>

        <div id="listing-container">
            @include('autoshow.partials.list', ['listings' => $listings])
        </div>
    </div>
</main>

<script>
let debounceTimer;
let controller;

function loadData(url = null) {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    const fetchUrl = url ? url + (url.includes('?') ? '&' : '?') + params : "{{ route('autoshow.filter') }}?" + params;

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
