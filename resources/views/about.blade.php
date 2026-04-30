@extends('layouts.app')

@section('content')
<section class="relative min-h-[560px] flex items-center bg-cover bg-center"
    style="background-image:url('{{ asset('images/hero.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/95 via-blue-900/75 to-blue-700/30"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-28 text-white">
        <div class="max-w-3xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-blue-200">Tentang Arsantara</p>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                Ekosistem digital untuk properti, kendaraan, dan pembiayaan.
            </h1>
            <p class="mt-5 text-lg text-blue-100 leading-relaxed">
                Arsantara membantu pengguna menemukan listing terpercaya, membandingkan pilihan, dan terhubung cepat dengan penjual atau admin melalui pengalaman marketplace yang sederhana.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('properti') }}"
                    class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow hover:bg-blue-700">
                    Jelajahi Properti
                </a>
                <a href="{{ route('autoshow') }}"
                    class="rounded-xl bg-white/10 px-5 py-3 font-semibold text-white ring-1 ring-white/30 backdrop-blur hover:bg-white/20">
                    Lihat Autoshow
                </a>
            </div>
        </div>
    </div>
</section>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <section class="relative z-20 -mt-16 px-6">
        <div class="mx-auto grid max-w-6xl gap-4 rounded-2xl border border-white/50 bg-white p-5 shadow-2xl md:grid-cols-4">
            <div class="rounded-xl bg-blue-50 p-5">
                <p class="text-3xl font-extrabold text-blue-700">4</p>
                <p class="mt-1 text-sm font-semibold text-gray-700">Kategori marketplace</p>
            </div>
            <div class="rounded-xl bg-green-50 p-5">
                <p class="text-3xl font-extrabold text-green-700">24/7</p>
                <p class="mt-1 text-sm font-semibold text-gray-700">Akses pencarian online</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-5">
                <p class="text-3xl font-extrabold text-slate-900">1</p>
                <p class="mt-1 text-sm font-semibold text-gray-700">Platform terpadu</p>
            </div>
            <div class="rounded-xl bg-indigo-50 p-5">
                <p class="text-3xl font-extrabold text-indigo-700">WA</p>
                <p class="mt-1 text-sm font-semibold text-gray-700">Kontak cepat via WhatsApp</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div>
                <div class="mb-10 max-w-3xl">
                    <p class="text-sm font-semibold uppercase text-blue-600">Siapa Kami</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900 md:text-4xl">
                        Arsantara hadir untuk membuat proses mencari properti dan kendaraan terasa lebih jelas.
                    </h2>
                    <p class="mt-4 text-gray-600 leading-8">
                        Kami menghubungkan pembeli, pemilik, agen, dan admin dalam satu ekosistem digital. Dari rumah, tanah, mobil, motor, sampai layanan pembiayaan, Arsantara dirancang agar pengguna bisa menelusuri pilihan dengan cepat, melihat detail yang relevan, lalu menghubungi pihak terkait tanpa proses yang rumit.
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 21h18M5 21V7l8-4 6 4v14M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">Properti</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Pilihan rumah dan tanah dengan informasi harga, lokasi, sertifikat, dan detail utama.
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 17h14M7 17l1-5h8l1 5M7 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM17 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">Autoshow</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Mobil dan motor yang bisa dibandingkan dari merk, model, transmisi, kondisi, dan harga.
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8c-2.8 0-5 1.3-5 3s2.2 3 5 3 5-1.3 5-3-2.2-3-5-3ZM4 6h16v12H4z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">Pembiayaan</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Akses informasi pinjaman dana dan pengajuan awal melalui kanal komunikasi yang mudah.
                        </p>
                    </div>
                </div>

                <div class="mt-12 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
                        <p class="text-sm font-semibold uppercase text-blue-600">Visi</p>
                        <h3 class="mt-2 text-2xl font-bold text-gray-900">Menjadi platform digital terpercaya.</h3>
                        <p class="mt-3 leading-7 text-gray-600">
                            Arsantara ingin menjadi tempat pertama yang dituju masyarakat saat mencari properti, kendaraan, dan solusi finansial terkait kebutuhan aset.
                        </p>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
                        <p class="text-sm font-semibold uppercase text-blue-600">Misi</p>
                        <ul class="mt-3 space-y-3 text-gray-600">
                            <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>Menyajikan informasi listing yang mudah dipahami.</li>
                            <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>Menghubungkan pengguna dengan penjual secara cepat.</li>
                            <li class="flex gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>Menghadirkan pengalaman digital yang rapi dan transparan.</li>
                        </ul>
                    </div>
                </div>

                <section class="mt-12">
                    <div class="mb-6">
                        <p class="text-sm font-semibold uppercase text-blue-600">Cara Kerja</p>
                        <h2 class="mt-2 text-3xl font-bold text-gray-900">Dari pencarian sampai terhubung.</h2>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach([
                            ['01', 'Cari', 'Gunakan kategori dan filter untuk menemukan listing yang paling sesuai.'],
                            ['02', 'Bandingkan', 'Buka detail produk, lihat foto, harga, spesifikasi, dan lokasi.'],
                            ['03', 'Hubungi', 'Gunakan tombol WhatsApp atau call untuk lanjut konsultasi dan transaksi.'],
                        ] as $step)
                            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                                <p class="text-sm font-extrabold text-blue-600">{{ $step[0] }}</p>
                                <h3 class="mt-3 text-xl font-bold text-gray-900">{{ $step[1] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-gray-600">{{ $step[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900">Arsantara News</h2>
                    <div class="mt-4 space-y-4">
                        @forelse($posts as $post)
                            <a href="{{ route('post.show', $post->id) }}"
                                class="group flex gap-3 rounded-lg p-2 transition hover:bg-blue-50">
                                <img src="{{ $post->images->count() ? asset('storage/'.$post->images->first()->image) : asset('images/logo.png') }}"
                                    class="h-20 w-24 shrink-0 rounded object-cover"
                                    alt="{{ $post->title }}">

                                <div>
                                    <h3 class="line-clamp-2 text-sm font-bold text-gray-900 group-hover:text-blue-700">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">{{ $post->created_at->format('d M Y') }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-400">Belum ada berita</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl bg-blue-700 p-6 text-white shadow-sm">
                    <h2 class="text-xl font-bold">Butuh bantuan memilih?</h2>
                    <p class="mt-2 text-sm leading-6 text-blue-100">
                        Tim Arsantara siap membantu Anda memahami pilihan properti, kendaraan, atau pembiayaan yang tersedia.
                    </p>
                    <a href="https://wa.me/62895347042844?text={{ urlencode('Halo Admin Arsantara, saya ingin bertanya tentang layanan Arsantara') }}"
                        target="_blank"
                        class="mt-5 inline-flex rounded-xl bg-white px-4 py-2.5 font-semibold text-blue-700 hover:bg-blue-50">
                        Hubungi Admin
                    </a>
                </div>
            </aside>
        </div>
    </section>
</main>
@endsection
