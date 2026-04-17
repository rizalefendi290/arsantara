@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="bg-blue-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold mb-4">
            Arsantara Properti
        </h1>
        <p class="text-lg text-blue-100">
            Temukan Mobil & Motor terbaik dengan harga terbaik
        </p>
    </div>
</section>

<div class="container mx-auto p-6">


    {{-- FILTER --}}
    <form id="filterForm" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-4 gap-4">

        <input type="text" name="keyword" placeholder="Cari properti..."
            class="border p-2 rounded">

        <input type="number" name="min_price" placeholder="Harga Min"
            class="border p-2 rounded">

        <input type="number" name="max_price" placeholder="Harga Max"
            class="border p-2 rounded">

        <select name="certificate" class="border p-2 rounded">
            <option value="">Semua Sertifikat</option>
            <option value="SHM">SHM</option>
            <option value="HGB">HGB</option>
        </select>

        {{-- FILTER RUMAH / TANAH --}}
        <div class="col-span-4 flex gap-2">
            <button type="button" onclick="setCategory('')" class="px-4 py-2 border rounded">Semua</button>
            <button type="button" onclick="setCategory('1')" class="px-4 py-2 border rounded">Rumah</button>
            <button type="button" onclick="setCategory('2')" class="px-4 py-2 border rounded">Tanah</button>

            <input type="hidden" name="category" id="categoryInput">
        </div>

    </form>

    {{-- LISTING --}}
    <div id="listing-container">
        @include('properti.partials.list', ['listings'=>$listings])
    </div>

</div>

<script>
let debounceTimer;
let controller;

function loadData(url = null){

    let form = document.getElementById('filterForm');
    let formData = new FormData(form);
    let params = new URLSearchParams(formData).toString();

    let fetchUrl = url ? url + '&' + params : "{{ route('properti.filter') }}?" + params;

    if(controller) controller.abort();
    controller = new AbortController();

    document.getElementById('listing-container').innerHTML = `
    <div class="text-center py-10 text-gray-500">Loading...</div>
    `;

    fetch(fetchUrl, { signal: controller.signal })
        .then(res => res.text())
        .then(data => {
            document.getElementById('listing-container').innerHTML = data;
        });
}

// debounce
document.getElementById('filterForm').addEventListener('input', function(){
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadData(), 500);
});

// pagination
document.addEventListener('click', function(e){
    if(e.target.closest('#pagination-links a')){
        e.preventDefault();
        loadData(e.target.closest('a').href);
    }
});

// kategori
function setCategory(val){
    document.getElementById('categoryInput').value = val;
    loadData();
}
</script>
@endsection