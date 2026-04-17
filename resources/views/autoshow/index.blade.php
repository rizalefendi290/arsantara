@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="bg-blue-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold mb-4">
            Arsantara Autoshow
        </h1>
        <p class="text-lg text-blue-100">
            Temukan Mobil & Motor terbaik dengan harga terbaik
        </p>
    </div>
</section>

<div class="container mx-auto p-6">

    {{-- ================= FILTER ================= --}}
    <form id="filterForm" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-4 gap-4">

        <input type="text" name="keyword" placeholder="Cari mobil / motor..."
            value="{{ request('keyword') }}"
            class="border p-2 rounded w-full">

        <input type="number" name="min_price" placeholder="Harga Min"
            value="{{ request('min_price') }}"
            class="border p-2 rounded">

        <input type="number" name="max_price" placeholder="Harga Max"
            value="{{ request('max_price') }}"
            class="border p-2 rounded">

        <select name="condition" class="border p-2 rounded">
            <option value="">Semua Kondisi</option>
            <option value="baru" {{ request('condition')=='baru'?'selected':'' }}>Baru</option>
            <option value="bekas" {{ request('condition')=='bekas'?'selected':'' }}>Bekas</option>
        </select>

        <select name="transmission" class="border p-2 rounded col-span-2">
            <option value="">Semua Transmisi</option>
            <option value="manual" {{ request('transmission')=='manual'?'selected':'' }}>Manual</option>
            <option value="matic" {{ request('transmission')=='matic'?'selected':'' }}>Matic</option>
        </select>

        <select name="category" class="border p-2 rounded">
            <option value="">Semua Kendaraan</option>
            <option value="3" {{ request('category') == 3 ? 'selected' : '' }}>Mobil</option>
            <option value="4" {{ request('category') == 4 ? 'selected' : '' }}>Motor</option>
        </select>

        <button class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 col-span-2">
            Filter
        </button>

    </form>

    {{-- ================= LISTING ================= --}}

    <div id="listing-container">
        @include('autoshow.partials.list', ['listings'=>$listings])
    </div>
    

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $listings->links() }}
    </div>

</div>

<script>
let debounceTimer;
let controller;

// 🔥 FUNCTION LOAD DATA
function loadData(url = null) {

    let form = document.getElementById('filterForm');
    let formData = new FormData(form);
    let params = new URLSearchParams(formData).toString();

    let fetchUrl = url ? url + '&' + params : "{{ route('autoshow.filter') }}?" + params;

    // cancel request lama
    if (controller) controller.abort();
    controller = new AbortController();

    // loading
    document.getElementById('listing-container').innerHTML = `
    <div class="text-center py-10 text-gray-500">
        Loading...
    </div>
    `;

    fetch(fetchUrl, { signal: controller.signal })
        .then(res => res.text())
        .then(data => {
            document.getElementById('listing-container').innerHTML = data;
        })
        .catch(err => {
            if (err.name !== 'AbortError') console.error(err);
        });
}

// 🔥 FILTER (DEBOUNCE)
document.getElementById('filterForm').addEventListener('input', function(){

    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        loadData();
    }, 500);

});

// 🔥 PAGINATION CLICK
document.addEventListener('click', function(e){

    if(e.target.closest('#pagination-links a')){
        e.preventDefault();

        let url = e.target.closest('a').href;

        loadData(url);
    }

});

function setCategory(val){
    document.getElementById('categoryInput').value = val;
    loadData(); // langsung trigger AJAX
}
</script>
@endsection