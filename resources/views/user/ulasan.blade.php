@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800">
            Apa Kata Mereka?
        </h1>
        <p class="text-gray-500 mt-2">
            Ulasan dari pelanggan Arsantara
        </p>
    </div>

    <a href="{{ route('ulasan.create') }}"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg">
        + Buat Ulasan
    </a>

    <!-- ================= SLIDER ================= -->
    <div class="relative">

        <!-- BUTTON LEFT -->
        <button onclick="scrollTesti(-1)"
            class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-3 rounded-full">
            ❮
        </button>

        <!-- CONTENT -->
        <div id="testi-slider"
            class="flex gap-6 overflow-x-auto scroll-smooth pb-4">

            @forelse($testimonials as $item)
            <div class="min-w-[300px] max-w-[300px] bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">

                <!-- PROFILE -->
                <div class="flex items-center gap-3 mb-3">
                    <img 
                        src="{{ $item->photo 
                            ? asset('storage/'.$item->photo) 
                            : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                        class="w-14 h-14 rounded-full object-cover">

                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $item->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $item->job }}
                        </p>
                    </div>
                </div>

                <!-- ⭐ RATING -->
                <div class="mb-2 text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $item->rating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>

                <!-- MESSAGE -->
                <p class="text-gray-600 text-sm leading-relaxed">
                    "{{ $item->message }}"
                </p>

            </div>
            @empty
            <p class="text-gray-400">Belum ada ulasan</p>
            @endforelse

        </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
        @endif

        <!-- BUTTON RIGHT -->
        <button onclick="scrollTesti(1)"
            class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-3 rounded-full">
            ❯
        </button>

    </div>

</div>

<script>
function scrollTesti(direction){
    const container = document.getElementById('testi-slider');
    container.scrollBy({
        left: direction * 320,
        behavior: 'smooth'
    });
}
</script>

@endsection