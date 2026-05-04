@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Pencarian Arsantara',
            'title' => 'Hasil untuk "'.($keyword !== '' ? $keyword : 'semua data').'"',
            'text' => 'Cari listing properti, kendaraan, dan berita terbaru dari satu halaman.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Properti Arsantara',
            'title' => 'Temukan rumah dan tanah sesuai kebutuhan',
            'text' => 'Gunakan filter kategori, harga, dan kata kunci untuk mempersempit hasil pencarian.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Autoshow Arsantara',
            'title' => 'Cari mobil dan motor dari satu halaman',
            'text' => 'Bandingkan listing kendaraan aktif dan lanjutkan komunikasi lewat WhatsApp.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[430px]" inner-height="min-h-[430px]" content-width="max-w-3xl" />

<div class="relative z-20 -mt-14 px-6">
    <form method="GET" action="{{ route('search') }}" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
            <input type="text" name="keyword" value="{{ $keyword }}"
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Cari judul, lokasi, merk, kategori, atau berita">

            <select name="category" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua kategori listing</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="min_price" value="{{ request('min_price') }}"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Harga minimum">

            <input type="number" name="max_price" value="{{ request('max_price') }}"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Harga maksimum">
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                Cari
            </button>
            <a href="{{ route('search') }}" class="rounded-xl bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-200">
                Reset
            </a>
        </div>
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <section class="mb-12">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase text-blue-600">Listing Produk</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $listings->total() }} hasil ditemukan</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($listings as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                        Tidak ada listing yang cocok.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $listings->links() }}
            </div>
        </section>

        <section>
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase text-blue-600">Berita</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $posts->total() }} hasil berita</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($posts as $post)
                    <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow hover:shadow-lg transition">
                        <div class="grid grid-cols-1 sm:grid-cols-3">
                            <a href="{{ route('post.show', $post->id) }}" class="block sm:col-span-1 bg-gray-100">
                                <img src="{{ $post->images->count() ? asset('storage/'.$post->images->first()->image) : asset('images/logo.png') }}"
                                    class="h-48 w-full object-cover sm:h-full"
                                    alt="{{ $post->title }}">
                            </a>

                            <div class="sm:col-span-2 p-5">
                                <p class="text-xs font-semibold uppercase text-blue-600">{{ $post->created_at->format('d M Y') }}</p>
                                <a href="{{ route('post.show', $post->id) }}" class="mt-2 block font-bold text-gray-900 hover:text-blue-600 line-clamp-2">
                                    {{ $post->title }}
                                </a>
                                <p class="mt-2 text-sm leading-relaxed text-gray-600 line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                        Tidak ada berita yang cocok.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </section>
    </div>
</main>

<script>
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
