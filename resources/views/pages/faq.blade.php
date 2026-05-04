@extends('layouts.app')

@section('content')
@php
    $faqs = [
        ['Apa itu Arsantara?', 'Arsantara adalah platform marketplace yang menyediakan layanan jual beli mobil, motor, properti, serta pengajuan pinjaman secara online.'],
        ['Bagaimana cara membeli produk di Arsantara?', 'Pilih produk yang diinginkan, lalu klik tombol Hubungi Penjual atau WhatsApp untuk melanjutkan komunikasi.'],
        ['Apakah produk yang ditampilkan masih tersedia?', 'Ketersediaan produk bisa berubah. Silakan hubungi admin atau penjual melalui WhatsApp untuk memastikan.'],
        ['Apakah Arsantara menyediakan sistem kredit?', 'Ya, beberapa produk seperti mobil dan motor dapat diajukan melalui sistem kredit. Silakan hubungi admin untuk simulasi.'],
        ['Apakah aman bertransaksi di Arsantara?', 'Kami membantu mempertemukan penjual dan pembeli secara transparan. Disarankan melakukan pengecekan langsung sebelum transaksi.'],
        ['Bagaimana cara menjual produk di Arsantara?', 'Silakan daftar atau hubungi admin untuk mendaftarkan produk Anda agar dapat ditampilkan di marketplace Arsantara.'],
        ['Apa saja jenis properti yang tersedia?', 'Kami menyediakan berbagai jenis properti seperti rumah dan tanah dengan pilihan harga dan lokasi.'],
        ['Apakah bisa mengajukan pinjaman dana?', 'Ya, Anda bisa mengajukan pinjaman dengan agunan seperti BPKB kendaraan melalui fitur Arsantara Pinjam Dana.'],
        ['Bagaimana cara mengetahui legalitas properti?', 'Setiap properti memiliki informasi sertifikat seperti SHM atau SHGB. Pastikan untuk mengecek dokumen langsung sebelum transaksi.'],
        ['Apakah ada biaya tambahan saat transaksi?', 'Untuk informasi biaya atau administrasi, silakan hubungi admin karena dapat berbeda tergantung jenis produk dan layanan.'],
    ];
@endphp

@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Pusat Bantuan',
            'title' => 'Pertanyaan Umum',
            'text' => 'Jawaban singkat untuk pertanyaan yang sering ditanyakan tentang layanan Arsantara.',
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
    <div class="mx-auto max-w-5xl px-6 py-12">
        <div class="space-y-4">
            @foreach($faqs as $i => $faq)
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                    <button type="button" onclick="toggleFAQ({{ $i }})"
                        class="flex w-full items-center justify-between gap-4 p-5 text-left font-bold text-gray-900">
                        <span>{{ $faq[0] }}</span>
                        <span id="icon-{{ $i }}" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">+</span>
                    </button>
                    <div id="faq-{{ $i }}" class="hidden px-5 pb-5 leading-7 text-gray-600">
                        {{ $faq[1] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 rounded-2xl bg-blue-700 p-6 text-white">
            <h2 class="text-2xl font-bold">Masih punya pertanyaan?</h2>
            <p class="mt-2 text-blue-100">Hubungi admin Arsantara untuk bantuan lebih lanjut.</p>
            <a href="https://wa.me/62895347042844?text={{ urlencode('Halo Admin Arsantara, saya ingin bertanya tentang layanan Arsantara') }}"
                target="_blank"
                class="mt-5 inline-flex rounded-xl bg-white px-5 py-3 font-semibold text-blue-700 hover:bg-blue-50">
                Hubungi Admin
            </a>
        </div>
    </div>
</main>

<script>
function toggleFAQ(id) {
    const content = document.getElementById('faq-' + id);
    const icon = document.getElementById('icon-' + id);
    const open = content.classList.contains('hidden');

    content.classList.toggle('hidden', !open);
    icon.textContent = open ? '-' : '+';
}
</script>
@endsection
