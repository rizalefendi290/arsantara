@extends('layouts.app')

@section('content')
@php
    $faqs = [
        ['Apa itu Arsantara Management?', 'Arsantara Management adalah bisnis yang bergerak di bidang properti, otomotif (mobil bekas), serta pembiayaan dana dengan jaminan BPKB motor dan mobil.'],
        ['Apa saja layanan yang tersedia?', "Kami menyediakan:\n1. Jual beli properti (rumah & tanah)\n2. Jual mobil bekas\n3. Pinjaman dana jaminan BPKB motor & mobil"],
        ['Apakah Arsantara sudah berbadan hukum (PT/CV)?', 'Saat ini Arsantara Management masih dalam tahap pengembangan sebagai bisnis, dan ke depan memiliki rencana untuk menjadi badan usaha resmi (CV/PT) seiring dengan pertumbuhan dan kebutuhan bisnis.'],
        ['Apakah aman menggunakan layanan Arsantara?', 'Kami mengutamakan transparansi, komunikasi yang jelas, serta bekerja sama dengan mitra terpercaya untuk setiap layanan yang kami tawarkan.'],
        ['Bagaimana sistem pinjaman dana di Arsantara?', 'Kami membantu proses pengajuan pinjaman dengan jaminan BPKB melalui mitra leasing, sehingga proses lebih mudah, cepat, dan terarah.'],
        ['Berapa bunga pinjaman?', 'Bunga mulai dari 0,6% per bulan (efektif), tergantung hasil analisa dan persetujuan dari pihak pembiayaan.'],
        ['Apakah BPKB aman?', 'Ya, BPKB akan disimpan oleh pihak leasing resmi selama masa pinjaman berlangsung.'],
        ['Apakah kendaraan masih bisa digunakan?', 'Ya, kendaraan tetap bisa digunakan selama cicilan berjalan dengan baik.'],
        ['Berapa lama proses pencairan dana?', 'Umumnya proses pencairan memakan waktu 1–3 hari kerja, tergantung kelengkapan data.'],
        ['Apa saja syarat pengajuan pinjaman?', 'Syarat umum:KTP, STNK & BPKB, Kendaraan atas nama sendiri / pasangan'],
        ['Apakah Arsantara menyediakan mobil baru?', 'Saat ini kami fokus pada penjualan mobil bekas berkualitas dengan harga yang lebih terjangkau.'],
        ['Bagaimana kondisi mobil yang dijual?', 'Kami memastikan unit yang ditawarkan dalam kondisi layak pakai dan memberikan informasi secara transparan kepada calon pembeli.'],
        ['Apakah bisa kredit mobil?','Bisa, kami membantu proses pengajuan kredit melalui mitra leasing yang bekerja sama dengan kami.'],
        ['Apakah bisa tukar tambah mobil?','Ya, kami juga melayani tukar tambah sesuai kondisi dan nilai kendaraan Anda.'],
        ['Bagaimana proses pembelian properti?', 'Kami membantu dari pencarian, negosiasi, hingga proses administrasi agar transaksi lebih aman dan mudah.'],
        ['Apakah properti yang ditawarkan legal?', 'Kami mengutamakan properti dengan dokumen yang jelas untuk menjaga keamanan transaksi.'],
        ['Apakah bisa konsultasi terlebih dahulu?','Tentu, Anda bisa melakukan konsultasi GRATIS untuk menentukan solusi terbaik sesuai kebutuhan Anda.'],
        ['Apa keunggulan Arsantara dibanding yang lain?', "1. Layanan lengkap dalam satu platform (properti, otomotif, dana)\n2. Proses mudah dan cepat dengan dukungan tim profesional\n3. Fokus pada kepuasan pelanggan dengan solusi yang tepat"],
        ['Apakah Arsantara hanya melayani wilayah Tulungagung?', 'Saat ini kami fokus melayani wilayah Tulungagung dan sekitarnya, namun ke depan kami berencana untuk memperluas layanan ke wilayah lain.'],
        ['Apakah Arsantara menerima kerjasama?', 'Tentu, kami terbuka untuk bekerja sama dengan mitra yang memiliki visi dan misi yang sama dalam memberikan layanan terbaik kepada pelanggan.']
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
                        {!! nl2br(e($faq[1])) !!}
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
