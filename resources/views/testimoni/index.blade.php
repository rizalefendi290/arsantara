@extends('layouts.app')

@section('content')
<main class="overflow-hidden bg-white">
    <section class="relative isolate overflow-hidden bg-gradient-to-b from-sky-50 via-white to-white px-4 pb-14 pt-10 sm:px-6 lg:px-8">
        <img src="{{ asset('images/hero.png') }}" alt="" aria-hidden="true"
            class="absolute inset-y-0 right-0 -z-20 h-full w-full object-cover object-right opacity-30">
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-white via-white/90 to-white/55"></div>
        <div class="absolute right-[-8rem] top-[-7rem] -z-10 h-72 w-72 rounded-full bg-blue-500/20"></div>
        <div class="absolute bottom-[-8rem] left-[-8rem] -z-10 h-64 w-64 rounded-full bg-[#f4c20d]/20"></div>

        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-8 lg:grid-cols-[280px_minmax(0,1fr)_280px]">

                <div class="mx-auto max-w-4xl text-center lg:col-start-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-xs font-black uppercase tracking-wide text-white shadow-lg shadow-blue-500/20 ring-2 ring-[#f4c20d]/80">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M4 4.8C4 3.8 4.8 3 5.8 3h12.4c1 0 1.8.8 1.8 1.8v8.4c0 1-.8 1.8-1.8 1.8h-5.5l-4.8 3.7c-.6.5-1.5 0-1.5-.8V15h-.6c-1 0-1.8-.8-1.8-1.8V4.8Z" />
                        </svg>
                        Ulasan Pelanggan
                    </span>

                    <h1 class="mt-6 text-4xl font-black leading-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Apa kata <span class="relative inline-block text-blue-600">mereka?
                            <span class="absolute -bottom-2 left-1/2 h-2 w-[92%] -translate-x-1/2 rounded-full bg-[#f4c20d]"></span>
                        </span>
                    </h1>

                    <p class="mx-auto mt-6 max-w-3xl text-base font-medium leading-8 text-slate-600 sm:text-lg">
                        Pengalaman nyata dari pelanggan yang telah menemukan properti terbaik di
                        <span class="font-black text-blue-600">Tulungagung</span> bersama
                        <span class="font-black text-blue-600">Arsantara.</span>
                    </p>

                    <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('testimoni.create') }}"
                            class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-black text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            Buat Ulasan
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-auto mt-10 max-w-3xl rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($testimonials as $item)
                    <article class="relative overflow-hidden rounded-[1.75rem] bg-white p-6 shadow-2xl shadow-blue-950/10 ring-1 ring-blue-100/80">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                                <span class="text-5xl font-black leading-none">“</span>
                            </div>

                            <div class="flex gap-1 text-[#f4c20d]">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-6 w-6 {{ $i <= $item->rating ? 'fill-current' : 'fill-none text-slate-300' }}" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="m12 3.5 2.6 5.26 5.8.84-4.2 4.1.99 5.78L12 16.75l-5.19 2.73.99-5.78-4.2-4.1 5.8-.84L12 3.5Z" stroke-linejoin="round" />
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        <p class="mt-7 min-h-[10rem] text-base font-semibold leading-8 text-slate-700">
                            {{ $item->message }}
                        </p>

                        <div class="mt-6 h-px bg-slate-200"></div>

                        <div class="mt-6 flex items-center gap-4">
                            <img src="{{ $item->photo ? asset('storage/'.$item->photo) : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=2563eb&color=fff' }}"
                                class="h-20 w-20 rounded-full object-cover ring-2 ring-blue-100"
                                alt="{{ $item->name }}">

                            <div class="min-w-0">
                                <h2 class="truncate text-xl font-black text-slate-950">{{ $item->name }}</h2>
                                <p class="mt-1 flex items-center gap-2 text-sm font-semibold text-slate-500">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 11.5 12 5l8 6.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6 11v8h12v-8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $item->job ?: 'Pengguna Arsantara' }}
                                </p>
                                <p class="mt-1 flex items-center gap-2 text-sm font-semibold text-slate-500">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14v16H5z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="absolute inset-x-0 bottom-0 h-3 {{ $loop->iteration % 2 === 0 ? 'bg-blue-600' : 'bg-[#f4c20d]' }}"></div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-blue-200 bg-white p-10 text-center text-slate-500">
                        Belum ada ulasan.
                    </div>
                @endforelse
            </div>

            <div class="mt-10 flex justify-center">
                {{ $testimonials->links() }}
            </div>
        </div>
    </section>
</main>
@endsection
