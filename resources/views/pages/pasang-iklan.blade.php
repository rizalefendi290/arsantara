@extends('layouts.app')

@section('content')
<main class="bg-white">
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-800 to-slate-950 pt-28 text-white">
        <div class="absolute inset-0 opacity-20">
            <img src="{{ asset('images/thumbnail_properti.png') }}" alt="" class="h-full w-full object-cover">
        </div>
        <div class="relative mx-auto grid max-w-screen-xl gap-10 px-4 py-14 lg:grid-cols-[1.2fr_0.8fr] lg:px-6 lg:py-20">
            <div>
                <p class="mb-4 inline-flex rounded-full border border-white/30 px-4 py-1 text-xs font-bold uppercase tracking-wide">
                    Pasang Iklan di Arsantara
                </p>
                <h1 class="max-w-3xl text-4xl font-extrabold leading-tight md:text-6xl">
                    Pasarkan properti dan kendaraan dengan data yang rapi dan terpercaya.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-blue-100 md:text-lg">
                    Arsantara membantu agen dan pemilik produk menampilkan listing rumah, tanah, mobil, dan motor kepada calon pembeli dengan informasi yang jelas, foto berkualitas, dan proses review admin.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        @if(in_array(auth()->user()->role, ['agen', 'pemilik']))
                            <a href="{{ route('agent.listings.create') }}" class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-800 shadow hover:bg-blue-50">
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
                </div>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white/95 p-6 text-gray-900 shadow-2xl">
                <h2 class="text-xl font-extrabold">Siapa yang bisa pasang iklan?</h2>
                <div class="mt-5 space-y-4">
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <h3 class="font-bold text-blue-800">Agen</h3>
                        <p class="mt-1 text-sm text-gray-600">Untuk pemasar properti atau kendaraan yang mengelola beberapa listing.</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <h3 class="font-bold text-emerald-800">Pemilik Produk</h3>
                        <p class="mt-1 text-sm text-gray-600">Untuk pemilik rumah, tanah, mobil, atau motor yang ingin menjual langsung.</p>
                    </div>
                    <div class="rounded-xl border border-orange-100 bg-orange-50 p-4">
                        <h3 class="font-bold text-orange-800">Produk yang didukung</h3>
                        <p class="mt-1 text-sm text-gray-600">Rumah, tanah, mobil, motor, dan kategori aktif lain yang tersedia di Arsantara.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
