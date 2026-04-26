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

    <!-- BUTTON + ALERT -->
    <div class="flex justify-between items-center mb-8">
        <a href="{{ route('testimoni.create') }}"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg">
            + Buat Ulasan
        </a>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <!-- ================= GRID TESTIMONI ================= -->
    <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">

        @forelse($testimonials as $item)
        <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">

            <!-- MESSAGE -->
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
                {{ $item->message }}
            </p>

            <!-- PROFILE -->
            <div class="flex items-center gap-3 mt-4">

                <img 
                    src="{{ $item->photo 
                        ? asset('storage/'.$item->photo) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                    class="w-12 h-12 rounded-full object-cover">

                <div>
                    <p class="font-semibold text-gray-800">
                        {{ strtoupper($item->name) }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $item->job ?? 'UMUM' }}
                    </p>

                    <!-- ⭐ RATING -->
                    <div class="text-yellow-400 text-sm mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $item->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>

                    <!-- DATE -->
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $item->created_at->translatedFormat('d M Y') }} • {{ $item->created_at->diffForHumans() }}
                    </p>
                </div>

            </div>

        </div>
        @empty
        <p class="text-gray-400">Belum ada ulasan</p>
        @endforelse

    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="mt-10 flex justify-center">
        {{ $testimonials->links() }}
    </div>

</div>

@endsection