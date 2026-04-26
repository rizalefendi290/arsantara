<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden cursor-pointer"
    onclick="window.location='{{ route('listing.show',$listing->id) }}'">

    <div class="relative">

        @php $images = $listing->images; @endphp

        <div class="relative h-48 overflow-hidden">
            @forelse($images as $index => $img)
            <img src="{{ asset('storage/'.$img->image) }}"
                class="card-slide absolute inset-0 w-full h-full object-cover {{ $index == 0 ? '' : 'hidden' }}">
            @empty
            <img src="https://via.placeholder.com/300x200"
                class="w-full h-full object-cover">
            @endforelse
        </div>

        <button onclick="event.stopPropagation(); prevSlide(this)"
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
            ❮
        </button>

        <button onclick="event.stopPropagation(); nextSlide(this)"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 w-8 h-8 rounded-full">
            ❯
        </button>

        <!-- BADGE KPR -->
        @if($listing->property && $listing->property->is_kpr)
        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
            KPR
        </span>
        @endif

    </div>

    <div class="p-4">
        <h3 class="font-semibold line-clamp-1">{{ $listing->title }}</h3>

        <p class="text-gray-500 text-sm">{{ $listing->location }}</p>

        <p class="text-blue-600 font-bold mt-1">
            Rp {{ number_format($listing->price) }}
        </p>

        @php
            $phone = "62895347042844";
            $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
        @endphp

        <a href="https://wa.me/{{ $phone }}?text={{ $message }}"
            onclick="event.stopPropagation()"
            target="_blank"
            class="block mt-3 text-center bg-green-500 text-white py-2 rounded-lg">
            WhatsApp
        </a>
    </div>

</div>