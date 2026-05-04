@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Kirim Testimoni',
            'title' => 'Bagikan pengalaman Anda.',
            'text' => 'Ulasan Anda membantu pengguna lain lebih percaya diri saat memilih layanan Arsantara.',
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

<x-hero-carousel :slides="$heroSlides" height="min-h-[360px]" inner-height="min-h-[360px]" content-width="max-w-2xl" />

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-3xl px-6 py-12">
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                @foreach($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data"
            class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl space-y-5">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block font-semibold text-gray-800">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Nama Anda">
                </div>

                <div>
                    <label class="mb-1 block font-semibold text-gray-800">Pekerjaan (opsional)</label>
                    <input type="text" name="job" value="{{ old('job') }}"
                        class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Contoh: Pemilik rumah">
                </div>
            </div>

            <div>
                <label class="mb-1 block font-semibold text-gray-800">Foto (opsional)</label>
                <input type="file" name="photo"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-blue-700">
            </div>

            <div>
                <label class="mb-2 block font-semibold text-gray-800">Rating</label>
                <div class="flex gap-2 text-4xl text-gray-300 cursor-pointer" id="rating">
                    @for($i=1; $i<=5; $i++)
                        <span data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}">
            </div>

            <div>
                <label class="mb-1 block font-semibold text-gray-800">Komentar</label>
                <textarea name="message" rows="5"
                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Tulis pengalaman Anda...">{{ old('message') }}</textarea>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">
                    Kirim Testimoni
                </button>
                <a href="{{ route('testimoni.index') }}"
                    class="rounded-xl bg-gray-100 px-6 py-3 text-center font-semibold text-gray-700 hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</main>

<script>
const stars = document.querySelectorAll('#rating span');
const input = document.getElementById('ratingInput');

function paintStars(value) {
    stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < value);
        star.classList.toggle('text-gray-300', index >= value);
    });
}

stars.forEach((star) => {
    star.addEventListener('click', () => {
        input.value = star.dataset.value;
        paintStars(Number(input.value));
    });
});

paintStars(Number(input.value || 5));
</script>
@endsection
