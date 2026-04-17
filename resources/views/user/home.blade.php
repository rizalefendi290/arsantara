@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="bg-center bg-no-repeat bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/conference.jpg')] ">
    <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-40">
        <h1 class="mb-6 text-4xl font-bold tracking-tight text-white md:text-5xl lg:text-6xl">
            Solusi Terbaik untuk Kebutuhan Anda
        </h1>
        <p class="mb-8 text-lg text-gray-300 md:text-xl sm:px-16 lg:px-48">
            Arsantara menyediakan layanan otomotif, pinjaman dana, dan properti dengan proses cepat dan terpercaya.
        </p>


    </div>
</section>

<div class="container mx-auto p-6">

    <!-- FITUR -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 -mt-20 relative z-10">

        <!-- CARD -->
        <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <img class="w-full h-48 object-cover" src="{{ asset('images/logo.png') }}" alt="">
            <div class="p-6">
                <h5 class="mb-2 text-xl font-bold text-gray-900">Arsantara Autoshow</h5>
                <p class="mb-4 text-gray-600">
                    Miliki beragam pilihan Mobil dan Motor, baik Baru maupun Bekas melalui pengajuan kredit.
                </p>
                <a href="{{ route('autoshow') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Lihat Selengkapnya →
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <img class="w-full h-48 object-cover" src="{{ asset('images/logo.png') }}" alt="">
            <div class="p-6">
                <h5 class="mb-2 text-xl font-bold text-gray-900">Arsantara Pinjam Dana</h5>
                <p class="mb-4 text-gray-600">
                    Ajukan pinjaman dana dengan jaminan BPKB secara cepat dan mudah.
                </p>
                <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Ajukan →
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <img class="w-full h-48 object-cover" src="{{ asset('images/logo.png') }}" alt="">
            <div class="p-6">
                <h5 class="mb-2 text-xl font-bold text-gray-900">Arsantara Properti</h5>
                <p class="mb-4 text-gray-600">
                    Temukan rumah dan tanah terbaik dengan harga terjangkau.
                </p>
                <a href="{{ route('properti') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Lihat Selengkapnya →
                </a>
            </div>
        </div>

    </div>

    <!-- CAROUSEL -->
    @if($carousels->count())
    <div id="indicators-carousel" class="relative w-full my-10" data-carousel="slide">
        <div class="relative h-56 overflow-hidden rounded-xl md:h-96 shadow">
            @foreach($carousels as $index => $item)
            <div class="{{ $index == 0 ? '' : 'hidden' }}" data-carousel-item>
                <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- MARKETPLACE -->
    <h1 class="text-3xl font-bold mb-8 text-white">
        Marketplace 
    </h1>


        @foreach($categories as $category)

        @php
            $route = '#';

            if(strtolower($category->name) == 'mobil'){
                $route = 'mobil.index';
            } elseif(strtolower($category->name) == 'motor'){
                $route = 'motor.index';
            } elseif(strtolower($category->name) == 'rumah'){
                $route = 'rumah.index';
            } elseif(strtolower($category->name) == 'tanah'){
                $route = 'tanah.index';
            }
        @endphp

        <div class="mb-10">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $category->name }}
                </h2>

                <a href="{{ route($route) }}"
                class="text-blue-600 text-sm hover:underline">
                Lihat Semua →
                </a>
            </div>

            <!-- LISTING -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @forelse($category->listings->take(4) as $listing)
                <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                    <img 
                        src="{{ $listing->images->count() 
                                ? asset('storage/'.$listing->images->first()->image) 
                                : 'https://via.placeholder.com/300x200' }}"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 line-clamp-1">
                            {{ $listing->title }}
                        </h3>

                        <p class="text-gray-500 text-sm">
                            {{ $listing->location }}
                        </p>

                        <p class="text-blue-600 font-bold mt-1">
                            Rp {{ number_format($listing->price) }}
                        </p>

                        <a href="{{ route('listing.show',$listing->id) }}"
                        class="block mt-3 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Detail
                        </a>
                    </div>

                </div>
                @empty
                <p class="text-gray-400">Belum ada data</p>
                @endforelse

            </div>

        </div>

    @endforeach

    <!-- ================= FAQ ================= -->
<section class="bg-gray-50 py-12 mt-10">
    <div class="container mx-auto px-6 max-w-4xl">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
            Pertanyaan Umum (FAQ)
        </h2>

        <div class="space-y-4">

            @php
            $faqs = [
                [
                    "q" => "Apa itu Arsantara?",
                    "a" => "Arsantara adalah platform marketplace yang menyediakan layanan jual beli mobil, motor, properti, serta pengajuan pinjaman secara online."
                ],
                [
                    "q" => "Bagaimana cara membeli produk di Arsantara?",
                    "a" => "Pilih produk yang diinginkan, lalu klik tombol 'Hubungi Penjual' untuk melanjutkan komunikasi melalui WhatsApp."
                ],
                [
                    "q" => "Apakah produk yang ditampilkan masih tersedia?",
                    "a" => "Ketersediaan produk bisa berubah. Silakan hubungi admin atau penjual melalui WhatsApp untuk memastikan."
                ],
                [
                    "q" => "Apakah Arsantara menyediakan sistem kredit?",
                    "a" => "Ya, beberapa produk seperti mobil dan motor dapat diajukan melalui sistem kredit. Silakan hubungi admin untuk simulasi."
                ],
                [
                    "q" => "Apakah aman bertransaksi di Arsantara?",
                    "a" => "Kami membantu mempertemukan penjual dan pembeli secara transparan. Disarankan untuk melakukan pengecekan langsung sebelum transaksi."
                ],
                [
                    "q" => "Bagaimana cara menjual produk di Arsantara?",
                    "a" => "Silakan hubungi admin untuk mendaftarkan produk Anda agar dapat ditampilkan di marketplace Arsantara."
                ],
                [
                    "q" => "Apa saja jenis properti yang tersedia?",
                    "a" => "Kami menyediakan berbagai jenis properti seperti rumah dan tanah dengan berbagai pilihan harga dan lokasi."
                ],
                [
                    "q" => "Apakah bisa mengajukan pinjaman dana?",
                    "a" => "Ya, Anda bisa mengajukan pinjaman dengan agunan seperti BPKB kendaraan melalui fitur Arsantara Pinjam Dana."
                ],
                [
                    "q" => "Bagaimana cara mengetahui legalitas properti?",
                    "a" => "Setiap properti memiliki informasi sertifikat seperti SHM atau SHGB. Pastikan untuk mengecek langsung dokumen sebelum transaksi."
                ],
                [
                    "q" => "Apakah ada biaya tambahan saat transaksi?",
                    "a" => "Untuk informasi biaya atau administrasi, silakan hubungi admin karena dapat berbeda tergantung jenis produk dan layanan."
                ],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="bg-white border rounded-xl shadow">
                <button onclick="toggleFAQ({{ $i }})" 
                    class="w-full flex justify-between items-center p-4 text-left font-semibold text-gray-800">
                    {{ $faq['q'] }}
                    <span id="icon-{{ $i }}">+</span>
                </button>
                <div id="faq-{{ $i }}" class="hidden px-4 pb-4 text-gray-600">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach

        </div>

    </div>
</section>

</div>
@endsection
<script>
function toggleFAQ(id){
    const content = document.getElementById('faq-' + id);
    const icon = document.getElementById('icon-' + id);

    if(content.classList.contains('hidden')){
        content.classList.remove('hidden');
        icon.innerHTML = '−';
    } else {
        content.classList.add('hidden');
        icon.innerHTML = '+';
    }
}
</script>