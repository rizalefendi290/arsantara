@extends('layouts.app')

@section('content')
<main class="bg-slate-50 px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('careers.index') }}" class="mb-6 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700">
            &larr; Kembali ke semua lowongan
        </a>

        <article class="overflow-hidden rounded-2xl border bg-white shadow-sm">
            <div class="bg-gradient-to-r from-blue-700 to-slate-900 px-6 py-10 text-white sm:px-10">
                <p class="text-sm font-bold uppercase tracking-wide text-blue-100">Detail Lowongan</p>
                <h1 class="mt-3 text-3xl font-black leading-tight sm:text-5xl">{{ $jobVacancy->title }}</h1>

                <div class="mt-6 flex flex-wrap gap-3 text-sm font-semibold">
                    <span class="rounded-full bg-white/15 px-4 py-2">{{ $jobVacancy->employment_type ?: 'Staff' }}</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">{{ $jobVacancy->location ?: 'Fleksibel' }}</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">
                        {{ $jobVacancy->deadline ? 'Deadline '.$jobVacancy->deadline->translatedFormat('d M Y') : 'Dibuka sampai terpenuhi' }}
                    </span>
                </div>
            </div>

            <div class="space-y-8 px-6 py-8 sm:px-10">
                <section>
                    <h2 class="text-xl font-bold text-gray-950">Deskripsi Pekerjaan</h2>
                    <div class="mt-3 whitespace-pre-line leading-7 text-gray-700">{{ $jobVacancy->description ?: 'Informasi deskripsi pekerjaan akan diperbarui oleh admin.' }}</div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-950">Kualifikasi</h2>
                    <div class="mt-3 whitespace-pre-line leading-7 text-gray-700">{{ $jobVacancy->requirements ?: 'Kualifikasi akan diinformasikan lebih lanjut.' }}</div>
                </section>

                @if($jobVacancy->apply_url)
                    <a href="{{ $jobVacancy->apply_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex rounded-xl bg-blue-600 px-6 py-3 font-bold text-white transition hover:bg-blue-700">
                        Lamar Posisi Ini
                    </a>
                @else
                    <div class="rounded-xl bg-blue-50 p-4 text-sm font-semibold text-blue-800">
                        Untuk melamar posisi ini, silakan hubungi admin Arsantara.
                    </div>
                @endif
            </div>
        </article>
    </div>
</main>
@endsection
