@props([
    'slides' => [],
    'height' => 'min-h-[520px]',
    'innerHeight' => 'min-h-[520px]',
    'contentWidth' => 'max-w-2xl',
    'overlay' => 'bg-gradient-to-r from-slate-950 via-slate-950/75 to-slate-950/10',
    'sectionClass' => '',
])

@php
    $fallbackSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Arsantara Management',
            'title' => 'Temukan pilihan terbaik dalam satu platform.',
            'text' => 'Jelajahi properti, kendaraan, dan layanan pembiayaan dengan tampilan yang mudah dipindai.',
        ],
    ];

    $heroSlides = collect($slides ?: $fallbackSlides)
        ->map(function ($slide) use ($fallbackSlides) {
            return array_merge($fallbackSlides[0], $slide);
        })
        ->values();

    $heroId = 'heroCarousel'.substr(md5(json_encode($heroSlides).uniqid('', true)), 0, 10);
    $firstSlide = $heroSlides->first();
@endphp

<section id="{{ $heroId }}" data-slides='@json($heroSlides)'
    {{ $attributes->merge(['class' => "relative {$height} overflow-hidden bg-slate-950 pt-24 text-white {$sectionClass}"]) }}>
    <div class="absolute inset-0">
        @foreach($heroSlides as $index => $slide)
            <img src="{{ $slide['image'] }}"
                alt="{{ $slide['title'] }}"
                class="hero-carousel-slide absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
        @endforeach
        <div class="absolute inset-0 {{ $overlay }}"></div>
    </div>

    <div class="relative z-10 mx-auto grid {{ $innerHeight }} max-w-7xl grid-cols-1 items-center gap-10 px-6 py-16 lg:grid-cols-2">
        <div class="{{ $contentWidth }}">
            @if($heroSlides->count() > 1)
                <div class="mb-6 flex gap-3">
                    @foreach($heroSlides as $index => $slide)
                        <button type="button"
                            class="hero-carousel-dot h-1.5 w-16 rounded-full transition {{ $index === 0 ? 'bg-blue-500' : 'bg-white/40' }}"
                            data-hero-index="{{ $index }}"
                            aria-label="Lihat slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <p class="hero-carousel-label mb-4 inline-flex rounded-full border border-white/30 px-4 py-1 text-sm font-semibold uppercase text-blue-100">
                {{ $firstSlide['label'] }}
            </p>

            <h1 class="hero-carousel-title text-4xl font-extrabold leading-tight md:text-6xl">
                {{ $firstSlide['title'] }}
            </h1>

            <p class="hero-carousel-text mt-5 max-w-xl text-base leading-8 text-slate-200 md:text-lg">
                {{ $firstSlide['text'] }}
            </p>

            @if(trim($slot) !== '')
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    {{ $slot }}
                </div>
            @endif
        </div>

        <div class="hidden lg:block"></div>
    </div>
</section>

@once
    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[id^="heroCarousel"]').forEach(function (hero) {
                const slides = Array.from(hero.querySelectorAll('.hero-carousel-slide'));
                const dots = Array.from(hero.querySelectorAll('.hero-carousel-dot'));
                const label = hero.querySelector('.hero-carousel-label');
                const title = hero.querySelector('.hero-carousel-title');
                const text = hero.querySelector('.hero-carousel-text');
                const slideData = JSON.parse(hero.dataset.slides || '[]');

                if (slides.length <= 1 || slideData.length <= 1) {
                    return;
                }

                let currentIndex = 0;
                let timer = null;

                function showSlide(index) {
                    currentIndex = index;

                    slides.forEach(function (slide, slideIndex) {
                        slide.classList.toggle('opacity-100', slideIndex === index);
                        slide.classList.toggle('opacity-0', slideIndex !== index);
                    });

                    dots.forEach(function (dot, dotIndex) {
                        dot.classList.toggle('bg-blue-500', dotIndex === index);
                        dot.classList.toggle('bg-white/40', dotIndex !== index);
                    });

                    if (slideData[index]) {
                        if (label) label.textContent = slideData[index].label;
                        if (title) title.textContent = slideData[index].title;
                        if (text) text.textContent = slideData[index].text;
                    }
                }

                function startSlider() {
                    window.clearInterval(timer);
                    timer = window.setInterval(function () {
                        showSlide((currentIndex + 1) % slides.length);
                    }, 4500);
                }

                dots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        showSlide(Number(dot.dataset.heroIndex));
                        startSlider();
                    });
                });

                startSlider();
            });
        });
        </script>
    @endpush
@endonce
