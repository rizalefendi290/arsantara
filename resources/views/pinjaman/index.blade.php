@extends('layouts.app')

@section('content')
@php
    $phone = '62895347042844';
    $message = urlencode('Halo Admin Arsantara, saya ingin mengajukan pinjaman dana dengan jaminan BPKB. Mohon informasinya.');
    $waUrl = "https://wa.me/{$phone}?text={$message}";

    $heroSlides = [
        [
            'image' => asset('images/thumbnail_pinjam_dana.png'),
            'label' => 'Proses Cepat',
            'title' => 'Ajukan pinjam dana tanpa ribet',
            'text' => 'Konsultasikan kebutuhan dana Anda dan tim kami akan membantu proses awal dengan cepat.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Jaminan BPKB',
            'title' => 'Motor dan mobil bisa diajukan',
            'text' => 'Gunakan dokumen kendaraan sebagai jaminan dengan alur pengajuan yang jelas.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Kebutuhan Fleksibel',
            'title' => 'Dana untuk kebutuhan penting',
            'text' => 'Cocok untuk modal usaha, renovasi, pendidikan, atau kebutuhan mendesak lainnya.',
        ],
        [
            'image' => asset('images/hero.png'),
            'label' => 'Didampingi Admin',
            'title' => 'Langsung terhubung ke WhatsApp',
            'text' => 'Klik ajukan dan lanjutkan percakapan langsung dengan admin Arsantara.',
        ],
    ];
@endphp

<main class="bg-white">
    <x-hero-carousel :slides="$heroSlides" content-width="max-w-2xl" overlay="bg-gradient-to-r from-slate-950/80 via-slate-950/35 to-slate-950/10">
        <a href="{{ $waUrl }}" target="_blank"
            class="inline-flex min-h-12 items-center justify-center rounded-lg bg-green-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-950/20 transition hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-200">
            Ajukan via WhatsApp
        </a>
        <a href="#syarat"
            class="inline-flex min-h-12 items-center justify-center rounded-lg border border-white/60 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30">
            Lihat Syarat
        </a>
    </x-hero-carousel>

    <section id="syarat" class="bg-gradient-to-b from-blue-50 via-white to-white py-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-6 lg:grid-cols-2">
            <div data-aos="fade-up">
                <p class="mb-3 text-sm font-bold uppercase text-blue-600">Dana Tunai Arsantara</p>
                <h2 class="text-3xl font-extrabold leading-tight text-gray-900 md:text-5xl">
                    Pinjam dana dengan jaminan BPKB jadi lebih praktis.
                </h2>
                <p class="mt-5 text-lg leading-8 text-gray-600">
                    Siapkan dokumen kendaraan, kirim kebutuhan Anda ke admin, lalu tim kami akan membantu pengecekan awal dan informasi proses berikutnya.
                </p>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Syarat Mudah</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">KTP, STNK aktif, dan BPKB asli sebagai dokumen utama.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Respon Cepat</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Admin langsung menerima pesan pengajuan melalui WhatsApp.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Motor & Mobil</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Pengajuan dapat dilakukan untuk kendaraan roda dua maupun roda empat.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Didampingi Tim</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Anda akan dibantu memahami langkah dan kelengkapan pengajuan.</p>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-2xl">
                <img src="{{ asset('images/thumbnail_pinjam_dana.png') }}"
                    alt="Ajukan pinjam dana"
                    class="h-[520px] w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent"></div>
                <div class="absolute bottom-0 p-6 text-white">
                    <p class="text-sm font-semibold uppercase text-blue-200">Pengajuan Cepat</p>
                    <h3 class="mt-2 text-3xl font-extrabold">Ajukan sekarang, lanjutkan di WhatsApp.</h3>
                    <a href="{{ $waUrl }}" target="_blank"
                        class="mt-5 inline-flex rounded-xl bg-green-500 px-5 py-3 font-bold text-white transition hover:bg-green-600">
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
