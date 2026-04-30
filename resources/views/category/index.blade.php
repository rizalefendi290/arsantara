@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold mb-6">
        Kategori {{ $title }}
    </h1>

    <!-- FILTER SWITCH -->
    <div class="flex gap-3 mb-6 flex-wrap">
        <a href="{{ route('category.show','mobil') }}"
           class="px-4 py-2 rounded-lg border {{ $slug=='mobil' ? 'bg-blue-600 text-white' : 'bg-white' }}">
           Mobil
        </a>

        <a href="{{ route('category.show','motor') }}"
           class="px-4 py-2 rounded-lg border {{ $slug=='motor' ? 'bg-blue-600 text-white' : 'bg-white' }}">
           Motor
        </a>

        <a href="{{ route('category.show','rumah') }}"
           class="px-4 py-2 rounded-lg border {{ $slug=='rumah' ? 'bg-blue-600 text-white' : 'bg-white' }}">
           Rumah
        </a>

        <a href="{{ route('category.show','tanah') }}"
           class="px-4 py-2 rounded-lg border {{ $slug=='tanah' ? 'bg-blue-600 text-white' : 'bg-white' }}">
           Tanah
        </a>
    </div>

    <!-- LISTING -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($listings as $listing)
        <div class="bg-white border rounded-xl shadow hover:shadow-lg transition overflow-hidden">

            <img 
                src="{{ $listing->images->count() 
                        ? asset('storage/'.$listing->images->first()->image) 
                        : 'https://via.placeholder.com/300x200' }}"
                class="w-full h-48 object-cover">

            <div class="p-4">
                <h3 class="font-bold text-lg">
                    {{ $listing->title }}
                </h3>

                <p class="text-gray-500 text-sm">
                    {{ $listing->location }}
                </p>

                <div class="mt-2">
                    <x-listing-price :listing="$listing" />
                </div>

                <!-- DETAIL SPESIFIK -->
                <div class="text-sm text-gray-600 mt-2">

                    @if($slug == 'mobil' && $listing->car)
                        <p>{{ $listing->car->brand }} • {{ $listing->car->year }}</p>
                    @endif

                    @if($slug == 'motor' && $listing->motorcycle)
                        <p>{{ $listing->motorcycle->brand }} • {{ $listing->motorcycle->year }}</p>
                    @endif

                    @if($slug == 'rumah' && $listing->property)
                        <p>{{ $listing->property->bedrooms }} KT • {{ $listing->property->bathrooms }} KM</p>
                    @endif

                    @if($slug == 'tanah' && $listing->property)
                        <p>{{ $listing->property->land_area }} m²</p>
                    @endif

                </div>

                <a href="{{ route('listing.show',$listing->id) }}"
                   class="block mt-3 text-center bg-blue-600 text-white py-1 rounded hover:bg-blue-700">
                   Detail
                </a>
            </div>

        </div>
        @empty
        <p class="text-gray-400">Belum ada data</p>
        @endforelse

    </div>

    <!-- PAGINATION -->
    <div class="mt-8">
        {{ $listings->links() }}
    </div>

</div>
@endsection
