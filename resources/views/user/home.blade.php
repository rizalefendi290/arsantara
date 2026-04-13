@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    
    @if($carousels->count())
    <div id="indicators-carousel" class="relative w-full mb-8" data-carousel="slide">

        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">

            @foreach($carousels as $index => $item)
            <div class="{{ $index == 0 ? '' : 'hidden' }}" data-carousel-item>
                <img src="{{ asset('storage/'.$item->image) }}"
                    class="w-full h-full object-cover">
            </div>
            @endforeach

        </div>

    </div>
    @endif

    <h1 class="text-3xl font-bold mb-8">Marketplace</h1>

    @foreach($categories as $category)

        <div class="mb-10">

            <!-- Judul kategori -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">
                    {{ $category->name }}
                </h2>

                <a href="{{ route('home',['category'=>$category->id]) }}"
                   class="text-blue-500 text-sm">
                   Lihat Semua →
                </a>
            </div>

            <!-- Listing -->
            <div class="grid grid-cols-4 gap-6">

                @forelse($category->listings->take(4) as $listing)
                <div class="bg-white shadow rounded overflow-hidden">

                    <img 
                        src="{{ $listing->images->count() 
                                ? asset('storage/'.$listing->images->first()->image) 
                                : 'https://via.placeholder.com/300x200' }}"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="font-bold">
                            {{ $listing->title }}
                        </h3>

                        <p class="text-gray-500 text-sm">
                            {{ $listing->location }}
                        </p>

                        <p class="text-blue-600 font-bold mt-1">
                            Rp {{ number_format($listing->price) }}
                        </p>

                        <a href="{{ route('listing.show',$listing->id) }}"
                           class="block mt-2 text-center bg-blue-500 text-white py-1 rounded">
                           Detail
                        </a>
                    </div>

                </div>
                @empty
                <p class="text-gray-400">Belum ada data</p>
                @endforelse

            </div>

        </div>

    @endforeach

</div>
@endsection