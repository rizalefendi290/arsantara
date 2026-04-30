@extends('layouts.app')

@section('content')
<section class="relative min-h-[460px] flex items-center bg-cover bg-center"
    style="background-image:url('{{ asset('images/hero.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/95 via-blue-900/75 to-blue-700/30"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-6 py-24 text-white">
        <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-blue-200">Testimoni Arsantara</p>
        <h1 class="max-w-3xl text-4xl md:text-6xl font-extrabold leading-tight">Cerita pengguna yang sudah mencoba Arsantara.</h1>
        <p class="mt-4 max-w-2xl text-lg text-blue-100">Baca pengalaman pembeli, pemilik, agen, dan pengguna yang menemukan properti atau kendaraan melalui Arsantara.</p>
    </div>
</section>

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase text-blue-600">Ulasan Pelanggan</p>
                <h2 class="text-3xl font-bold text-gray-900">Apa kata mereka?</h2>
            </div>
            <a href="{{ route('testimoni.create') }}"
                class="inline-flex w-fit rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow hover:bg-blue-700">
                Buat Ulasan
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="columns-1 gap-6 space-y-6 md:columns-2 lg:columns-3">
            @forelse($testimonials as $item)
                <article class="break-inside-avoid rounded-2xl border border-gray-100 bg-white p-6 shadow hover:shadow-lg transition">
                    <div class="mb-4 flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= $item->rating ? '★' : '☆' }}</span>
                        @endfor
                    </div>

                    <p class="text-gray-700 leading-7">
                        "{{ $item->message }}"
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t pt-4">
                        <img src="{{ $item->photo ? asset('storage/'.$item->photo) : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=2563eb&color=fff' }}"
                            class="h-12 w-12 rounded-full object-cover"
                            alt="{{ $item->name }}">

                        <div>
                            <p class="font-bold text-gray-900">{{ $item->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->job ?: 'Pengguna Arsantara' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                    Belum ada ulasan.
                </div>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            {{ $testimonials->links() }}
        </div>
    </div>
</main>
@endsection
