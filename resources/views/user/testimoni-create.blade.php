@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">
    @php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Ulasan',
            'title' => 'Buat Ulasan Anda',
            'text' => 'Bagikan pengalaman terbaik Anda di Arsantara agar pelanggan lain semakin percaya.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Pengalaman Properti',
            'title' => 'Ceritakan proses Anda menemukan pilihan terbaik.',
            'text' => 'Ulasan Anda membantu pengguna lain membaca gambaran layanan dengan lebih jernih.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Pengalaman Kendaraan',
            'title' => 'Bagikan cerita saat membandingkan listing.',
            'text' => 'Masukan Anda menjadi sinyal kepercayaan untuk calon pengguna berikutnya.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[420px]" inner-height="min-h-[420px]" content-width="max-w-3xl" />

    <div class="container mx-auto px-6 pb-16">
        <div class="mx-auto max-w-3xl overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-xl">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-8 py-8 text-white">
                <h2 class="text-2xl font-semibold">Tulis Ulasan</h2>
                <p class="mt-2 text-slate-100">Isi formulir berikut dan bantu orang lain menemukan pilihan terbaik.</p>
            </div>

            <div class="px-8 py-8">
                <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" name="name" placeholder="Nama"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Ulasan</label>
                        <textarea name="message" rows="5" placeholder="Tulis ulasan..."
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Rating</label>
                        <select name="rating"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Foto Profil (Opsional)</label>
                        <input type="file" name="photo"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none" />
                    </div>

                    <button type="submit"
                        class="w-full rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 transition hover:bg-blue-700">Kirim
                        Ulasan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection