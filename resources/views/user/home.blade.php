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
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
                    onclick="window.location='{{ route('listing.show',$listing->id) }}'">

                    <!-- ================= CAROUSEL ================= -->
                    <div class="relative">

                        @php
                            $images = $listing->images;
                        @endphp

                        <div class="relative h-48 overflow-hidden">

                            @forelse($images as $index => $img)
                            <img src="{{ asset('storage/'.$img->image) }}"
                                class="card-slide absolute inset-0 w-full h-full object-cover transition duration-300 {{ $index == 0 ? '' : 'hidden' }}">
                            @empty
                            <img src="https://via.placeholder.com/300x200"
                                class="w-full h-full object-cover">
                            @endforelse

                        </div>

                        <!-- BUTTON -->
                        <button onclick="event.stopPropagation(); prevSlide(this)"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
                            ❮
                        </button>

                        <button onclick="event.stopPropagation(); nextSlide(this)"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
                            ❯
                        </button>

                    </div>

                    <!-- ================= CONTENT ================= -->
                    <div class="p-4">

                        <h3 class="font-semibold line-clamp-1">
                            {{ $listing->title }}
                        </h3>

                        <p class="text-gray-500 text-sm">
                            {{ $listing->location }}
                        </p>

                        <p class="text-blue-600 font-bold mt-1">
                            Rp {{ number_format($listing->price) }}
                        </p>

                        <!-- WA BUTTON -->
                        @php
                            $phone = "62895347042844";
                            $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
                        @endphp

                        <a href="https://wa.me/{{ $phone }}?text={{ $message }}"
                            onclick="event.stopPropagation()"
                            target="_blank"
                            class="block mt-3 text-center bg-green-500 text-white py-2 rounded-lg">
                            WhatsApp
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

<section>
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Berita Terbaru</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($posts as $post)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
                onclick="window.location='{{ route('post.show',$post->id) }}'">

                <img 
                    src="{{ $post->images->count() 
                        ? asset('storage/'.$post->images->first()->image) 
                        : 'https://via.placeholder.com/300x200' }}"
                    class="w-full h-48 object-cover"
                >

                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 line-clamp-2">
                        {{ $post->title }}
                    </h3>

                    <p class="text-gray-500 text-sm mt-2 line-clamp-2">
                        {{ Str::limit(strip_tags($post->content), 80) }}
                    </p>

                    <p class="text-xs text-gray-400 mt-2">
                        {{ $post->created_at->format('d M Y') }}
                    </p>
                </div>

            </div>
            @endforeach

        </div>
    </div>
</section>

<section class="mt-20">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
            Testimoni Mereka Tentang Arsantara
        </h2>

        <a href="{{ route('testimoni.index') }}"
            class="bg-black text-white px-5 py-2 rounded-full text-sm">
            Lihat Semua
        </a>
    </div>

    <div class="relative">

        <!-- LEFT -->
        <button onclick="scrollTesti(-1)"
            class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow w-10 h-10 rounded-full items-center justify-center">
            ←
        </button>

        <!-- SLIDER -->
        <div id="testi-slider"
            class="flex gap-6 overflow-x-auto scroll-smooth pb-4 no-scrollbar">

            @foreach($testimonials as $item)

            @php
                $initial = strtoupper(substr($item->name,0,1));
            @endphp

            <div class="min-w-[320px] max-w-[320px] bg-white rounded-2xl p-6 shadow hover:shadow-lg transition relative">

                <!-- AVATAR BULAT -->
                       <img 
                            src="{{ $item->photo 
                                ? asset('storage/'.$item->photo) 
                                : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                            class="w-20 h-20 rounded-full object-cover">

                <!-- QUOTE ICON -->
                <div class="absolute top-4 right-4 bg-yellow-400 w-8 h-8 flex items-center justify-center rounded">
                    "
                </div>

                <!-- CONTENT -->
                <div class="mt-6">

                    <h3 class="font-bold text-gray-800 uppercase">
                        {{ $item->name }}
                    </h3>

                    <!-- ⭐ RATING -->
                    <div class="text-yellow-400 mb-3">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $item->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>

                    <!-- MESSAGE -->
                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-4">
                        {{ $item->message }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        {{ $item->created_at->translatedFormat('d M Y') }} • {{ $item->created_at->diffForHumans() }}
                    </p>

                </div>

            </div>

            @endforeach

        </div>

        <!-- RIGHT -->
        <button onclick="scrollTesti(1)"
            class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow w-10 h-10 rounded-full items-center justify-center">
            →
        </button>

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

function scrollTesti(direction){
    const container = document.getElementById('testi-slider');
    container.scrollBy({
        left: direction * 340,
        behavior: 'smooth'
    });
}

function nextSlide(btn){
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');

    let activeIndex = 0;

    slides.forEach((img, i) => {
        if(!img.classList.contains('hidden')){
            activeIndex = i;
        }
        img.classList.add('hidden');
    });

    let nextIndex = (activeIndex + 1) % slides.length;
    slides[nextIndex].classList.remove('hidden');
}

function prevSlide(btn){
    const container = btn.closest('.relative');
    const slides = container.querySelectorAll('.card-slide');

    let activeIndex = 0;

    slides.forEach((img, i) => {
        if(!img.classList.contains('hidden')){
            activeIndex = i;
        }
        img.classList.add('hidden');
    });

    let prevIndex = (activeIndex - 1 + slides.length) % slides.length;
    slides[prevIndex].classList.remove('hidden');
}
</script>