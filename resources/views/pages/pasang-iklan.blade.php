@extends('layouts.app')

@section('content')
<main class="bg-white">
    @php
    $heroSlides = [
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Pasang Iklan di Arsantara',
            'title' => 'Pasarkan properti dan kendaraan dengan data yang rapi dan terpercaya.',
            'text' => 'Arsantara membantu agen dan pemilik produk menampilkan listing rumah, tanah, mobil, dan motor kepada calon pembeli dengan informasi yang jelas, foto berkualitas, dan proses review admin.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Jangkauan Listing',
            'title' => 'Tampilkan produk Anda ke calon pembeli yang tepat.',
            'text' => 'Produk tampil di halaman kategori, pencarian, dan dapat masuk rekomendasi beranda setelah disetujui admin.',
        ],
        [
            'image' => asset('images/hero.png'),
            'label' => 'Agen & Pemilik',
            'title' => 'Kelola listing dari akun yang sesuai kebutuhan.',
            'text' => 'Agen dan pemilik produk dapat mengunggah data, foto, serta detail produk secara lebih profesional.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[560px]" inner-height="min-h-[560px]" content-width="max-w-3xl">
    @auth
        @if(in_array(auth()->user()->role, ['agen', 'pemilik']))
            <a href="{{ route('agent.listings.create') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
                Upload Produk
            </a>
        @else
            <button type="button" onclick="openUpgradeModal('agen')" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
                Daftar Sebagai Agen
            </button>
            <button type="button" onclick="openUpgradeModal('pemilik')" class="rounded-xl border border-white/40 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">
                Daftar Pemilik Produk
            </button>
        @endif
    @else
        <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" onclick="showRegister()" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
            Daftar Akun
        </button>
        <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" class="rounded-xl border border-white/40 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">
            Login
        </button>
    @endauth
</x-hero-carousel>

    <section class="mx-auto max-w-screen-xl px-4 py-14 lg:px-6">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-blue-700">Benefit 01</p>
                <h3 class="mt-2 text-xl font-extrabold text-gray-950">Jangkauan listing lebih luas</h3>
                <p class="mt-3 text-sm leading-6 text-gray-600">Produk tampil di halaman kategori, pencarian, dan dapat masuk rekomendasi beranda setelah disetujui admin.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-blue-700">Benefit 02</p>
                <h3 class="mt-2 text-xl font-extrabold text-gray-950">Data produk lebih profesional</h3>
                <p class="mt-3 text-sm leading-6 text-gray-600">Harga, lokasi, kondisi, kategori, foto, dan detail teknis disusun agar mudah dibandingkan calon pembeli.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-blue-700">Benefit 03</p>
                <h3 class="mt-2 text-xl font-extrabold text-gray-950">Terhubung lewat WhatsApp</h3>
                <p class="mt-3 text-sm leading-6 text-gray-600">Calon pembeli dapat menghubungi pemilik atau agen dari halaman detail produk secara langsung.</p>
            </div>
        </div>
    </section>

    <section class="bg-gray-50">
        <div class="mx-auto grid max-w-screen-xl gap-10 px-4 py-14 lg:grid-cols-[0.9fr_1.1fr] lg:px-6">
            <div>
                <p class="text-sm font-bold uppercase text-blue-700">Panduan Upload</p>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-950">Syarat dan ketentuan produk agar cepat disetujui</h2>
                <p class="mt-4 text-sm leading-6 text-gray-600">
                    Gunakan informasi asli, foto yang jelas, dan harga yang wajar. Admin dapat menunda atau menolak listing jika data tidak sesuai.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @php
                    $rules = [
                        ['title' => 'Gunakan foto produk asli', 'text' => 'Jangan memakai foto produk lain, foto stok, atau gambar yang tidak sesuai kondisi sebenarnya.'],
                        ['title' => 'Foto harus jelas dan terang', 'text' => 'Gunakan foto tajam, fokus, tidak gelap, dan menampilkan objek utama dengan baik.'],
                        ['title' => 'Ukuran file maksimal 4 MB', 'text' => 'Kompres gambar bila perlu agar upload lebih cepat tanpa mengorbankan kejelasan foto.'],
                        ['title' => 'Tampilkan beberapa sudut', 'text' => 'Untuk properti, sertakan depan bangunan, ruang utama, kamar, dapur, kamar mandi, halaman, atau fasilitas. Untuk kendaraan, sertakan eksterior, interior, odometer, mesin, dan dokumen pendukung bila ada.'],
                        ['title' => 'Hindari foto kolase sebagai utama', 'text' => 'Gunakan satu foto penuh untuk gambar utama agar produk terlihat jelas di kartu listing.'],
                        ['title' => 'Brosur bukan foto utama', 'text' => 'Brosur boleh menjadi pelengkap, tetapi foto utama harus menampilkan produk asli.'],
                        ['title' => 'Watermark situs lain tidak diperbolehkan', 'text' => 'Logo agen atau informasi kontak boleh selama tidak menutupi produk, tetapi watermark marketplace lain sebaiknya dihapus.'],
                        ['title' => 'Kategori harus sesuai', 'text' => 'Pilih rumah, tanah, mobil, atau motor sesuai produk yang benar agar mudah ditemukan.'],
                        ['title' => 'Deskripsi lengkap dan jujur', 'text' => 'Jelaskan lokasi, kondisi, fasilitas, spesifikasi, dokumen, kekurangan penting, dan keunggulan produk.'],
                        ['title' => 'Harga harus realistis', 'text' => 'Gunakan harga jual atau sewa yang benar. Listing dengan harga janggal dapat ditunda oleh admin.'],
                    ];
                @endphp

                @foreach($rules as $index => $rule)
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                            {{ $index + 1 }}
                        </div>
                        <h3 class="font-extrabold text-gray-950">{{ $rule['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">{{ $rule['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-screen-xl px-4 py-14 lg:px-6">
        <div class="rounded-3xl bg-blue-700 p-6 text-white md:p-10">
            <div class="grid gap-8 md:grid-cols-[1fr_auto] md:items-center">
                <div>
                    <p class="text-sm font-bold uppercase text-blue-100">Siap mulai?</p>
                    <h2 class="mt-2 text-3xl font-extrabold">Daftar, lengkapi data, lalu upload produk terbaik Anda.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100">
                        Setelah pengajuan role disetujui admin, Anda dapat mengelola produk dari dashboard agen atau pemilik.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @auth
                        @if(in_array(auth()->user()->role, ['agen', 'pemilik']))
                            <a href="{{ route('agent.listings.create') }}" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
                                Upload Produk
                            </a>
                        @else
                            <button type="button" onclick="openUpgradeModal('agen')" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
                                Ajukan Jadi Agen
                            </button>
                        @endif
                    @else
                        <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" onclick="showRegister()" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
                            Buat Akun
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
