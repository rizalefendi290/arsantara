@extends('layouts.app')

@section('content')
@php
    $phone = '62895347042844';
    $message = urlencode('Halo Admin Arsantara, saya ingin mengajukan pinjaman dana dengan jaminan BPKB. Mohon informasinya.');
    $waUrl = "https://wa.me/{$phone}?text={$message}";

    $slides = [
        [
            'image' => asset('images/thumbnail_pinjam_dana.png'),
            'label' => 'Proses Cepat',
            'title' => 'Ajukan pinjam dana tanpa ribet',
            'text' => 'Konsultasikan kebutuhan dana Anda dan tim kami akan membantu proses awal dengan cepat.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Jaminan BPKB',
            'title' => 'Motor dan mobil bisa diajukan',
            'text' => 'Gunakan dokumen kendaraan sebagai jaminan dengan alur pengajuan yang jelas.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Kebutuhan Fleksibel',
            'title' => 'Dana untuk kebutuhan penting',
            'text' => 'Cocok untuk modal usaha, renovasi, pendidikan, atau kebutuhan mendesak lainnya.',
        ],
        [
            'image' => asset('images/hero.png'),
            'label' => 'Didampingi Admin',
            'title' => 'Langsung terhubung ke WhatsApp',
            'text' => 'Klik ajukan dan lanjutkan percakapan langsung dengan admin Arsantara.',
        ],
    ];
@endphp

<main class="bg-white">
    <section class="relative min-h-screen overflow-hidden bg-slate-950 pt-24 text-white">
        <div class="absolute inset-0">
            @foreach($slides as $index => $slide)
                <img src="{{ $slide['image'] }}"
                    alt="{{ $slide['title'] }}"
                    class="loan-slide absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
            @endforeach
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/75 to-slate-950/10"></div>
        </div>

        <div class="relative z-10 mx-auto grid min-h-[calc(100vh-6rem)] max-w-7xl grid-cols-1 items-center gap-10 px-6 py-16 lg:grid-cols-2">
            <div class="max-w-2xl">
                <div class="mb-6 flex gap-3">
                    @foreach($slides as $index => $slide)
                        <button type="button"
                            onclick="showLoanSlide({{ $index }})"
                            class="loan-dot h-1.5 w-16 rounded-full transition {{ $index === 0 ? 'bg-blue-500' : 'bg-white/40' }}"
                            aria-label="Lihat slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <p id="loanLabel" class="mb-4 inline-flex rounded-full border border-white/30 px-4 py-1 text-sm font-semibold uppercase text-blue-100">
                    {{ $slides[0]['label'] }}
                </p>

                <h1 id="loanTitle" class="text-4xl font-extrabold leading-tight md:text-6xl">
                    {{ $slides[0]['title'] }}
                </h1>

                <p id="loanText" class="mt-5 max-w-xl text-base leading-8 text-slate-200 md:text-lg">
                    {{ $slides[0]['text'] }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $waUrl }}" target="_blank"
                        class="inline-flex items-center justify-center rounded-xl bg-green-500 px-6 py-3 font-bold text-white shadow-lg transition hover:bg-green-600">
                        Ajukan via WhatsApp
                    </a>
                    <a href="#syarat"
                        class="inline-flex items-center justify-center rounded-xl border border-white/30 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                        Lihat Syarat
                    </a>
                </div>
            </div>

            <div class="hidden lg:block"></div>
        </div>
    </section>

    <section id="syarat" class="bg-gradient-to-b from-blue-50 via-white to-white py-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-6 lg:grid-cols-2">
            <div data-aos="fade-up">
                <p class="mb-3 text-sm font-bold uppercase text-blue-600">Dana Tunai Arsantara</p>
                <h2 class="text-3xl font-extrabold leading-tight text-gray-900 md:text-5xl">
                    Pinjam dana dengan jaminan BPKB jadi lebih praktis.
                </h2>
                <p class="mt-5 text-lg leading-8 text-gray-600">
                    Siapkan dokumen kendaraan, kirim kebutuhan Anda ke admin, lalu tim kami akan membantu pengecekan awal dan informasi proses berikutnya.
                </p>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Syarat Mudah</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">KTP, STNK aktif, dan BPKB asli sebagai dokumen utama.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Respon Cepat</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Admin langsung menerima pesan pengajuan melalui WhatsApp.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Motor & Mobil</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Pengajuan dapat dilakukan untuk kendaraan roda dua maupun roda empat.</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-gray-900">Didampingi Tim</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Anda akan dibantu memahami langkah dan kelengkapan pengajuan.</p>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-2xl">
                <img src="{{ asset('images/thumbnail_pinjam_dana.png') }}"
                    alt="Ajukan pinjam dana"
                    class="h-[520px] w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent"></div>
                <div class="absolute bottom-0 p-6 text-white">
                    <p class="text-sm font-semibold uppercase text-blue-200">Pengajuan Cepat</p>
                    <h3 class="mt-2 text-3xl font-extrabold">Ajukan sekarang, lanjutkan di WhatsApp.</h3>
                    <a href="{{ $waUrl }}" target="_blank"
                        class="mt-5 inline-flex rounded-xl bg-green-500 px-5 py-3 font-bold text-white transition hover:bg-green-600">
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
const loanSlides = @json($slides);
let loanSlideIndex = 0;
let loanSlideTimer = null;

function showLoanSlide(index) {
    loanSlideIndex = index;

    document.querySelectorAll('.loan-slide').forEach(function(slide, slideIndex) {
        slide.classList.toggle('opacity-100', slideIndex === index);
        slide.classList.toggle('opacity-0', slideIndex !== index);
    });

    document.querySelectorAll('.loan-dot').forEach(function(dot, dotIndex) {
        dot.classList.toggle('bg-blue-500', dotIndex === index);
        dot.classList.toggle('bg-white/40', dotIndex !== index);
    });

    document.getElementById('loanLabel').textContent = loanSlides[index].label;
    document.getElementById('loanTitle').textContent = loanSlides[index].title;
    document.getElementById('loanText').textContent = loanSlides[index].text;
}

function startLoanSlider() {
    loanSlideTimer = setInterval(function() {
        showLoanSlide((loanSlideIndex + 1) % loanSlides.length);
    }, 4500);
}

startLoanSlider();
</script>
@endsection
