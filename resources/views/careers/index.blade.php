@extends('layouts.app')

@section('content')
@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Karir Arsantara',
            'title' => 'Bergabung dan tumbuh bersama Arsantara.',
            'text' => 'Temukan peluang kerja terbaru dan jadilah bagian dari ekosistem properti, kendaraan, dan layanan pelanggan yang terus berkembang.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[460px]" inner-height="min-h-[460px]" content-width="max-w-3xl" />

<main class="bg-white px-4 py-14 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-600">Recent Vacancy</p>
                <h1 class="mt-2 text-3xl font-black italic text-gray-950 sm:text-5xl">Lowongan Tersedia</h1>
            </div>
            <p class="max-w-xl text-sm leading-6 text-gray-600">
                Pilih posisi yang sesuai dengan minat dan pengalaman Anda. Klik detail untuk membaca informasi lengkap dan cara melamar.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($vacancies as $vacancy)
                <article class="flex min-h-[360px] flex-col rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-200 hover:shadow-lg">
                    <h2 class="text-xl font-extrabold leading-snug text-gray-950">
                        {{ $vacancy->title }}
                    </h2>

                    <div class="mt-6 space-y-4 text-gray-700">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1M4 8h16v11H4z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span>{{ $vacancy->employment_type ?: 'Staff' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M8 3v3M16 3v3M4 9h16M5 5h14v16H5z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span>{{ $vacancy->deadline ? $vacancy->deadline->translatedFormat('l, d M Y') : 'Dibuka sampai terpenuhi' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.8a7 7 0 0 0-7 7c0 5.2 7 11.4 7 11.4s7-6.2 7-11.4a7 7 0 0 0-7-7Zm0 9.8a2.8 2.8 0 1 1 0-5.6 2.8 2.8 0 0 1 0 5.6Z" />
                                </svg>
                            </span>
                            <span>{{ $vacancy->location ?: 'Fleksibel' }}</span>
                        </div>
                    </div>

                    <div class="mt-auto flex justify-end pt-8">
                        <a href="{{ route('careers.show', $vacancy->id) }}"
                            class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                            Details
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center text-gray-500 md:col-span-2 xl:col-span-3">
                    Belum ada lowongan pekerjaan yang aktif.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $vacancies->links() }}
        </div>
    </div>
</main>
@endsection
