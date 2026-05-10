@props(['listing'])

<div class="group bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer border border-gray-100"
    onclick="window.location='{{ route('listing.show',$listing->id) }}'">

    <div class="relative">
        @php $images = $listing->images; @endphp

        <div class="relative h-32 overflow-hidden bg-gray-100 sm:h-40 md:h-48">
            @forelse($images as $index => $img)
                <img src="{{ asset('storage/'.$img->image) }}"
                    class="card-slide absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105 {{ $index == 0 ? '' : 'hidden' }}"
                    alt="{{ $listing->title }}">
            @empty
                <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain p-8" alt="{{ $listing->title }}">
            @endforelse
        </div>

        @if($images->count() > 1)
            <button type="button" onclick="event.stopPropagation(); prevSlide(this)"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/85 hover:bg-white w-7 h-7 rounded-full shadow text-gray-700 sm:h-8 sm:w-8">
                ‹
            </button>

            <button type="button" onclick="event.stopPropagation(); nextSlide(this)"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/85 hover:bg-white w-7 h-7 rounded-full shadow text-gray-700 sm:h-8 sm:w-8">
                ›
            </button>
        @endif

        <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] px-2 py-1 rounded sm:text-xs">
            {{ $listing->category->name ?? 'Listing' }}
        </span>

        @if($listing->propertyDetail && $listing->propertyDetail->is_kpr)
            <span class="absolute top-2 right-2 bg-green-500 text-white text-[10px] px-2 py-1 rounded sm:text-xs">
                KPR
            </span>
        @endif
    </div>

    <div class="p-3 sm:p-4">
        <p class="mb-1 text-[10px] font-bold uppercase text-blue-600 sm:text-xs">
            {{ $listing->product_code ?: $listing->buildProductCode() }}
        </p>
        <h3 class="text-sm font-semibold text-gray-800 line-clamp-1 sm:text-base">{{ $listing->title }}</h3>
        <p class="text-gray-500 text-xs line-clamp-1 sm:text-sm">{{ $listing->location }}</p>

        <div class="mt-1">
            <x-listing-price :listing="$listing" />
        </div>

        @if($listing->propertyDetail)
            <p class="text-xs text-gray-600 mt-1 line-clamp-1 sm:text-sm">
                {{ $listing->propertyDetail->land_area ?? '-' }} m2
                @if($listing->propertyDetail->certificate)
                    • {{ $listing->propertyDetail->certificate }}
                @endif
            </p>
        @elseif($listing->carDetail)
            <p class="text-xs text-gray-600 mt-1 line-clamp-1 sm:text-sm">
                {{ $listing->carDetail->brand ?? '-' }} • {{ $listing->carDetail->year ?? '-' }} • {{ ucfirst($listing->carDetail->transmission ?? '-') }}
            </p>
        @elseif($listing->motorcycleDetail)
            <p class="text-xs text-gray-600 mt-1 line-clamp-1 sm:text-sm">
                {{ $listing->motorcycleDetail->brand ?? '-' }} • {{ $listing->motorcycleDetail->year ?? '-' }} • {{ ucfirst($listing->motorcycleDetail->transmission ?? '-') }}
            </p>
        @endif

        @php
            $phone = "62895347042844";
            $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
        @endphp

        <a href="https://wa.me/{{ $phone }}?text={{ $message }}"
            onclick="event.stopPropagation()"
            target="_blank"
            class="block mt-3 text-center bg-green-500 hover:bg-green-600 text-white py-1.5 rounded-lg text-sm font-semibold transition sm:py-2 sm:text-base">
            WhatsApp
        </a>
    </div>
</div>
