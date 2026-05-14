@props([
    'slides' => [],
    'height' => 'min-h-[520px]',
    'innerHeight' => 'min-h-[520px]',
    'contentWidth' => 'max-w-2xl',
    'overlay' => '',
    'sectionClass' => '',
    'contentClass' => '',
    'pageKey' => null,
])

@php
    $resolvedPageKey = $pageKey ?: (request()->route()?->getName() ?: (request()->path() === '/' ? 'home' : trim(request()->path(), '/')));
    $adminSlides = \App\Models\Carousel::heroSlidesFor($resolvedPageKey);

    $fallbackSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Arsantara Management',
            'title' => 'Temukan pilihan terbaik dalam satu platform.',
            'text' => 'Jelajahi properti, kendaraan, dan layanan pembiayaan dengan tampilan yang mudah dipindai.',
            'label_color' => '#dbeafe',
            'title_color' => '#ffffff',
            'text_color' => '#e2e8f0',
            'buttons' => [],
        ],
    ];

    $heroSlides = collect($adminSlides->isNotEmpty() ? $adminSlides : ($slides ?: $fallbackSlides))
        ->map(function ($slide) use ($fallbackSlides) {
            $slide = array_merge($fallbackSlides[0], $slide);
            $slide['buttons'] = collect($slide['buttons'] ?? [])
                ->take(2)
                ->filter(fn ($button) => filled($button['label'] ?? null) && filled($button['url'] ?? null))
                ->map(fn ($button) => [
                    'label' => $button['label'],
                    'url' => $button['url'],
                    'variant' => ($button['variant'] ?? 'primary') === 'secondary' ? 'secondary' : 'primary',
                ])
                ->values()
                ->all();

            return $slide;
        })
        ->values();

    $heroId = 'heroCarousel'.substr(md5(json_encode($heroSlides).uniqid('', true)), 0, 10);
    $firstSlide = $heroSlides->first();
    $hasDynamicHeroActions = $heroSlides->contains(fn ($slide) => !empty($slide['buttons']));
    $hasStaticHeroActions = trim((string) $slot) !== '';
    $hasHeroActions = $hasDynamicHeroActions || $hasStaticHeroActions;

    $heroActionClass = function (string $variant): string {
        return $variant === 'secondary'
            ? 'inline-flex min-h-12 items-center justify-center rounded-lg border border-white/60 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30'
            : 'inline-flex min-h-12 items-center justify-center rounded-lg bg-[#f3bd12] px-5 py-3 text-sm font-bold text-[#08234c] shadow-lg shadow-slate-950/20 transition hover:bg-[#e5ad05] focus:outline-none focus:ring-4 focus:ring-yellow-200';
    };
@endphp

<section id="{{ $heroId }}" data-slides='@json($heroSlides)'
    {{ $attributes->merge(['class' => "ars-hero-frame relative overflow-hidden bg-slate-950 text-white {$sectionClass}"]) }}>
    <div class="absolute inset-0">
        @foreach($heroSlides as $index => $slide)
            <div class="hero-carousel-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                <img src="{{ $slide['image'] }}"
                    alt=""
                    aria-hidden="true"
                    class="absolute inset-0 h-full w-full scale-105 object-cover opacity-70 blur-xl">
                <img src="{{ $slide['image'] }}"
                    alt="{{ $slide['title'] }}"
                    class="absolute inset-0 h-full w-full object-contain">
            </div>
        @endforeach
        <div class="absolute inset-0 {{ $overlay }}"></div>
    </div>

    <div class="ars-hero-inner relative z-10 mx-auto grid w-full max-w-7xl grid-cols-1 items-center justify-items-start gap-8 px-4 py-8 sm:px-6 sm:py-10 lg:py-12 xl:grid-cols-2">
        <div class="{{ $contentWidth }} {{ $contentClass }} ml-0 mr-auto w-full min-w-0 max-w-[calc(100vw-2rem)] justify-self-start overflow-hidden sm:max-w-[calc(100vw-3rem)] xl:max-w-3xl">
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

            <p class="hero-carousel-label mb-4 inline-flex rounded-full border border-white/30 px-4 py-1 text-sm font-semibold uppercase text-blue-100"
                style="color: {{ $firstSlide['label_color'] }};">
                {{ $firstSlide['label'] }}
            </p>

            <h1 class="hero-carousel-title max-w-[42rem] whitespace-normal break-words text-left text-[1.6rem] font-extrabold leading-tight [overflow-wrap:anywhere] sm:text-3xl md:max-w-[36rem] md:text-4xl xl:max-w-[42rem] xl:text-5xl"
                style="color: {{ $firstSlide['title_color'] }};">
                {{ $firstSlide['title'] }}
            </h1>

            <p class="hero-carousel-text mt-3 max-w-full whitespace-normal break-words text-left text-sm leading-6 text-slate-200 [overflow-wrap:anywhere] sm:max-w-xl sm:text-base md:leading-7"
                style="color: {{ $firstSlide['text_color'] }};">
                {{ $firstSlide['text'] }}
            </p>

            @if($hasHeroActions)
                <div class="hero-carousel-actions mt-8 flex flex-col gap-3 sm:flex-row">
                    @if(!empty($firstSlide['buttons']))
                        @foreach($firstSlide['buttons'] as $button)
                            <a href="{{ $button['url'] }}"
                                @if(Str::startsWith($button['url'], ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif
                                class="{{ $heroActionClass($button['variant'] ?? 'primary') }}">
                                {{ $button['label'] }}
                            </a>
                        @endforeach
                    @elseif($hasStaticHeroActions && !$hasDynamicHeroActions)
                        {{ $slot }}
                    @endif
                </div>
            @endif
        </div>

        <div class="hidden xl:block"></div>
    </div>
</section>

@once
    @push('styles')
        <style>
            .ars-hero-frame {
                height: clamp(340px, 58vh, 520px);
            }

            .ars-hero-inner {
                height: 100%;
                min-height: 0;
            }

            @media (min-width: 640px) {
                .ars-hero-frame {
                    height: clamp(360px, 50vh, 560px);
                }
            }

            @media (min-width: 1024px) {
                .ars-hero-frame {
                    height: clamp(400px, 54vh, 620px);
                }
            }

            @media (min-width: 1280px) {
                .ars-hero-frame {
                    height: clamp(460px, 62vh, 660px);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[id^="heroCarousel"]').forEach(function (hero) {
                const slides = Array.from(hero.querySelectorAll('.hero-carousel-slide'));
                const dots = Array.from(hero.querySelectorAll('.hero-carousel-dot'));
                const label = hero.querySelector('.hero-carousel-label');
                const title = hero.querySelector('.hero-carousel-title');
                const text = hero.querySelector('.hero-carousel-text');
                const actions = hero.querySelector('.hero-carousel-actions');
                const slideData = JSON.parse(hero.dataset.slides || '[]');
                const staticActions = actions ? actions.innerHTML : '';
                const hasDynamicActions = slideData.some(function (slide) {
                    return Array.isArray(slide.buttons) && slide.buttons.length > 0;
                });
                const actionClasses = {
                    primary: 'inline-flex min-h-12 items-center justify-center rounded-lg bg-[#f3bd12] px-5 py-3 text-sm font-bold text-[#08234c] shadow-lg shadow-slate-950/20 transition hover:bg-[#e5ad05] focus:outline-none focus:ring-4 focus:ring-yellow-200',
                    secondary: 'inline-flex min-h-12 items-center justify-center rounded-lg border border-white/60 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30'
                };

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
                        if (label) {
                            label.textContent = slideData[index].label;
                            label.style.color = slideData[index].label_color;
                        }

                        if (title) {
                            title.textContent = slideData[index].title;
                            title.style.color = slideData[index].title_color;
                        }

                        if (text) {
                            text.textContent = slideData[index].text;
                            text.style.color = slideData[index].text_color;
                        }

                        if (actions) {
                            const buttons = Array.isArray(slideData[index].buttons) ? slideData[index].buttons : [];
                            actions.innerHTML = '';

                            if (buttons.length > 0) {
                                buttons.slice(0, 2).forEach(function (button) {
                                    if (!button.label || !button.url) {
                                        return;
                                    }

                                    const link = document.createElement('a');
                                    link.href = button.url;
                                    link.textContent = button.label;
                                    link.className = actionClasses[button.variant] || actionClasses.primary;

                                    if (/^https?:\/\//i.test(button.url)) {
                                        link.target = '_blank';
                                        link.rel = 'noopener noreferrer';
                                    }

                                    actions.appendChild(link);
                                });
                            } else if (!hasDynamicActions) {
                                actions.innerHTML = staticActions;
                            }
                        }
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
