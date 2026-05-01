@extends('layouts.app')

@section('meta')
@php
    $shareDescription = \Illuminate\Support\Str::limit(strip_tags($listing->description ?: $listing->location), 155);
    $shareImage = $listing->images->count()
        ? asset('storage/'.$listing->images->first()->image)
        : asset('images/logo.png');
@endphp
<meta property="og:title" content="{{ $listing->title }}">
<meta property="og:description" content="{{ $shareDescription }}">
<meta property="og:url" content="{{ route('listing.show', $listing->id) }}">
<meta property="og:type" content="website">
<meta property="og:image" content="{{ $shareImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $listing->title }}">
<meta name="twitter:description" content="{{ $shareDescription }}">
<meta name="twitter:image" content="{{ $shareImage }}">
@endsection

@section('content')
@php
    $images = $listing->images->map(fn ($image) => asset('storage/'.$image->image))->values();
    if ($images->isEmpty()) {
        $images = collect([asset('images/logo.png')]);
    }

    $phone = '62895347042844';
    $message = urlencode(
        "Halo Admin Arsantara\n\n".
        "Saya tertarik dengan:\n".
        "Kode Produk: ".($listing->product_code ?: $listing->buildProductCode())."\n".
        $listing->title."\n".
        "Rp ".number_format($listing->finalPrice(), 0, ',', '.')."\n".
        $listing->location."\n\n".
        "Apakah masih tersedia?"
    );

    $categoryName = $listing->category->name ?? 'Listing';
    $conditionLabel = $listing->condition ? ucfirst($listing->condition) : '-';
    $primaryFacts = [
        ['label' => 'Kode Produk', 'value' => $listing->product_code ?: $listing->buildProductCode()],
        ['label' => 'Kategori', 'value' => $categoryName],
        ['label' => 'Kondisi', 'value' => $conditionLabel],
        ['label' => 'Status', 'value' => 'Tersedia'],
    ];

    if ($listing->category_id == 1 && $listing->property) {
        $primaryFacts = array_merge([
            ['label' => 'Kamar', 'value' => ($listing->property->bedrooms ?? '-').' KT'],
            ['label' => 'Mandi', 'value' => ($listing->property->bathrooms ?? '-').' KM'],
            ['label' => 'Bangunan', 'value' => ($listing->property->building_area ?? '-').' m2'],
            ['label' => 'Tanah', 'value' => ($listing->property->land_area ?? '-').' m2'],
        ], $primaryFacts);
    } elseif ($listing->category_id == 2 && $listing->property) {
        $primaryFacts = array_merge([
            ['label' => 'Luas Tanah', 'value' => ($listing->property->land_area ?? '-').' m2'],
            ['label' => 'Sertifikat', 'value' => $listing->property->certificate ?? '-'],
        ], $primaryFacts);
    } elseif ($listing->category_id == 3 && $listing->car) {
        $primaryFacts = array_merge([
            ['label' => 'Merk', 'value' => $listing->car->brand ?? '-'],
            ['label' => 'Model', 'value' => $listing->car->model ?? '-'],
            ['label' => 'Tahun', 'value' => $listing->car->year ?? '-'],
            ['label' => 'Transmisi', 'value' => ucfirst($listing->car->transmission ?? '-')],
        ], $primaryFacts);
    } elseif ($listing->category_id == 4 && $listing->motorcycle) {
        $primaryFacts = array_merge([
            ['label' => 'Merk', 'value' => $listing->motorcycle->brand ?? '-'],
            ['label' => 'Model', 'value' => $listing->motorcycle->model ?? '-'],
            ['label' => 'Tahun', 'value' => $listing->motorcycle->year ?? '-'],
            ['label' => 'Transmisi', 'value' => ucfirst($listing->motorcycle->transmission ?? '-')],
        ], $primaryFacts);
    }

    $detailRows = [];
    if ($listing->category_id == 1 && $listing->property) {
        $detailRows = [
            ['Tipe Rumah', $listing->property->house_type ?? '-'],
            ['Luas Tanah', ($listing->property->land_area ?? '-').' m2'],
            ['Luas Bangunan', ($listing->property->building_area ?? '-').' m2'],
            ['Kamar Tidur', $listing->property->bedrooms ?? '-'],
            ['Kamar Mandi', $listing->property->bathrooms ?? '-'],
            ['Jumlah Lantai', $listing->property->floors ?? '-'],
            ['Sertifikat', $listing->property->certificate ?? '-'],
            ['Jenis Rumah', $listing->property->is_kpr ? 'KPR' : 'Non KPR'],
        ];
        $facilities = collect(explode(',', $listing->property->facilities ?? ''))
            ->map(fn ($facility) => trim($facility))
            ->filter()
            ->values();
    } elseif ($listing->category_id == 2 && $listing->property) {
        $detailRows = [
            ['Luas Tanah', ($listing->property->land_area ?? '-').' m2'],
            ['Sertifikat', $listing->property->certificate ?? '-'],
        ];
        $facilities = collect();
    } elseif ($listing->category_id == 3 && $listing->car) {
        $detailRows = [
            ['Merk', $listing->car->brand ?? '-'],
            ['Model', $listing->car->model ?? '-'],
            ['Tahun', $listing->car->year ?? '-'],
            ['Mesin', ($listing->car->engine ?? '-').' cc'],
            ['Transmisi', ucfirst($listing->car->transmission ?? '-')],
            ['Bahan Bakar', ucfirst($listing->car->fuel_type ?? '-')],
            ['Warna', $listing->car->color ?? '-'],
            ['Kilometer', number_format($listing->car->kilometer ?? 0, 0, ',', '.').' km'],
        ];
        $facilities = collect();
    } elseif ($listing->category_id == 4 && $listing->motorcycle) {
        $detailRows = [
            ['Merk', $listing->motorcycle->brand ?? '-'],
            ['Model', $listing->motorcycle->model ?? '-'],
            ['Tahun', $listing->motorcycle->year ?? '-'],
            ['Mesin', ($listing->motorcycle->engine ?? '-').' cc'],
            ['Transmisi', ucfirst($listing->motorcycle->transmission ?? '-')],
        ];
        $facilities = collect();
    } else {
        $facilities = collect();
    }

    $isKprListing = $listing->category_id == 1 && $listing->property && $listing->property->is_kpr;
    $kprPrice = $listing->finalPrice();
    $defaultKprDpPercent = 20;
    $defaultKprDp = (int) round($kprPrice * ($defaultKprDpPercent / 100));
@endphp

<div class="bg-white text-slate-950">
    <section class="relative border-b border-slate-200 bg-slate-100">
        <div class="absolute left-4 top-6 z-20 md:left-8">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 rounded bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow ring-1 ring-slate-200 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back
            </a>
        </div>

        <div class="absolute right-4 top-6 z-20 flex flex-wrap justify-end gap-3 md:right-8">
            <button type="button"
                data-share-button
                data-share-url="{{ route('listing.show', $listing->id) }}"
                data-share-title="{{ $listing->title }}"
                data-share-text="Lihat listing di Arsantara: {{ $listing->title }}"
                class="inline-flex items-center gap-2 rounded bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow ring-1 ring-slate-200 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 8a3 3 0 1 0-2.83-4H15a3 3 0 0 0 .17 1L8.9 8.14a3 3 0 1 0 0 3.72l6.27 3.13A3 3 0 1 0 16 13.2L9.73 10.07A3.1 3.1 0 0 0 9.73 10l6.27-3.13A2.98 2.98 0 0 0 18 8Z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Share
            </button>
        </div>

        <div class="relative h-[430px] overflow-hidden md:h-[520px]">
            <div id="galleryTrack"
                class="flex h-full snap-x snap-mandatory overflow-x-auto scroll-smooth scrollbar-hide"
                onscroll="syncGalleryFromScroll()">
                @foreach($images as $index => $image)
                    <button type="button"
                        onclick="openModal({{ $index }})"
                        class="gallery-slide group relative h-full min-w-full snap-center overflow-hidden bg-slate-200 text-left">
                        <img src="{{ $image }}"
                            alt="{{ $listing->title }}"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]">
                    </button>
                @endforeach
            </div>

            @if($images->count() > 1)
                <button type="button" onclick="scrollGallery(-1)"
                    class="absolute left-4 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/95 p-3 text-slate-900 shadow ring-1 ring-slate-200 hover:bg-white md:block">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <button type="button" onclick="scrollGallery(1)"
                    class="absolute right-4 top-1/2 z-20 hidden -translate-y-1/2 rounded-full bg-white/95 p-3 text-slate-900 shadow ring-1 ring-slate-200 hover:bg-white md:block">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            @endif

            <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2 rounded bg-white/95 px-3 py-2 text-sm font-semibold text-slate-900 shadow">
                <span id="galleryCounter">1</span>
                <span>/</span>
                <span>{{ $images->count() }}</span>
                <span class="ml-1">Foto</span>
            </div>

            @if($images->count() > 1)
                <div class="absolute bottom-5 right-5 z-20 hidden max-w-[34rem] gap-2 overflow-x-auto rounded bg-black/35 p-2 backdrop-blur md:flex">
                    @foreach($images as $index => $image)
                        <button type="button" onclick="goToGallery({{ $index }})"
                            class="gallery-dot h-14 w-20 shrink-0 overflow-hidden rounded border-2 {{ $index === 0 ? 'border-white' : 'border-transparent opacity-70' }}">
                            <img src="{{ $image }}" alt="{{ $listing->title }}" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-pink-600">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-4 px-4 py-2.5 text-sm font-semibold text-white sm:px-6 lg:px-8">
                <span>Must See</span>
                <span class="hidden sm:inline">Curated by Arsantara</span>
                <span class="hidden sm:inline">Verified listing</span>
            </div>
        </div>
    </section>

    <main class="mx-auto grid max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_430px] lg:px-8">
        <article class="min-w-0">
            <div class="mb-6">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="rounded bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-emerald-700">
                        Tersedia
                    </span>
                    <span class="rounded bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                        {{ $categoryName }}
                    </span>
                    @if($listing->is_featured)
                        <span class="rounded bg-pink-100 px-3 py-1 text-xs font-semibold text-pink-700">
                            Rekomendasi
                        </span>
                    @endif
                </div>

                <h1 class="max-w-4xl text-3xl font-bold leading-tight text-slate-950 md:text-4xl">
                    {{ $listing->title }}
                </h1>

                <p class="mt-4 flex items-start gap-2 text-base text-slate-700">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 10.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ $listing->location }}</span>
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 border-y border-slate-200 py-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($primaryFacts as $fact)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $fact['label'] }}</p>
                        <p class="mt-1 text-base font-bold text-slate-950">{{ $fact['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <section class="mt-8">
                <h2 class="text-xl font-bold text-slate-950">Detail Produk</h2>
                <div class="mt-4 grid gap-x-8 gap-y-0 overflow-hidden rounded border border-slate-200 md:grid-cols-2">
                    @forelse($detailRows as $row)
                        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-4 py-3">
                            <span class="text-sm text-slate-500">{{ $row[0] }}</span>
                            <span class="text-right text-sm font-semibold text-slate-900">{{ $row[1] }}</span>
                        </div>
                    @empty
                        <p class="px-4 py-5 text-sm text-slate-500">Detail tambahan belum tersedia.</p>
                    @endforelse
                </div>
            </section>

            @if($facilities->isNotEmpty())
                <section class="mt-8">
                    <h2 class="text-xl font-bold text-slate-950">Fasilitas</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($facilities as $facility)
                            <span class="rounded bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700 ring-1 ring-blue-100">
                                {{ $facility }}
                            </span>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="mt-8">
                <h2 class="text-xl font-bold text-slate-950">Deskripsi</h2>
                <div id="desc" class="mt-4 max-w-3xl whitespace-pre-line text-base leading-8 text-slate-700 line-clamp-6">
                    {{ $listing->description ?: 'Deskripsi belum tersedia.' }}
                </div>
                @if($listing->description)
                    <button type="button" onclick="toggleDesc(this)"
                        class="mt-3 text-sm font-semibold text-blue-700 hover:text-blue-800">
                        Lihat Selengkapnya
                    </button>
                @endif
            </section>
        </article>

        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="rounded border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Harga</p>
                <div class="mt-1">
                    <x-listing-price :listing="$listing" size="large" />
                </div>

                @if($listing->hasDiscount())
                    <p class="mt-2 text-sm text-emerald-700">
                        Hemat {{ $listing->discountPercent() }}% dari harga normal.
                    </p>
                @endif

                @if($isKprListing)
                    <div class="mt-5 rounded border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-bold text-blue-800">Simulasi KPR</p>
                                <p class="mt-1 text-xs leading-5 text-blue-700">Estimasi cicilan berdasarkan harga listing.</p>
                            </div>
                            <span class="rounded bg-white px-2 py-1 text-xs font-bold text-blue-700 shadow-sm">KPR</span>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="kprPrice" class="mb-1 block text-xs font-semibold uppercase text-slate-500">Harga Properti</label>
                                <input id="kprPrice" type="text" value="Rp {{ number_format($kprPrice, 0, ',', '.') }}"
                                    class="w-full rounded border border-blue-100 bg-white px-3 py-2 text-sm font-semibold text-slate-900"
                                    readonly>
                            </div>

                            <div>
                                <div class="mb-1 flex items-center justify-between gap-3">
                                    <label for="kprDpPercent" class="text-xs font-semibold uppercase text-slate-500">DP</label>
                                    <span id="kprDpLabel" class="text-xs font-bold text-blue-700">{{ $defaultKprDpPercent }}%</span>
                                </div>
                                <input id="kprDpPercent" type="range" min="0" max="80" step="1" value="{{ $defaultKprDpPercent }}"
                                    class="w-full accent-blue-600">
                                <p id="kprDpAmount" class="mt-1 text-sm font-semibold text-slate-900">Rp {{ number_format($defaultKprDp, 0, ',', '.') }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="kprRate" class="mb-1 block text-xs font-semibold uppercase text-slate-500">Bunga / Tahun</label>
                                    <div class="flex overflow-hidden rounded border border-blue-100 bg-white">
                                        <input id="kprRate" type="number" min="0" max="30" step="0.1" value="7.5"
                                            class="w-full border-0 px-3 py-2 text-sm font-semibold focus:ring-0">
                                        <span class="flex items-center px-3 text-sm font-semibold text-slate-500">%</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="kprTenor" class="mb-1 block text-xs font-semibold uppercase text-slate-500">Tenor</label>
                                    <select id="kprTenor" class="w-full rounded border border-blue-100 bg-white px-3 py-2 text-sm font-semibold">
                                        <option value="5">5 tahun</option>
                                        <option value="10">10 tahun</option>
                                        <option value="15" selected>15 tahun</option>
                                        <option value="20">20 tahun</option>
                                        <option value="25">25 tahun</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rounded bg-white p-4 shadow-sm">
                                <p class="text-xs font-semibold uppercase text-slate-500">Estimasi Cicilan</p>
                                <p id="kprMonthly" class="mt-1 text-2xl font-extrabold text-blue-700">Rp 0</p>
                                <p id="kprLoan" class="mt-1 text-xs text-slate-500">Plafon pinjaman: Rp 0</p>
                            </div>
                        </div>

                        <p class="mt-3 text-[11px] leading-5 text-slate-500">
                            Simulasi bersifat estimasi. Angka final mengikuti kebijakan bank/lembaga pembiayaan.
                        </p>
                    </div>
                @endif

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                    <a href="tel:+{{ $phone }}"
                        class="inline-flex items-center justify-center gap-2 rounded bg-blue-600 px-5 py-3 text-sm font-bold text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.8a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.8 2.1Z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Call
                    </a>

                    <a href="https://wa.me/{{ $phone }}?text={{ $message }}" target="_blank" rel="noopener"
                        class="inline-flex items-center justify-center gap-2 rounded bg-green-600 px-5 py-3 text-sm font-bold text-white hover:bg-green-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.52 3.48A11.8 11.8 0 0 0 12.01 0C5.38 0 .01 5.37.01 12c0 2.11.55 4.17 1.59 5.99L0 24l6.17-1.62A11.94 11.94 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.2-1.25-6.21-3.48-8.52zM12 21.82c-1.88 0-3.72-.5-5.33-1.45l-.38-.23-3.66.96.98-3.57-.25-.37A9.79 9.79 0 0 1 2.18 12c0-5.41 4.41-9.82 9.82-9.82 2.62 0 5.08 1.02 6.94 2.88A9.77 9.77 0 0 1 21.82 12c0 5.41-4.41 9.82-9.82 9.82zm5.45-7.36c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.49-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.87 1.22 3.07.15.2 2.1 3.2 5.1 4.48.71.31 1.26.49 1.69.63.71.22 1.36.19 1.87.11.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>

                <div class="mt-5 rounded bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-950">Bagikan listing</p>
                    <p class="mt-1 text-sm text-slate-600">Kirim link produk ini ke calon pembeli atau sosial media.</p>
                    <x-listing-share
                        class="mt-3 border-slate-200 bg-white"
                        :url="route('listing.show', $listing->id)"
                        :title="$listing->title"
                        :text="'Lihat listing di Arsantara: '.$listing->title" />
                </div>
            </div>
        </aside>
    </main>

    @if($similar->count())
        <section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-950">Produk Serupa</h2>
                <div class="hidden gap-2 md:flex">
                    <button type="button" onclick="scrollSimilar(-1)"
                        class="rounded border border-slate-200 bg-white p-2 text-slate-700 shadow-sm hover:bg-slate-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button type="button" onclick="scrollSimilar(1)"
                        class="rounded border border-slate-200 bg-white p-2 text-slate-700 shadow-sm hover:bg-slate-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="similar-slider" class="flex gap-4 overflow-x-auto scroll-smooth pb-3 scrollbar-hide">
                @foreach($similar as $item)
                    <a href="{{ route('listing.show', $item->id) }}"
                        class="group min-w-[280px] max-w-[280px] overflow-hidden rounded border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <img src="{{ $item->images->count() ? asset('storage/'.$item->images->first()->image) : asset('images/logo.png') }}"
                            class="h-44 w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                            alt="{{ $item->title }}">

                        <div class="p-4">
                            <h3 class="line-clamp-2 min-h-12 font-bold text-slate-950">{{ $item->title }}</h3>
                            <p class="mt-2 line-clamp-1 text-sm text-slate-500">{{ $item->location }}</p>
                            <div class="mt-3">
                                <x-listing-price :listing="$item" />
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 p-4 sm:p-8">
    <button type="button" onclick="closeModal()"
        class="absolute right-4 top-4 z-10 rounded bg-white/10 px-3 py-2 text-white hover:bg-white/20">
        Tutup
    </button>

    <button type="button" onclick="prevModal()"
        class="absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <img id="modalImage"
        class="max-h-[86vh] max-w-[94vw] rounded object-contain shadow-2xl"
        alt="{{ $listing->title }}">

    <button type="button" onclick="nextModal()"
        class="absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
</div>

<script>
const galleryImages = @json($images->values());
const kprBasePrice = {{ $isKprListing ? $kprPrice : 0 }};
let currentIndex = 0;
let galleryScrollTimer = null;
let modalTouchStartX = 0;
let modalTouchStartY = 0;

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(Math.max(0, Math.round(value)));
}

function calculateKpr() {
    const dpPercentInput = document.getElementById('kprDpPercent');
    const rateInput = document.getElementById('kprRate');
    const tenorInput = document.getElementById('kprTenor');

    if (!dpPercentInput || !rateInput || !tenorInput || !kprBasePrice) return;

    const dpPercent = Number(dpPercentInput.value) || 0;
    const annualRate = Number(rateInput.value) || 0;
    const tenorYears = Number(tenorInput.value) || 1;
    const dpAmount = kprBasePrice * (dpPercent / 100);
    const loanAmount = Math.max(kprBasePrice - dpAmount, 0);
    const months = tenorYears * 12;
    const monthlyRate = annualRate / 100 / 12;

    let monthlyPayment = loanAmount / months;
    if (monthlyRate > 0) {
        monthlyPayment = loanAmount * monthlyRate / (1 - Math.pow(1 + monthlyRate, -months));
    }

    document.getElementById('kprDpLabel').textContent = dpPercent + '%';
    document.getElementById('kprDpAmount').textContent = formatRupiah(dpAmount);
    document.getElementById('kprMonthly').textContent = formatRupiah(monthlyPayment) + ' / bulan';
    document.getElementById('kprLoan').textContent = 'Plafon pinjaman: ' + formatRupiah(loanAmount);
}

document.addEventListener('DOMContentLoaded', function() {
    ['kprDpPercent', 'kprRate', 'kprTenor'].forEach(function(id) {
        const input = document.getElementById(id);
        if (input) input.addEventListener('input', calculateKpr);
    });

    calculateKpr();
});

function setGalleryIndex(index) {
    currentIndex = Math.max(0, Math.min(index, galleryImages.length - 1));

    const counter = document.getElementById('galleryCounter');
    if (counter) {
        counter.textContent = currentIndex + 1;
    }

    document.querySelectorAll('.gallery-dot').forEach(function(dot, dotIndex) {
        const active = dotIndex === currentIndex;
        dot.classList.toggle('border-white', active);
        dot.classList.toggle('border-transparent', !active);
        dot.classList.toggle('opacity-70', !active);
    });
}

function scrollGallery(direction) {
    const track = document.getElementById('galleryTrack');
    if (!track) return;

    const nextIndex = Math.max(0, Math.min(currentIndex + direction, galleryImages.length - 1));
    goToGallery(nextIndex);
}

function goToGallery(index) {
    const track = document.getElementById('galleryTrack');
    const slide = track ? track.children[index] : null;
    if (!slide) return;

    slide.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'center'
    });
    setGalleryIndex(index);
}

function syncGalleryFromScroll() {
    window.clearTimeout(galleryScrollTimer);
    galleryScrollTimer = window.setTimeout(function() {
        const track = document.getElementById('galleryTrack');
        if (!track) return;

        const index = Math.round(track.scrollLeft / Math.max(track.clientWidth, 1));
        setGalleryIndex(index);
    }, 80);
}

function openModal(index) {
    currentIndex = index;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    updateModal();
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function updateModal() {
    document.getElementById('modalImage').src = galleryImages[currentIndex];
}

function nextModal() {
    currentIndex = (currentIndex + 1) % galleryImages.length;
    goToGallery(currentIndex);
    updateModal();
}

function prevModal() {
    currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
    goToGallery(currentIndex);
    updateModal();
}

document.addEventListener('keydown', function(event) {
    const modal = document.getElementById('imageModal');
    if (modal.classList.contains('hidden')) return;

    if (event.key === 'Escape') closeModal();
    if (event.key === 'ArrowRight') nextModal();
    if (event.key === 'ArrowLeft') prevModal();
});

document.getElementById('imageModal').addEventListener('touchstart', function(event) {
    modalTouchStartX = event.touches[0].clientX;
    modalTouchStartY = event.touches[0].clientY;
}, { passive: true });

document.getElementById('imageModal').addEventListener('touchend', function(event) {
    const touch = event.changedTouches[0];
    const diffX = touch.clientX - modalTouchStartX;
    const diffY = touch.clientY - modalTouchStartY;

    if (Math.abs(diffX) < 45 || Math.abs(diffX) < Math.abs(diffY)) return;

    if (diffX < 0) {
        nextModal();
    } else {
        prevModal();
    }
}, { passive: true });

function toggleDesc(button) {
    const desc = document.getElementById('desc');
    desc.classList.toggle('line-clamp-6');
    button.textContent = desc.classList.contains('line-clamp-6') ? 'Lihat Selengkapnya' : 'Tampilkan Lebih Sedikit';
}

function scrollSimilar(direction) {
    const container = document.getElementById('similar-slider');
    if (!container) return;

    container.scrollBy({
        left: direction * 320,
        behavior: 'smooth'
    });
}
</script>
@endsection
