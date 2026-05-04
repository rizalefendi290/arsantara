@extends('layouts.app')

@section('content')
@php
    $homeHeroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Arsantara Marketplace',
            'title' => 'Temukan properti impian Anda',
            'text' => 'Rumah, tanah, mobil hingga kebutuhan lainnya dalam satu platform terpercaya.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Properti Pilihan',
            'title' => 'Rumah dan tanah dalam satu tempat',
            'text' => 'Cari properti aktif, lokasi strategis, dan pilihan harga yang mudah dibandingkan.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Autoshow Arsantara',
            'title' => 'Mobil dan motor siap Anda bandingkan',
            'text' => 'Jelajahi kendaraan berdasarkan harga, kondisi, kategori, dan kebutuhan harian Anda.',
        ],
        [
            'image' => asset('images/thumbnail_pinjam_dana.png'),
            'label' => 'Pinjaman Dana',
            'title' => 'Ajukan dana dengan jaminan BPKB',
            'text' => 'Konsultasikan kebutuhan dana dan lanjutkan proses awal bersama admin Arsantara.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$homeHeroSlides" height="min-h-screen" inner-height="min-h-[calc(100vh-6rem)]" content-width="max-w-2xl" />

<!-- SEARCH BOX FLOAT -->
<div class="relative -mt-20 z-20 px-6 flex justify-center">
    
    <form data-aos="zoom-in"
        method="GET"
        action="{{ route('search') }}"
        class="w-full max-w-4xl bg-blue backdrop-blur-xl border border-white/30 rounded-3xl p-6 shadow-2xl">

        <!-- TAB -->
        <div class="flex justify-center gap-3 mb-6">
            
            <button type="button" onclick="setCategory(1)"
                class="tab-btn px-5 py-2 rounded-full text-sm font-medium transition 
                bg-blue-600 text-white shadow-md hover:bg-blue-700">
                Rumah
            </button>

            <button type="button" onclick="setCategory(2)"
                class="tab-btn px-5 py-2 rounded-full text-sm font-medium transition 
                bg-blue-100 text-blue-700 hover:bg-blue-200">
                Tanah
            </button>

            <button type="button" onclick="setCategory(3)"
                class="tab-btn px-5 py-2 rounded-full text-sm font-medium transition 
                bg-blue-100 text-blue-700 hover:bg-blue-200">
                Mobil
            </button>

        </div>

        <!-- INPUT -->
        <div class="flex flex-col md:flex-row gap-3">

            <input type="text" name="keyword"
                placeholder="Cari properti atau kendaraan..."
                class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-black outline-none">

            <button type="submit"
                class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold 
                hover:bg-blue-700 transition shadow-md">
                Cari
            </button>

        </div>

        <input type="hidden" name="category" id="category">

    </form>
</div>

<div class="relative mt-10 py-20 overflow-hidden bg-gradient-to-b from-blue-50 via-white to-white">

    <!-- WAVE ATAS (LEBIH HALUS) -->
<div class="absolute top-0 left-0 w-full overflow-hidden leading-none z-0">
    <svg class="relative block w-full h-[140px]" viewBox="0 0 1440 320">
        <path fill="#e0f2fe"
            d="M0,160C240,80,480,80,720,160C960,240,1200,240,1440,160L1440,0L0,0Z">
        </path>
    </svg>
</div>

    <!-- GRADIENT BULAT HALUS -->
    <div class="absolute top-20 left-[-100px] w-[400px] h-[400px] bg-blue-200 opacity-30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-[-120px] w-[450px] h-[450px] bg-blue-300 opacity-20 rounded-full blur-3xl"></div>

    <!-- DOT PATTERN (LEBIH HALUS) -->
    <div class="absolute top-24 right-16 grid grid-cols-6 gap-2 opacity-10">
        @for ($i = 0; $i < 24; $i++)
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
        @endfor
    </div>

    <!-- CIRCLE LINE -->
    <div class="absolute bottom-16 left-16 w-48 h-48 border border-blue-200 rounded-full opacity-20"></div>

    <!-- CONTENT -->
<div data-aos="fade-up" 
    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10 px-6 max-w-6xl mx-auto -mt-24">

    <!-- CARD 1 -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px]">

        <!-- IMAGE (TANPA BLUR) -->
        <img src="{{ asset('images/thumbnail_properti.png') }}"
            class="absolute inset-0 w-full h-full object-contain bg-blue-900">

        <!-- OVERLAY TIPIS -->
        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/40 to-transparent"></div>

        <!-- CONTENT -->
        <div class="relative z-10 p-6 flex flex-col h-full justify-between text-white">

            <div>
                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 text-xs border border-white/40 px-3 py-1 rounded-full mb-4">
                    ?? PROPERTI
                </div>

                <!-- TITLE -->
                <h2 class="text-3xl font-bold leading-tight">
                    Temukan Properti <br>
                    <span class="text-blue-300">Impian Anda</span>
                </h2>

                <!-- DESC -->
                <p class="mt-3 text-sm text-blue-100 max-w-xs">
                    Pilihan rumah, apartemen, dan tanah lokasi strategis dengan harga terbaik.
                </p>

                <!-- BUTTON -->
                <a href="{{ route('properti') }}"
                    class="mt-5 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Properti ?
                </a>
            </div>
        </div>
    </div>


    <!-- CARD 2 -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px]">

        <img src="{{ asset('images/thumbnail_kendaraan.png') }}"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/40 to-transparent"></div>

        <div class="relative z-10 p-6 flex flex-col h-full justify-between text-white">

            <div>
                <div class="inline-flex items-center gap-2 text-xs border border-white/40 px-3 py-1 rounded-full mb-4">
                    ?? MOBIL BEKAS
                </div>

                <h2 class="text-3xl font-bold leading-tight">
                    Mobil Bekas <br>
                    <span class="text-blue-300">Berkualitas</span>
                </h2>

                <p class="mt-3 text-sm text-blue-100 max-w-xs">
                    Unit terpilih, kondisi prima, dan harga bersaing dengan garansi.
                </p>

                <a href="{{ route('autoshow') }}"
                    class="mt-5 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Mobil ?
                </a>
            </div>
        </div>
    </div>


    <!-- CARD 3 -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 h-[480px]">

        <img src="{{ asset('images/thumbnail_pinjam_dana.png') }}"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/40 to-transparent"></div>

        <div class="relative z-10 p-6 flex flex-col h-full justify-between text-white">

            <div>
                <div class="inline-flex items-center gap-2 text-xs border border-white/40 px-3 py-1 rounded-full mb-4">
                    ?? PEMBIAYAAN
                </div>

                <h2 class="text-3xl font-bold leading-tight">
                    Pinjam Dana <br>
                    <span class="text-blue-300">Jaminan BPKB</span><br>
                    <span class="text-white">Motor & Mobil</span>
                </h2>

                <p class="mt-3 text-sm text-blue-100 max-w-xs">
                    Cair cepat, bunga kompetitif, dan proses mudah & aman.
                </p>

                <a href="{{ route('pinjaman.index') }}"
                    class="mt-5 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                    Ajukan Sekarang ?
                </a>
            </div>
        </div>
    </div>

</div>
</div>

<div data-aos="fade-up" class="container -mt-20 mx-auto p-6">
    <!-- CAROUSEL -->
    @if($carousels->count())
<div data-aos="fade-up" id="default-carousel" class="relative mx-auto my-8 w-full max-w-6xl overflow-hidden rounded-2xl" data-carousel="slide">
    <!-- WRAPPER -->
    <div class="relative h-[450px] w-full overflow-hidden rounded-2xl">
        @foreach($carousels as $index => $item)
        <div class="{{ $index == 0 ? '' : 'hidden' }} absolute inset-0 h-full w-full overflow-hidden rounded-2xl duration-700 ease-in-out"
            data-carousel-item>
            @if($item->link_url)
                <a href="{{ $item->link_url }}"
                    class="absolute inset-0 bg-black/20 block overflow-hidden rounded-2xl"
                    aria-label="{{ $item->title ?: 'Buka halaman carousel' }}">
                    <img src="{{ asset('storage/'.$item->image) }}"
                        class="absolute inset-0 block h-full w-full rounded-2xl object-cover object-center" alt="{{ $item->title ?: 'Carousel' }}">
                </a>
            @else
                <img src="{{ asset('storage/'.$item->image) }}"
                    class="absolute inset-0 block h-full w-full rounded-2xl object-contain object-center" alt="{{ $item->title ?: 'Carousel' }}">
            @endif
        </div>
        @endforeach
    </div>

    <!-- INDICATOR -->
    <div class="absolute z-30 flex -translate-x-1/2 bottom-3 left-1/2 space-x-2 sm:bottom-4 sm:space-x-3">
        @foreach($carousels as $index => $item)
        <button type="button"
            class="h-2.5 w-2.5 rounded-full bg-white/70 ring-1 ring-black/10 sm:h-3 sm:w-3"
            aria-current="{{ $index == 0 ? 'true' : 'false' }}"
            data-carousel-slide-to="{{ $index }}">
        </button>
        @endforeach
    </div>

    <!-- PREV BUTTON -->
    <button type="button"
        class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-2 sm:px-4 cursor-pointer group"
        data-carousel-prev>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/30 text-sm text-white group-hover:bg-black/50 sm:h-10 sm:w-10 sm:text-base">
            ?
        </span>
    </button>

    <!-- NEXT BUTTON -->
    <button type="button"
        class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-2 sm:px-4 cursor-pointer group"
        data-carousel-next>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/30 text-sm text-white group-hover:bg-black/50 sm:h-10 sm:w-10 sm:text-base">
            ?
        </span>
    </button>
</div>
@endif

@if($recommendedListings->count())
<section data-aos="fade-up" class="mb-14">
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Produk Rekomendasi</h2>
        </div>

        <a href="{{ route('search') }}" class="text-blue-600 font-semibold hover:underline">
            Lihat Semua
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recommendedListings as $listing)
            <div data-aos="fade-up"
                class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
                onclick="window.location='{{ route('listing.show',$listing->id) }}'">

                <div class="relative">
                    <div class="relative h-48 overflow-hidden">
                        @forelse($listing->images as $index => $img)
                            <img src="{{ asset('storage/'.$img->image) }}"
                                class="card-slide absolute inset-0 w-full h-full object-cover transition duration-300 {{ $index == 0 ? '' : 'hidden' }}">
                        @empty
                            <img src="https://via.placeholder.com/300x200" class="w-full h-full object-cover">
                        @endforelse
                    </div>

                    <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                        {{ $listing->category->name ?? 'Rekomendasi' }}
                    </span>

                    @if($listing->images->count() > 1)
                        <button onclick="event.stopPropagation(); prevSlide(this)"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
                            ?
                        </button>

                        <button onclick="event.stopPropagation(); nextSlide(this)"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
                            ?
                        </button>
                    @endif
                </div>

                <div class="p-4">
                    <p class="mb-1 text-xs font-bold uppercase text-blue-600">
                        {{ $listing->product_code ?: $listing->buildProductCode() }}
                    </p>
                    <h3 class="font-semibold line-clamp-1">{{ $listing->title }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-1">{{ $listing->location }}</p>
                    <div class="mt-1">
                        <x-listing-price :listing="$listing" />
                    </div>

                    @php
                        $phone = "62895347042844";
                        $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
                    @endphp

                    <a href="https://wa.me/{{ $phone }}?text={{ $message }}" onclick="event.stopPropagation()"
                        target="_blank" class="block mt-3 text-center bg-green-500 text-white py-2 rounded-lg">
                        WhatsApp
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

    <!-- MARKETPLACE -->
    <h1 data-aos="fade-up" class="text-3xl font-bold mb-8 text-gray-800">
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

        <div data-aos="fade-up" class="mb-10">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $category->name }}
                </h2>

                <div>
                    <button type="button" class="tab-btn px-5 py-2 rounded-full text-sm font-medium transition 
                        bg-blue-600 text-white shadow-md hover:bg-blue-700"><a href="{{ route($route) }}">Lihat Semua ?</a>
                    </button>
                </div>
            </div>

            <!-- LISTING -->
            <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @forelse($category->listings->take(4) as $listing)
                <div data-aos="fade-up" class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
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
                            ?
                        </button>

                        <button onclick="event.stopPropagation(); nextSlide(this)"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
                            ?
                        </button>

                    </div>

                    <!-- ================= CONTENT ================= -->
                    <div class="p-4">

                        <h3 class="font-semibold line-clamp-1">
                            {{ $listing->title }}
                        </h3>

                        <p class="mt-1 text-xs font-bold uppercase text-blue-600">
                            {{ $listing->product_code ?: $listing->buildProductCode() }}
                        </p>

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

<section data-aos="fade-up" class="mt-12 mb-16">
    <a href="{{ route('ads.guide') }}"
        class="group relative block w-full overflow-hidden rounded-xl bg-gray-900 text-left shadow hover:shadow-xl transition">
        <img src="{{ asset('images/thumbnail_properti.png') }}"
            alt="Daftar sebagai agen"
            class="h-60 md:h-72 w-full object-cover transition duration-700 group-hover:scale-105">

        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/10"></div>

        <div class="absolute inset-0 flex items-center">
            <div class="max-w-xl px-6 md:px-10 text-white">
                <p class="mb-3 inline-flex rounded-full border border-white/40 px-4 py-1 text-xs font-semibold uppercase">
                    Peluang Mitra Arsantara
                </p>

                <h2 class="text-3xl md:text-5xl font-extrabold leading-tight">
                    Daftar Sebagai Agen
                </h2>

                <p class="mt-3 max-w-md text-sm md:text-base text-white/85">
                    Jangkau lebih banyak calon pembeli dan pasarkan listing terbaik Anda bersama Arsantara.
                </p>

                <span class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md transition group-hover:bg-blue-700">
                    Mulai Bergabung Sekarang
                </span>
            </div>
        </div>
    </a>
</section>

<section>
    <div class="mt-16" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-6">Berita Terbaru</h2>

        <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($posts as $post)
            <div data-aos="fade-up" class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
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
    <div data-aos="fade-up" class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
            Testimoni Mereka Tentang Arsantara
        </h2>
        <button type="button" class="tab-btn px-5 py-2 rounded-full text-sm font-medium transition 
                bg-blue-600 text-white shadow-md hover:bg-blue-700"><a href="{{ route('testimoni.index') }}">Lihat Semua ?</a>
        </button>
    </div>

    <div class="relative" data-aos="fade-up">

        <!-- LEFT -->
        <button onclick="scrollTesti(-1)"
            class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow w-10 h-10 rounded-full items-center justify-center">
            ?
        </button>

        <!-- SLIDER -->
        <div id="testi-slider"
            class="flex gap-6 overflow-x-auto scroll-smooth pb-4 no-scrollbar">

            @foreach($testimonials as $item)

            @php
                $initial = strtoupper(substr($item->name,0,1));
            @endphp

            <div data-aos="fade-up" class="min-w-[320px] max-w-[320px] bg-white rounded-2xl p-6 shadow hover:shadow-lg transition relative">

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

                    <!-- ? RATING -->
                    <div class="text-yellow-400 mb-3">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $item->rating)
                                ?
                            @else
                                ?
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
            ?
        </button>

    </div>
</section>
</div>

@endsection
<script>
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




