@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Privasi Pengguna',
            'title' => 'Kebijakan Privasi',
            'text' => 'Penjelasan bagaimana Arsantara mengumpulkan, memakai, menyimpan, dan melindungi data pengguna.',
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

<x-hero-carousel :slides="$heroSlides" height="min-h-[420px]" inner-height="min-h-[420px]" content-width="max-w-2xl" />

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-12 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold uppercase text-blue-600">Navigasi Legal</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('terms') }}" class="block rounded-lg px-3 py-2 font-semibold text-gray-700 hover:bg-gray-50">Syarat & Ketentuan</a>
                    <a href="{{ route('privacy') }}" class="block rounded-lg bg-blue-50 px-3 py-2 font-semibold text-blue-700">Kebijakan Privasi</a>
                    <a href="{{ route('faq') }}" class="block rounded-lg px-3 py-2 font-semibold text-gray-700 hover:bg-gray-50">FAQ</a>
                </div>
            </div>
        </aside>

        <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-10">
            <div class="mb-8 border-b pb-6">
                <p class="text-sm text-gray-500">Terakhir diperbarui: {{ now()->translatedFormat('d M Y') }}</p>
                <p class="mt-3 leading-7 text-gray-600">
                    Kami menghargai privasi Anda. Kebijakan ini menjelaskan pengelolaan data saat Anda memakai website dan layanan Arsantara.
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                @foreach([
                    ['Informasi yang Dikumpulkan', 'Nama, email, nomor telepon, alamat, foto profil, data listing, aktivitas akun, dan informasi teknis seperti perangkat atau sesi kunjungan.'],
                    ['Penggunaan Data', 'Data digunakan untuk menjalankan akun, menampilkan listing, memproses permintaan, meningkatkan layanan, dan membantu komunikasi dengan admin atau penjual.'],
                    ['Perlindungan Data', 'Kami menerapkan pengamanan sesuai kemampuan sistem. Namun, tidak ada sistem digital yang sepenuhnya bebas risiko.'],
                    ['Pembagian Data', 'Arsantara tidak menjual data pribadi. Data dapat dibagikan jika dibutuhkan untuk layanan, persetujuan pengguna, atau kewajiban hukum.'],
                    ['Cookie & Analitik', 'Cookie dapat digunakan untuk menjaga sesi, meningkatkan pengalaman, dan membaca performa website.'],
                    ['Perubahan Kebijakan', 'Kebijakan privasi dapat diperbarui. Versi terbaru akan ditampilkan di halaman ini.'],
                ] as $section)
                    <section class="rounded-xl bg-gray-50 p-5">
                        <h2 class="text-xl font-bold text-gray-900">{{ $section[0] }}</h2>
                        <p class="mt-2 leading-7 text-gray-700">{{ $section[1] }}</p>
                    </section>
                @endforeach
            </div>
        </article>
    </div>
</main>
@endsection
