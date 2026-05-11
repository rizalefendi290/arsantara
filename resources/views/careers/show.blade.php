@extends('layouts.app')

@section('content')
@php
    $descriptionLines = collect(preg_split('/\r\n|\r|\n/', (string) $jobVacancy->description))
        ->map(fn ($line) => trim($line, " \t\n\r\0\x0B-"))
        ->filter();
    $requirementLines = collect(preg_split('/\r\n|\r|\n/', (string) $jobVacancy->requirements))
        ->map(fn ($line) => trim($line, " \t\n\r\0\x0B-"))
        ->filter();
@endphp

<main class="bg-[#f5f7fb] pb-16">
    <section class="relative min-h-[260px] overflow-hidden bg-slate-900">
        <img src="{{ asset('images/hero.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-35">
        <div class="absolute inset-0 bg-[#061735]/70"></div>
    </section>

    <section class="relative z-10 -mt-28 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-[2rem] bg-white px-6 py-10 shadow-xl shadow-slate-900/10 sm:px-10 lg:px-12">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col gap-8 border-b border-slate-200 pb-9 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a href="{{ route('careers.index') }}" class="mb-7 inline-flex text-sm font-bold text-blue-700 hover:text-blue-800">
                        &larr; Kembali ke semua lowongan
                    </a>
                    <h1 class="text-3xl font-black uppercase tracking-normal text-slate-950 sm:text-4xl">
                        {{ $jobVacancy->title }}
                    </h1>

                    <div class="mt-8 flex flex-wrap gap-6 text-lg font-semibold text-slate-950">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 items-center justify-center text-slate-600">
                                <svg class="h-10 w-10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.5a7 7 0 0 0-7 7c0 5.2 7 11.8 7 11.8s7-6.6 7-11.8a7 7 0 0 0-7-7Zm0 9.5a2.7 2.7 0 1 1 0-5.4 2.7 2.7 0 0 1 0 5.4Z" />
                                </svg>
                            </span>
                            <span>{{ $jobVacancy->location ?: 'Fleksibel' }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 items-center justify-center text-slate-600">
                                <svg class="h-9 w-9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9 5V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1h3.5A2.5 2.5 0 0 1 21 7.5V10H3V7.5A2.5 2.5 0 0 1 5.5 5H9Zm2 0h2V4h-2v1ZM3 11.5h18v7A2.5 2.5 0 0 1 18.5 21h-13A2.5 2.5 0 0 1 3 18.5v-7Z" />
                                </svg>
                            </span>
                            <span>{{ $jobVacancy->employment_type ?: 'Staff' }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('careers.apply', $jobVacancy) }}"
                    class="inline-flex min-h-[68px] w-full items-center justify-center rounded-2xl bg-red-700 px-8 text-center text-2xl font-black text-white shadow-[6px_6px_0_rgba(185,28,28,0.18)] transition hover:bg-red-800 sm:w-auto sm:min-w-[360px]">
                    APPLY FOR JOB
                </a>
            </div>

            <div class="grid gap-12 pt-10 lg:grid-cols-[1fr_360px]">
                <div class="space-y-10">
                    <section>
                        <h2 class="text-xl font-black uppercase text-slate-950">Deskripsi Pekerjaan</h2>
                        @if($descriptionLines->isNotEmpty())
                            <ul class="mt-5 space-y-5 text-lg leading-9 text-slate-700">
                                @foreach($descriptionLines as $line)
                                    <li class="flex gap-4">
                                        <span class="mt-4 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                        <span>{{ $line }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-5 text-lg leading-8 text-slate-700">Informasi deskripsi pekerjaan akan diperbarui oleh admin.</p>
                        @endif
                    </section>

                    <section>
                        <h2 class="text-xl font-black uppercase text-slate-950">Kualifikasi</h2>
                        @if($requirementLines->isNotEmpty())
                            <ul class="mt-5 space-y-5 text-lg leading-9 text-slate-700">
                                @foreach($requirementLines as $line)
                                    <li class="flex gap-4">
                                        <span class="mt-4 h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                        <span>{{ $line }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-5 text-lg leading-8 text-slate-700">Kualifikasi akan diinformasikan lebih lanjut.</p>
                        @endif
                    </section>
                </div>

                <aside class="space-y-10">
                    <h2 class="text-xl font-black text-slate-700">Job Overview</h2>
                    <div class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                        <div class="flex gap-5 py-6">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center text-slate-600">
                                <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M8 3v4M16 3v4M4 9h16M5 5h14v16H5z" />
                                    <path d="M8 13h3M13 13h3M8 17h3M13 17h3" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-lg font-black uppercase text-slate-700">Batas Waktu</p>
                                <p class="mt-1 text-slate-600">{{ $jobVacancy->deadline ? $jobVacancy->deadline->translatedFormat('l, d M Y') : 'Dibuka sampai terpenuhi' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-5 py-6">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center text-slate-600">
                                <svg class="h-12 w-12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.5a7 7 0 0 0-7 7c0 5.2 7 11.8 7 11.8s7-6.6 7-11.8a7 7 0 0 0-7-7Zm0 9.5a2.7 2.7 0 1 1 0-5.4 2.7 2.7 0 0 1 0 5.4Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-lg font-black uppercase text-slate-700">Lokasi</p>
                                <p class="mt-1 uppercase text-slate-600">{{ $jobVacancy->location ?: 'Fleksibel' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-5 py-6">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center text-slate-600">
                                <svg class="h-11 w-11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9 5V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1h3.5A2.5 2.5 0 0 1 21 7.5V10H3V7.5A2.5 2.5 0 0 1 5.5 5H9Zm2 0h2V4h-2v1ZM3 11.5h18v7A2.5 2.5 0 0 1 18.5 21h-13A2.5 2.5 0 0 1 3 18.5v-7Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-lg font-black uppercase text-slate-700">Tipe Job</p>
                                <p class="mt-1 text-slate-600">{{ $jobVacancy->employment_type ?: 'Staff' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($recommendedVacancies->isNotEmpty())
                        <section class="border-t border-slate-200 pt-8">
                            <div class="flex items-center justify-between gap-4">
                                <h2 class="text-lg font-black uppercase text-slate-950">Lowongan Lainnya</h2>
                                <a href="{{ route('careers.index', ['show' => 1]) }}" class="shrink-0 text-sm font-semibold text-slate-500 hover:text-blue-700">
                                    View All Vacancy &gt;&gt;
                                </a>
                            </div>

                            <div class="mt-6 space-y-7">
                                @foreach($recommendedVacancies as $recommended)
                                    <a href="{{ route('careers.show', $recommended) }}" class="block group">
                                        <h3 class="text-base font-black uppercase leading-7 text-blue-700 group-hover:text-blue-900">
                                            {{ $recommended->title }}
                                        </h3>
                                        <p class="mt-1 text-base font-semibold uppercase text-slate-950">
                                            {{ $recommended->location ?: 'Fleksibel' }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
