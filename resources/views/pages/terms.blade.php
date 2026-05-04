@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Legal Arsantara',
            'title' => 'Syarat & Ketentuan',
            'text' => 'Ketentuan penggunaan layanan marketplace, listing, transaksi, dan akun pengguna Arsantara.',
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
                    <a href="{{ route('terms') }}" class="block rounded-lg bg-blue-50 px-3 py-2 font-semibold text-blue-700">Syarat & Ketentuan</a>
                    <a href="{{ route('privacy') }}" class="block rounded-lg px-3 py-2 font-semibold text-gray-700 hover:bg-gray-50">Kebijakan Privasi</a>
                    <a href="{{ route('faq') }}" class="block rounded-lg px-3 py-2 font-semibold text-gray-700 hover:bg-gray-50">FAQ</a>
                </div>
            </div>
        </aside>

        <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-10">
            <div class="mb-8 border-b pb-6">
                <p class="text-sm text-gray-500">Terakhir diperbarui: {{ now()->translatedFormat('d M Y') }}</p>
                <p class="mt-3 leading-7 text-gray-600">
                    Dengan menggunakan layanan Arsantara, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan berikut.
                </p>
            </div>

            <div class="space-y-6 text-gray-700 leading-relaxed">
                @foreach([
                    ['Penggunaan Layanan', 'Arsantara menyediakan platform digital untuk jual beli properti, kendaraan, dan layanan terkait. Pengguna wajib menggunakan layanan secara sah dan tidak melanggar hukum yang berlaku.'],
                    ['Akun Pengguna', 'Pengguna bertanggung jawab atas keamanan akun, data login, dan aktivitas yang dilakukan melalui akun masing-masing.'],
                    ['Konten & Listing', 'Listing, foto, deskripsi, harga, dan informasi lain yang diunggah menjadi tanggung jawab pengguna. Arsantara berhak menolak, menyembunyikan, atau menghapus konten yang tidak pantas, tidak valid, atau melanggar aturan.'],
                    ['Transaksi', 'Arsantara berperan sebagai media informasi dan penghubung. Segala transaksi merupakan kesepakatan langsung antara penjual dan pembeli.'],
                    ['Pembiayaan', 'Informasi pembiayaan bersifat awal dan dapat berubah sesuai hasil verifikasi, survei, dan ketentuan mitra terkait.'],
                    ['Perubahan Ketentuan', 'Arsantara dapat memperbarui syarat dan ketentuan sewaktu-waktu. Pengguna disarankan mengecek halaman ini secara berkala.'],
                ] as $index => $section)
                    <section class="rounded-xl bg-gray-50 p-5">
                        <h2 class="text-xl font-bold text-gray-900">{{ $index + 1 }}. {{ $section[0] }}</h2>
                        <p class="mt-2">{{ $section[1] }}</p>
                    </section>
                @endforeach

                <section class="rounded-xl bg-red-50 p-5">
                    <h2 class="text-xl font-bold text-red-700">Larangan</h2>
                    <ul class="mt-3 list-disc space-y-2 pl-6">
                        <li>Mengunggah data palsu atau menyesatkan.</li>
                        <li>Melakukan penipuan, spam, atau penyalahgunaan platform.</li>
                        <li>Mengunggah konten yang melanggar hukum atau hak pihak lain.</li>
                    </ul>
                </section>
            </div>
        </article>
    </div>
</main>
@endsection
