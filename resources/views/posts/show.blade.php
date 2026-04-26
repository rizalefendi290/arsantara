@extends('layouts.app')

@section('content')

<div class="w-full">

    <!-- ================= HERO SLIDER ================= -->
    <div class="relative w-full h-[300px] md:h-[500px] overflow-hidden">

        @php
            $images = $post->images;
        @endphp

        @if($images->count())
            @foreach($images as $index => $img)
            <img src="{{ asset('storage/'.$img->image) }}"
                 class="hero-slide absolute inset-0 w-full h-full object-cover transition duration-500 {{ $index == 0 ? 'block' : 'hidden' }}">
            @endforeach
        @else
            <!-- fallback -->
            <img src="https://via.placeholder.com/1200x500"
                 class="w-full h-full object-cover">
        @endif

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40 flex items-end">
            <div class="p-6 md:p-10 text-white max-w-4xl">

                <h1 class="text-2xl md:text-4xl font-bold">
                    {{ $post->title }}
                </h1>

                <p class="text-sm mt-2 text-gray-200">
                    {{ $post->created_at->format('d M Y') }}
                    @if($post->source_name)
                        • {{ $post->source_name }}
                    @endif
                </p>

            </div>
        </div>

        <!-- BUTTON -->
        @if($images->count() > 1)
        <button onclick="prevHero()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl">❮</button>
        <button onclick="nextHero()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl">❯</button>
        @endif

    </div>


    <!-- ================= CONTENT ================= -->
    <div class="max-w-5xl mx-auto p-6">

        <!-- SHARE -->
        @php
            $url = urlencode(request()->fullUrl());
            $text = urlencode($post->title);
        @endphp

        <a href="https://wa.me/?text={{ $text }}%20{{ $url }}"
           class="inline-block mb-6 bg-green-500 text-white px-4 py-2 rounded-lg">
           Share WhatsApp
        </a>

        <!-- ISI -->
        <div class="text-lg text-gray-700 leading-relaxed space-y-4">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- SOURCE -->
        @if($post->source_url)
        <a href="{{ $post->source_url }}" target="_blank"
           class="block mt-6 text-blue-600 font-semibold">
           🔗 Baca sumber asli →
        </a>
        @endif


        <!-- ================= GALLERY ================= -->
        @if($images->count() > 1)
        <div class="mt-10">
            <h2 class="font-bold text-xl mb-4">Galeri</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($images as $img)
                <img src="{{ asset('storage/'.$img->image) }}"
                     class="w-full h-40 object-cover rounded-lg">
                @endforeach
            </div>
        </div>
        @endif


        <!-- ================= RELATED ================= -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Berita Terkait</h2>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($related as $item)
                <a href="{{ route('post.show',$item->id) }}"
                   class="bg-white rounded-xl shadow hover:shadow-lg overflow-hidden">

                    <img 
                        src="{{ $item->images->count() 
                            ? asset('storage/'.$item->images->first()->image) 
                            : 'https://via.placeholder.com/300x200' }}"
                        class="w-full h-48 object-cover"
                    >

                    <div class="p-3">
                        <h3 class="font-semibold line-clamp-2">
                            {{ $item->title }}
                        </h3>
                    </div>

                </a>
                @endforeach
            </div>
        </div>

    </div>

</div>

<script>
let heroIndex = 0;
const heroSlides = document.querySelectorAll('.hero-slide');

function showHero(i){
    if(heroSlides.length === 0) return;

    heroSlides.forEach((img, index)=>{
        img.classList.add('hidden');
        if(index === i){
            img.classList.remove('hidden');
        }
    });
}

function nextHero(){
    if(heroSlides.length === 0) return;
    heroIndex = (heroIndex + 1) % heroSlides.length;
    showHero(heroIndex);
}

function prevHero(){
    if(heroSlides.length === 0) return;
    heroIndex = (heroIndex - 1 + heroSlides.length) % heroSlides.length;
    showHero(heroIndex);
}
</script>

@endsection