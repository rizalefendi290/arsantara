@extends('layouts.app')

@section('meta')
@php
    $heroImage = $post->images->count()
        ? asset('storage/'.$post->images->first()->image)
        : asset('images/hero.png');
    $description = \Illuminate\Support\Str::limit(strip_tags($post->content), 155);
@endphp
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ route('post.show', $post->id) }}">
<meta property="og:type" content="article">
<meta property="og:image" content="{{ $heroImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $heroImage }}">
@endsection

@section('content')
@php
    $images = $post->images->map(fn ($image) => asset('storage/'.$image->image))->values();
    if ($images->isEmpty()) {
        $images = collect([asset('images/hero.png')]);
    }

    $shareUrl = route('post.show', $post->id);
    $shareText = rawurlencode($post->title.' '.$shareUrl);
    $readingMinutes = max(1, ceil(str_word_count(strip_tags($post->content)) / 200));
@endphp

<article class="bg-white text-slate-950">
    <section class="relative min-h-[520px] overflow-hidden bg-slate-950">
        <div id="heroTrack" class="absolute inset-0">
            @foreach($images as $index => $image)
                <img src="{{ $image }}"
                    class="hero-slide absolute inset-0 h-full w-full object-cover transition duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                    alt="{{ $post->title }}">
            @endforeach
        </div>

        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/65 to-slate-950/20"></div>

        <div class="relative z-10 mx-auto flex min-h-[520px] max-w-7xl flex-col justify-end px-6 pb-14 pt-28 text-white">
            <a href="{{ url()->previous() }}"
                class="mb-8 inline-flex w-fit items-center gap-2 rounded bg-white/10 px-4 py-2 text-sm font-semibold ring-1 ring-white/20 backdrop-blur hover:bg-white/20">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Kembali
            </a>

            <div class="max-w-4xl">
                <div class="mb-4 flex flex-wrap items-center gap-3 text-sm text-blue-100">
                    <span class="rounded bg-blue-600 px-3 py-1 font-semibold uppercase tracking-wide text-white">Berita</span>
                    <span>{{ $post->created_at->translatedFormat('d M Y') }}</span>
                    <span>{{ $readingMinutes }} menit baca</span>
                    @if($post->source_name)
                        <span>{{ $post->source_name }}</span>
                    @endif
                </div>

                <h1 class="text-4xl font-extrabold leading-tight md:text-6xl">
                    {{ $post->title }}
                </h1>

                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-slate-200">
                    {{ $description }}
                </p>
            </div>
        </div>

        @if($images->count() > 1)
            <button type="button" onclick="prevHero()"
                class="absolute left-4 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/15 p-3 text-white backdrop-blur hover:bg-white/25 md:block">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button type="button" onclick="nextHero()"
                class="absolute right-4 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/15 p-3 text-white backdrop-blur hover:bg-white/25 md:block">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        @endif
    </section>

    <main class="bg-gradient-to-b from-blue-50 via-white to-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-10 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div class="min-w-0">
                <div class="mb-8 rounded-xl border border-slate-100 bg-white p-5 shadow-xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase text-blue-600">Bagikan Artikel</p>
                            <p class="text-sm text-slate-500">Kirim berita ini ke WhatsApp atau salin link.</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener"
                                class="rounded bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}" target="_blank" rel="noopener"
                                class="rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                                Facebook
                            </a>
                            <button type="button" onclick="copyArticleLink(this)"
                                class="rounded bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                                Salin Link
                            </button>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-white p-6 shadow-sm md:p-10">
                    <div class="prose-content mx-auto max-w-3xl text-lg leading-9 text-slate-700">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @if($post->source_url)
                        <div class="mx-auto mt-8 max-w-3xl rounded-lg bg-blue-50 p-4">
                            <p class="text-sm font-semibold text-blue-900">Sumber artikel</p>
                            <a href="{{ $post->source_url }}" target="_blank" rel="noopener"
                                class="mt-1 inline-block text-sm font-semibold text-blue-700 hover:underline">
                                {{ $post->source_name ?: 'Baca sumber asli' }}
                            </a>
                        </div>
                    @endif
                </div>

                @if($post->images->count() > 1)
                    <section class="mt-10">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-slate-950">Galeri Berita</h2>
                            <p class="text-sm text-slate-500">{{ $post->images->count() }} foto</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                            @foreach($images as $index => $image)
                                <button type="button" onclick="openImageModal({{ $index }})"
                                    class="group overflow-hidden rounded-lg bg-slate-100">
                                    <img src="{{ $image }}"
                                        class="h-40 w-full object-cover transition duration-500 group-hover:scale-105"
                                        alt="{{ $post->title }}">
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($related->count())
                    <section class="mt-12">
                        <h2 class="mb-5 text-2xl font-bold text-slate-950">Berita Terkait</h2>
                        <div class="grid gap-6 md:grid-cols-2">
                            @foreach($related as $item)
                                <a href="{{ route('post.show',$item->id) }}"
                                    class="group overflow-hidden rounded-xl border border-slate-100 bg-white shadow hover:shadow-lg transition">
                                    <img src="{{ $item->images->count() ? asset('storage/'.$item->images->first()->image) : asset('images/hero.png') }}"
                                        class="h-52 w-full object-cover transition duration-500 group-hover:scale-105"
                                        alt="{{ $item->title }}">
                                    <div class="p-5">
                                        <p class="text-xs font-semibold uppercase text-blue-600">{{ $item->created_at->format('d M Y') }}</p>
                                        <h3 class="mt-2 line-clamp-2 font-bold text-slate-950 group-hover:text-blue-700">
                                            {{ $item->title }}
                                        </h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-950">Berita Terbaru</h2>
                    <div class="mt-4 space-y-4">
                        @forelse($latest as $item)
                            <a href="{{ route('post.show', $item->id) }}" class="group flex gap-3">
                                <img src="{{ $item->images->count() ? asset('storage/'.$item->images->first()->image) : asset('images/logo.png') }}"
                                    class="h-20 w-24 shrink-0 rounded object-cover"
                                    alt="{{ $item->title }}">
                                <div>
                                    <p class="text-xs text-slate-500">{{ $item->created_at->format('d M Y') }}</p>
                                    <h3 class="mt-1 line-clamp-2 text-sm font-bold text-slate-900 group-hover:text-blue-700">
                                        {{ $item->title }}
                                    </h3>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada berita terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>
</article>

<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 p-4">
    <button type="button" onclick="closeImageModal()"
        class="absolute right-4 top-4 rounded bg-white/10 px-3 py-2 text-white hover:bg-white/20">
        Tutup
    </button>
    <button type="button" onclick="prevImageModal()"
        class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
    <img id="modalImage" class="max-h-[88vh] max-w-[92vw] rounded object-contain shadow-2xl" alt="{{ $post->title }}">
    <button type="button" onclick="nextImageModal()"
        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
</div>

<script>
const articleImages = @json($images->values());
let heroIndex = 0;
let modalIndex = 0;
const heroSlides = document.querySelectorAll('.hero-slide');

function showHero(index) {
    heroSlides.forEach(function(image, slideIndex) {
        image.classList.toggle('opacity-100', slideIndex === index);
        image.classList.toggle('opacity-0', slideIndex !== index);
    });
}

function nextHero() {
    heroIndex = (heroIndex + 1) % heroSlides.length;
    showHero(heroIndex);
}

function prevHero() {
    heroIndex = (heroIndex - 1 + heroSlides.length) % heroSlides.length;
    showHero(heroIndex);
}

function copyArticleLink(button) {
    const url = @json($shareUrl);
    navigator.clipboard.writeText(url).then(function() {
        button.textContent = 'Link Tersalin';
        setTimeout(function() {
            button.textContent = 'Salin Link';
        }, 1600);
    });
}

function openImageModal(index) {
    modalIndex = index;
    document.getElementById('modalImage').src = articleImages[modalIndex];
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function nextImageModal() {
    modalIndex = (modalIndex + 1) % articleImages.length;
    document.getElementById('modalImage').src = articleImages[modalIndex];
}

function prevImageModal() {
    modalIndex = (modalIndex - 1 + articleImages.length) % articleImages.length;
    document.getElementById('modalImage').src = articleImages[modalIndex];
}

document.addEventListener('keydown', function(event) {
    const modal = document.getElementById('imageModal');
    if (!modal.classList.contains('hidden')) {
        if (event.key === 'Escape') closeImageModal();
        if (event.key === 'ArrowRight') nextImageModal();
        if (event.key === 'ArrowLeft') prevImageModal();
    }
});
</script>
@endsection
