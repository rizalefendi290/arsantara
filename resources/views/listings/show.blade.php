@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

<div class="bg-white shadow-lg rounded-xl p-6">

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- ================= GALLERY ================= -->
    <div class="w-full">

        <!-- SLIDER -->
        <div class="relative w-full">

            <!-- WRAPPER -->
            <div id="slider-wrapper" class="relative w-full h-64 sm:h-80 md:h-96 overflow-hidden rounded-xl">

                @foreach($listing->images as $index => $img)
                <img 
                    src="{{ asset('storage/'.$img->image) }}"
                    class="slide-img absolute inset-0 w-full h-full object-cover rounded cursor-pointer transition duration-300 {{ $index == 0 ? 'block' : 'hidden' }}"
                    data-index="{{ $index }}"
                    onclick="openModal({{ $index }})">
                @endforeach

            </div>

            <!-- PREV -->
            <button onclick="prevSlide()"
                class="absolute top-1/2 left-2 sm:left-4 -translate-y-1/2 z-50">
                <span class="w-8 h-8 sm:w-10 sm:h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center">
                    ❮
                </span>
            </button>

            <!-- NEXT -->
            <button onclick="nextSlide()"
                class="absolute top-1/2 right-2 sm:right-4 -translate-y-1/2 z-50">
                <span class="w-8 h-8 sm:w-10 sm:h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center">
                    ❯
                </span>
            </button>

        </div>

        <!-- ================= THUMBNAIL ================= -->
        <div class="flex gap-2 mt-4 overflow-x-auto pb-2">

            @foreach($listing->images as $index => $img)
            <img 
                src="{{ asset('storage/'.$img->image) }}"
                class="thumb flex-shrink-0 h-16 w-24 sm:h-20 sm:w-28 object-cover rounded cursor-pointer border-2 transition 
                {{ $index == 0 ? 'border-blue-500' : 'border-transparent' }}"
                onclick="goToSlide({{ $index }})">
            @endforeach

        </div>

    </div>


<!-- ================= MODAL ================= -->
<div id="imageModal" class="fixed inset-0 bg-black/90 hidden z-50 flex items-center justify-center">

    <!-- WRAPPER BIAR CENTER -->
    <div class="relative flex items-center justify-center">

        <!-- CLOSE -->
        <button onclick="closeModal()" 
            class="absolute -top-10 right-0 text-white text-3xl font-bold">
            ✕
        </button>

        <!-- PREV -->
        <button onclick="prevModal()" 
            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 text-white text-4xl bg-black/40 hover:bg-black/70 px-3 py-2 rounded-full">
            ❮
        </button>

        <!-- IMAGE -->
        <img id="modalImage" 
            class="max-h-[85vh] max-w-[90vw] rounded shadow-lg transition duration-200 cursor-zoom-in select-none"
            draggable="false">

        <!-- NEXT -->
        <button onclick="nextModal()" 
            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 text-white text-4xl bg-black/40 hover:bg-black/70 px-3 py-2 rounded-full">
            ❯
        </button>

    </div>

</div>

    <!-- ================= DETAIL ================= -->
    <div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
            {{ $listing->title }}

            <div class="flex items-center gap-2 mt-2">
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                    Tersedia
                </span>
            </div>
        </h1>

        <p class="text-gray-500 mt-2 flex items-center gap-2">
            📍 {{ $listing->location }}
        </p>

        <p class="text-3xl text-blue-600 font-bold my-4">
            Rp {{ number_format($listing->price) }}
        </p>

        <!-- ================= INFO BOX ================= -->
        <div class="bg-gray-50 rounded-lg p-4 border">

        <!-- RUMAH -->
        @if($listing->category_id == 1 && $listing->property)
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm mt-4">

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                🏠 <p class="font-semibold">{{ $listing->property->house_type ?? '-' }}</p>
                <span class="text-xs text-gray-500">Tipe</span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                📐 <p class="font-semibold">{{ $listing->property->land_area ?? '-' }} m²</p>
                <span class="text-xs text-gray-500">Luas Tanah</span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                🏢 <p class="font-semibold">{{ $listing->property->building_area ?? '-' }} m²</p>
                <span class="text-xs text-gray-500">Luas Bangunan</span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                🛏️ <p class="font-semibold">{{ $listing->property->bedrooms ?? '-' }}</p>
                <span class="text-xs text-gray-500">Kamar</span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                🚿 <p class="font-semibold">{{ $listing->property->bathrooms ?? '-' }}</p>
                <span class="text-xs text-gray-500">Kamar Mandi</span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-center">
                📄 <p class="font-semibold">{{ $listing->property->certificate ?? '-' }}</p>
                <span class="text-xs text-gray-500">Sertifikat</span>
            </div>

        </div>

        <div class="mt-4">
            <h3 class="font-semibold">Fasilitas</h3>
            @php
                $facilities = explode(',', $listing->property->facilities ?? '');
            @endphp

            <div class="flex flex-wrap gap-2 mt-2">
                @forelse($facilities as $f)
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                        {{ trim($f) }}
                    </span>
                @empty
                    <span class="text-gray-400">Tidak ada fasilitas</span>
                @endforelse
            </div>
        </div>
        @endif

        <!-- TANAH -->
        @if($listing->category_id == 2 && $listing->property)
        <div class="grid grid-cols-2 gap-3 text-sm">
            <p>📐 Luas Tanah: <b>{{ $listing->property->land_area ?? '-' }} m²</b></p>
            <p>📄 Sertifikat: <b>{{ $listing->property->certificate ?? '-' }}</b></p>
        </div>
        @endif

        <!-- MOBIL -->
        @if($listing->category_id == 3 && $listing->car)
        <div class="grid grid-cols-2 gap-3 text-sm">
            <p>🚗 Merk: <b>{{ $listing->car->brand ?? '-' }}</b></p>
            <p>📌 Model: <b>{{ $listing->car->model ?? '-' }}</b></p>
            <p>📅 Tahun: <b>{{ $listing->car->year ?? '-' }}</b></p>
            <p>⚙️ Mesin: <b>{{ $listing->car->engine ?? '-' }} cc</b></p>
            <p>🔁 Transmisi: <b>{{ $listing->car->transmission ?? '-' }}</b></p>
            <p>⛽ Bahan Bakar: <b>{{ $listing->car->fuel_type ?? '-' }}</b></p>
            <p>🛣️ Kilometer: <b>{{ number_format($listing->car->kilometer ?? 0) }} km</b></p>
        </div>
        @endif

        <!-- MOTOR -->
        @if($listing->category_id == 4 && $listing->motorcycle)
        <div class="grid grid-cols-2 gap-3 text-sm">
            <p>🏍️ Merk: <b>{{ $listing->motorcycle->brand ?? '-' }}</b></p>
            <p>📌 Model: <b>{{ $listing->motorcycle->model ?? '-' }}</b></p>
            <p>📅 Tahun: <b>{{ $listing->motorcycle->year ?? '-' }}</b></p>
            <p>⚙️ Mesin: <b>{{ $listing->motorcycle->engine ?? '-' }} cc</b></p>
            <p>🔁 Transmisi: <b>{{ $listing->motorcycle->transmission ?? '-' }}</b></p>
        </div>
        @endif

        </div>

        <!-- DESKRIPSI -->
        <div class="border-t pt-4 mt-6">
            <h2 class="font-semibold mb-2 text-lg">Deskripsi</h2>
            <p id="desc" class="text-gray-700 leading-relaxed line-clamp-4">
                {{ $listing->description }}
            </p>

            <button onclick="toggleDesc()" 
                class="text-blue-600 text-sm mt-2">
                Lihat Selengkapnya
            </button>
        </div>

        <!-- TOMBOL -->
        <div class="mt-6 flex gap-3">
            @php
                $phone = "62895347042844"; // nomor kamu
                $message = urlencode(
                    "Halo Admin Arsantara 👋\n\n".
                    "Saya tertarik dengan:\n".
                    "📌 ".$listing->title."\n".
                    "💰 Rp ".number_format($listing->price)."\n".
                    "📍 ".$listing->location."\n\n".
                    "Apakah masih tersedia?"
                );
            @endphp
            <div class="mt-6 flex gap-3">

                <a href="{{ url()->previous() }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                    ← Kembali
                </a>
                
                <a href="https://wa.me/{{ $phone }}?text={{ $message }}" 
                    target="_blank"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 transition">
                    
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A11.8 11.8 0 0 0 12.01 0C5.38 0 .01 5.37.01 12c0 2.11.55 4.17 1.59 5.99L0 24l6.17-1.62A11.94 11.94 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.2-1.25-6.21-3.48-8.52zM12 21.82c-1.88 0-3.72-.5-5.33-1.45l-.38-.23-3.66.96.98-3.57-.25-.37A9.79 9.79 0 0 1 2.18 12c0-5.41 4.41-9.82 9.82-9.82 2.62 0 5.08 1.02 6.94 2.88A9.77 9.77 0 0 1 21.82 12c0 5.41-4.41 9.82-9.82 9.82zm5.45-7.36c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.49-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.87 1.22 3.07.15.2 2.1 3.2 5.1 4.48.71.31 1.26.49 1.69.63.71.22 1.36.19 1.87.11.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>
                    </svg>

                    Hubungi Penjual
                </a>

            </div>
        </div>

    </div>

</div>
</div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4">Produk Serupa</h2>

        <div class="relative">

            <!-- BUTTON LEFT -->
            <button onclick="scrollSimilar(-1)"
                class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
                ❮
            </button>

            <!-- SLIDER -->
            <div id="similar-slider"
                class="flex gap-4 overflow-x-auto scroll-smooth scrollbar-hide px-1">

                @foreach($similar as $item)
                <div class="min-w-[250px] max-w-[250px] bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                    <img 
                        src="{{ $item->images->count() 
                                ? asset('storage/'.$item->images->first()->image) 
                                : 'https://via.placeholder.com/300x200' }}"
                        class="w-full h-40 object-cover">

                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 line-clamp-1">
                            {{ $item->title }}
                        </h3>

                        <p class="text-gray-500 text-sm">
                            {{ $item->location }}
                        </p>

                        <p class="text-blue-600 font-bold mt-1">
                            Rp {{ number_format($item->price) }}
                        </p>

                        <a href="{{ route('listing.show',$item->id) }}"
                        class="block mt-2 text-center bg-blue-500 hover:bg-blue-600 text-white py-1 rounded transition">
                        Detail
                        </a>
                    </div>

                </div>
                @endforeach

            </div>

            <!-- BUTTON RIGHT -->
            <button onclick="scrollSimilar(1)"
                class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
                ❯
            </button>

        </div>
    </div>
</div>

<script>
function changeImage(el){
    document.getElementById('main-image').src = el.src;
}
let currentIndex = 0;
const slides = document.querySelectorAll('.slide-img');
const thumbs = document.querySelectorAll('.thumb');

function showSlide(index){
    slides.forEach((img, i)=>{
        img.classList.add('hidden');
        thumbs[i].classList.remove('border-blue-500');

        if(i === index){
            img.classList.remove('hidden');
            thumbs[i].classList.add('border-blue-500');
        }
    });

    currentIndex = index;
}

// SLIDER CONTROL
function nextSlide(){
    let index = (currentIndex + 1) % slides.length;
    showSlide(index);
}

function prevSlide(){
    let index = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(index);
}

function goToSlide(index){
    showSlide(index);
}

// ================= MODAL =================
function openModal(index){
    currentIndex = index;
    document.getElementById('imageModal').classList.remove('hidden');
    updateModal();
}

function closeModal(){
    document.getElementById('imageModal').classList.add('hidden');
}

function updateModal(){
    const src = slides[currentIndex].src;
    document.getElementById('modalImage').src = src;
}

function nextModal(){
    currentIndex = (currentIndex + 1) % slides.length;
    updateModal();
    showSlide(currentIndex); // sync ke slider
}

function prevModal(){
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateModal();
    showSlide(currentIndex);
}

// OPTIONAL: keyboard support
document.addEventListener('keydown', function(e){
    if(!document.getElementById('imageModal').classList.contains('hidden')){
        if(e.key === 'ArrowRight') nextModal();
        if(e.key === 'ArrowLeft') prevModal();
        if(e.key === 'Escape') closeModal();
    }
});

let scale = 1;
let isDragging = false;
let startX, startY;
let translateX = 0;
let translateY = 0;

const img = document.getElementById('modalImage');

// APPLY TRANSFORM
function updateTransform(){
    img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
}

// RESET
function resetZoom(){
    scale = 1;
    translateX = 0;
    translateY = 0;
    img.style.cursor = 'zoom-in';
    updateTransform();
}

// CLICK → ZOOM
img.addEventListener('click', function(e){
    if(scale === 1){
        scale = 2;
        img.style.cursor = 'grab';
    } else {
        resetZoom();
    }
    updateTransform();
});

// SCROLL → ZOOM SMOOTH
img.addEventListener('wheel', function(e){
    e.preventDefault();

    let zoomSpeed = 0.1;

    if(e.deltaY < 0){
        scale += zoomSpeed;
    } else {
        scale -= zoomSpeed;
    }

    // LIMIT
    scale = Math.min(Math.max(1, scale), 4);

    if(scale === 1){
        translateX = 0;
        translateY = 0;
        img.style.cursor = 'zoom-in';
    } else {
        img.style.cursor = 'grab';
    }

    updateTransform();
});

// DRAG START
img.addEventListener('mousedown', function(e){
    if(scale <= 1) return;

    isDragging = true;
    startX = e.clientX - translateX;
    startY = e.clientY - translateY;

    img.style.cursor = 'grabbing';
});

// DRAG MOVE
window.addEventListener('mousemove', function(e){
    if(!isDragging) return;

    translateX = e.clientX - startX;
    translateY = e.clientY - startY;

    updateTransform();
});

// DRAG END
window.addEventListener('mouseup', function(){
    isDragging = false;
    if(scale > 1){
        img.style.cursor = 'grab';
    }
});

// DOUBLE CLICK → ZOOM MAX
img.addEventListener('dblclick', function(){
    if(scale < 3){
        scale = 3;
    } else {
        resetZoom();
    }
    updateTransform();
});

// CLOSE MODAL RESET

function scrollSimilar(direction) {
    const container = document.getElementById('similar-slider');
    const scrollAmount = 300;

    container.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}
function closeModal(){
    document.getElementById('imageModal').classList.add('hidden');
    resetZoom();
}

function toggleDesc(){
    const desc = document.getElementById('desc');

    if(desc.classList.contains('line-clamp-4')){
        desc.classList.remove('line-clamp-4');
    } else {
        desc.classList.add('line-clamp-4');
    }
}

</script>

@endsection