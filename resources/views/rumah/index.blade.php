@extends('layouts.app')

@section('content')
<section class="relative min-h-[460px] flex items-center bg-cover bg-center"
    style="background-image:url('{{ asset('images/thumbnail_properti.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-blue-900/70 to-blue-700/30"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-24 text-white">
        <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-blue-200">Kategori Rumah</p>
        <h1 class="max-w-3xl text-4xl md:text-6xl font-extrabold leading-tight">Daftar Rumah</h1>
        <p class="mt-4 max-w-2xl text-lg text-blue-100">Temukan rumah KPR dan non KPR dengan filter harga, lokasi, kamar, dan urutan.</p>
    </div>
</section>

<div class="relative z-20 -mt-14 px-6">
    <form method="GET" class="mx-auto max-w-6xl rounded-2xl border border-white/40 bg-white p-5 shadow-2xl">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Harga Min"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Harga Max"
                class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <input type="text" name="location" value="{{ request('location') }}" placeholder="Lokasi"
                class="md:col-span-2 rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            <select name="bedrooms" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Kamar</option>
                <option value="1" {{ request('bedrooms') == 1 ? 'selected' : '' }}>1+</option>
                <option value="2" {{ request('bedrooms') == 2 ? 'selected' : '' }}>2+</option>
                <option value="3" {{ request('bedrooms') == 3 ? 'selected' : '' }}>3+</option>
            </select>
            <select name="sort" class="rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Terbaru</option>
                <option value="low" {{ request('sort') == 'low' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="high" {{ request('sort') == 'high' ? 'selected' : '' }}>Harga Termahal</option>
            </select>
        </div>
        <div class="mt-4 flex gap-2">
            <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('rumah.index') }}" class="rounded-xl bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-200">Reset</a>
        </div>
    </form>
</div>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <section class="mb-14">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">Rumah Bisa KPR</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($kpr as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah KPR</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="mb-6 text-2xl font-bold text-gray-800">Rumah Non KPR</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($nonKpr as $listing)
                    <x-card-listing :listing="$listing" />
                @empty
                    <p class="col-span-full text-gray-400">Belum ada rumah Non KPR</p>
                @endforelse
            </div>
        </section>
    </div>
</main>

<script>
function nextSlide(btn){ const slides = btn.closest('.relative').querySelectorAll('.card-slide'); let i = [...slides].findIndex(img => !img.classList.contains('hidden')); slides[i].classList.add('hidden'); slides[(i + 1) % slides.length].classList.remove('hidden'); }
function prevSlide(btn){ const slides = btn.closest('.relative').querySelectorAll('.card-slide'); let i = [...slides].findIndex(img => !img.classList.contains('hidden')); slides[i].classList.add('hidden'); slides[(i - 1 + slides.length) % slides.length].classList.remove('hidden'); }
</script>
@endsection
