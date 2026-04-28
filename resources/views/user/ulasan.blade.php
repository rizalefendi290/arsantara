@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">
    <section data-aos="fade-up" class="relative overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.16),_transparent_45%)] py-16">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <p class="text-sm uppercase tracking-[0.3em] text-blue-600 mb-4">Ulasan Pengguna</p>
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Apa Kata Mereka?</h1>
                <p class="text-slate-600 text-lg md:text-xl">Ulasan dari pelanggan Arsantara untuk membantu calon pembeli menemukan pengalaman terbaik.</p>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-6 pb-16">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <p class="text-slate-600">Bagikan pengalaman Anda atau lihat cerita pelanggan lain tentang transaksi di Arsantara.</p>
            </div>
            <a href="{{ route('testimoni.create') }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 hover:bg-blue-700 transition">+ Buat Ulasan</a>
        </div>

        @if(session('success'))
        <div class="mb-6 rounded-3xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-800">
            {{ session('success') }}
        </div>
        @endif

        <div class="relative">
            <button onclick="scrollTesti(-1)" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg p-3 rounded-full">
                ❮
            </button>

            <div id="testi-slider" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 no-scrollbar">

                @forelse($testimonials as $item)
                <div data-aos="fade-up" class="min-w-[300px] max-w-[300px] bg-white border border-slate-200 p-6 rounded-[28px] shadow-sm transition hover:shadow-md">

                    <div class="flex items-center gap-3 mb-4">
                        <img 
                            src="{{ $item->photo 
                                ? asset('storage/'.$item->photo) 
                                : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                            class="w-14 h-14 rounded-full object-cover">

                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->job }}</p>
                        </div>
                    </div>

                    <div class="mb-4 text-amber-400 text-lg">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $item->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>

                    <p class="text-slate-600 text-sm leading-relaxed">"{{ $item->message }}"</p>
                </div>
                @empty
                <div class="min-w-[300px] max-w-[300px] rounded-3xl border border-dashed border-slate-300 bg-white p-6 text-center text-slate-500">
                    Belum ada ulasan
                </div>
                @endforelse

            </div>

            <button onclick="scrollTesti(1)" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg p-3 rounded-full">
                ❯
            </button>
        </div>
    </div>
</div>

<script>
function scrollTesti(direction) {
    const container = document.getElementById('testi-slider');
    container.scrollBy({
        left: direction * 320,
        behavior: 'smooth'
    });
}
</script>

@endsection