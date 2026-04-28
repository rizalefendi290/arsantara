@extends('layouts.app')

@section('content')

<div data-aos="fade-up" class="container mx-auto p-6">

    <!-- ================= FILTER ================= -->
    <form method="GET" class="bg-white p-6 rounded-2xl shadow mb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Harga -->
            <select name="price" class="border p-2 rounded-lg">
                <option value="">Pilih Harga</option>
                <option value="100000000"> < 100 Juta</option>
                <option value="500000000"> < 500 Juta</option>
                <option value="1000000000"> < 1 Miliar</option>
            </select>

            <!-- Lokasi -->
            <input type="text" name="location"
                value="{{ request('location') }}"
                placeholder="Contoh: Jakarta"
                class="border p-2 rounded-lg">

            <!-- Luas Tanah -->
            <input type="number" name="min_land"
                value="{{ request('min_land') }}"
                placeholder="Min Luas Tanah (m²)"
                class="border p-2 rounded-lg">

            <!-- Sertifikat -->
            <select name="certificate" class="border p-2 rounded-lg">
                <option value="">Semua Sertifikat</option>
                <option value="SHM">SHM</option>
                <option value="SHGB">SHGB</option>
            </select>

        </div>

        <!-- BUTTON -->
        <div class="mt-4 flex gap-3">
            <button class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                Terapkan Filter
            </button>

            <a href="{{ route('tanah.index') }}"
                class="bg-gray-400 text-white px-5 py-2 rounded-lg">
                Reset
            </a>
        </div>

    </form>


    <!-- ================= TITLE ================= -->
    <h1 data-aos="fade-up" class="text-2xl font-bold mb-6">Daftar Tanah</h1>


    <!-- ================= GRID ================= -->
    <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($listings as $listing)
        <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden cursor-pointer group"
            onclick="window.location='{{ route('listing.show',$listing->id) }}'">

            <!-- ================= IMAGE SLIDER ================= -->
            <div class="relative">

                @php $images = $listing->images; @endphp

                <div class="relative h-48 overflow-hidden">

                    @forelse($images as $index => $img)
                    <img src="{{ asset('storage/'.$img->image) }}"
                        class="card-slide absolute inset-0 w-full h-full object-cover transition duration-300 {{ $index == 0 ? '' : 'hidden' }}">
                    @empty
                    <img src="https://via.placeholder.com/300x200"
                        class="w-full h-full object-cover">
                    @endforelse

                </div>

                <!-- NAV BUTTON -->
                <button onclick="event.stopPropagation(); prevSlide(this)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-8 h-8 rounded-full shadow hidden group-hover:flex items-center justify-center">
                    ❮
                </button>

                <button onclick="event.stopPropagation(); nextSlide(this)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-8 h-8 rounded-full shadow hidden group-hover:flex items-center justify-center">
                    ❯
                </button>

                <!-- BADGE (OPSIONAL) -->
                <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                    {{ ucfirst($listing->condition ?? 'Baru') }}
                </div>

            </div>

            <!-- ================= CONTENT ================= -->
            <div class="p-4">

                <h3 class="font-semibold text-gray-800 line-clamp-1 group-hover:text-blue-600 transition">
                    {{ $listing->title }}
                </h3>

                <p class="text-gray-500 text-sm mt-1">
                    📍 {{ $listing->location }}
                </p>

                <!-- DETAIL KHUSUS TANAH -->
                @if($listing->property)
                <div class="text-xs text-gray-500 mt-2 space-y-1">
                    <p>📐 {{ $listing->property->land_area ?? '-' }} m²</p>
                    <p>📄 {{ $listing->property->certificate ?? '-' }}</p>
                </div>
                @endif

                <p class="text-blue-600 font-bold mt-3 text-lg">
                    Rp {{ number_format($listing->price) }}
                </p>

                <!-- ACTION -->
                @php
                    $phone = "62895347042844";
                    $message = urlencode("Halo, saya tertarik dengan ".$listing->title);
                @endphp

                <a href="https://wa.me/{{ $phone }}?text={{ $message }}"
                    onclick="event.stopPropagation()"
                    target="_blank"
                    class="block mt-3 text-center bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm transition">
                    WhatsApp
                </a>

            </div>
        </div>
        @empty
        <p class="text-gray-400">Belum ada data</p>
        @endforelse

    </div>


    <!-- ================= PAGINATION ================= -->
    <div data-aos="fade-up" class="mt-8">
        {{ $listings->links() }}
    </div>

</div>

@endsection

<script>
function nextSlide(btn){
    let container = btn.closest('.relative').querySelectorAll('.card-slide');
    let activeIndex = [...container].findIndex(img => !img.classList.contains('hidden'));

    container[activeIndex].classList.add('hidden');
    let nextIndex = (activeIndex + 1) % container.length;
    container[nextIndex].classList.remove('hidden');
}

function prevSlide(btn){
    let container = btn.closest('.relative').querySelectorAll('.card-slide');
    let activeIndex = [...container].findIndex(img => !img.classList.contains('hidden'));

    container[activeIndex].classList.add('hidden');
    let prevIndex = (activeIndex - 1 + container.length) % container.length;
    container[prevIndex].classList.remove('hidden');
}
</script>