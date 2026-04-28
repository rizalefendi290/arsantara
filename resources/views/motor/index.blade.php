@extends('layouts.app')

@section('content')

<div data-aos="fade-up" class="container mx-auto p-6">

    <!-- TITLE -->
    <h1 data-aos="fade-up" class="text-2xl font-bold mb-6">Daftar Motor</h1>

    <!-- GRID -->
    <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($listings as $listing)
        <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition overflow-hidden">

            <!-- IMAGE -->
            <img 
                src="{{ $listing->images->count() 
                        ? asset('storage/'.$listing->images->first()->image) 
                        : 'https://via.placeholder.com/300x200' }}"
                class="w-full h-48 object-cover">

            <!-- CONTENT -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 line-clamp-1">
                    {{ $listing->title }}
                </h3>

                <!-- DETAIL MOTOR -->
                <p class="text-gray-500 text-sm">
                    {{ $listing->motorcycle->brand ?? '-' }} - 
                    {{ $listing->motorcycle->model ?? '-' }}
                </p>

                <p class="text-gray-500 text-sm">
                    Tahun: {{ $listing->motorcycle->year ?? '-' }}
                </p>

                <p class="text-gray-500 text-sm">
                    Mesin: {{ $listing->motorcycle->engine ?? '-' }} cc
                </p>

                <p class="text-gray-500 text-sm">
                    {{ $listing->location }}
                </p>

                <p class="text-blue-600 font-bold mt-1">
                    Rp {{ number_format($listing->price) }}
                </p>

                <!-- BUTTON -->
                <a href="{{ route('listing.show',$listing->id) }}"
                   class="block mt-3 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                   Detail
                </a>
            </div>

        </div>
        @empty
        <p class="text-gray-400">Belum ada data motor</p>
        @endforelse

    </div>

    <!-- PAGINATION -->
    <div data-aos="fade-up" class="mt-6">
        {{ $listings->links() }}
    </div>

</div>

@endsection