<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($listings as $listing)
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

        <img 
            src="{{ $listing->images->count() 
                    ? asset('storage/'.$listing->images->first()->image) 
                    : 'https://via.placeholder.com/300x200' }}"
            class="w-full h-48 object-cover">

        <div class="p-4">

            <h3 class="font-bold text-lg text-gray-800">
                {{ $listing->title }}
            </h3>

            <p class="text-gray-500 text-sm">
                {{ $listing->location }}
            </p>

            <p class="text-blue-600 font-bold mt-2">
                Rp {{ number_format($listing->price) }}
            </p>

            @if($listing->category_id == 3 && $listing->carDetail)
            <p class="text-sm text-gray-600 mt-1">
                {{ $listing->carDetail->brand }} • 
                {{ $listing->carDetail->year }} • 
                {{ $listing->carDetail->transmission }}
            </p>
            @endif

            @if($listing->category_id == 4 && $listing->motorcycleDetail)
            <p class="text-sm text-gray-600 mt-1">
                {{ $listing->motorcycleDetail->brand }} • 
                {{ $listing->motorcycleDetail->year }}
            </p>
            @endif

            <a href="{{ route('listing.show',$listing->id) }}"
               class="block mt-3 text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Detail
            </a>

        </div>
    </div>
    @empty
    <p class="text-gray-400">Data tidak ditemukan</p>
    @endforelse

    {{-- PAGINATION --}}
    <div class="mt-6" id="pagination-links">
        {{ $listings->links() }}
    </div>
</div>