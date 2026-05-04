@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Kategori Motor',
            'title' => 'Daftar Motor',
            'text' => 'Temukan motor sesuai merk, transmisi, harga, dan kebutuhan harian Anda.',
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

<x-hero-carousel :slides="$heroSlides" height="min-h-[460px]" inner-height="min-h-[460px]" content-width="max-w-2xl" />

<div class="relative z-20 -mt-14 px-6">
    <form method="GET" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari motor..."
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <input type="text" name="brand" value="{{ request('brand') }}" placeholder="Merk"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <select name="transmission" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Transmisi</option>
                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="matic" {{ request('transmission') == 'matic' ? 'selected' : '' }}>Matic</option>
            </select>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Harga Min"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Harga Max"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <select name="sort" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Terbaru</option>
                <option value="low" {{ request('sort') == 'low' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="high" {{ request('sort') == 'high' ? 'selected' : '' }}>Harga Tertinggi</option>
            </select>
        </div>
        <div class="mt-4 flex gap-2">
            <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">Filter</button>
            <a href="{{ route('motor.index') }}" class="rounded-xl bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-200">Reset</a>
        </div>
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-6">
            <p class="text-sm font-semibold uppercase text-blue-600">Marketplace Kendaraan</p>
            <h2 class="text-3xl font-bold text-gray-800">Motor Tersedia</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($listings as $listing)
                <x-card-listing :listing="$listing" />
            @empty
                <p class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">Belum ada data motor</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $listings->withQueryString()->links() }}
        </div>
    </div>
</main>

<script>
function nextSlide(btn){ const slides = btn.closest('.relative').querySelectorAll('.card-slide'); let i = [...slides].findIndex(img => !img.classList.contains('hidden')); slides[i].classList.add('hidden'); slides[(i + 1) % slides.length].classList.remove('hidden'); }
function prevSlide(btn){ const slides = btn.closest('.relative').querySelectorAll('.card-slide'); let i = [...slides].findIndex(img => !img.classList.contains('hidden')); slides[i].classList.add('hidden'); slides[(i - 1 + slides.length) % slides.length].classList.remove('hidden'); }
</script>
@endsection
