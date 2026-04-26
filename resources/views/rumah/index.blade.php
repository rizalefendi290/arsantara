@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4 md:p-6">

    <!-- ================= FILTER ================= -->
    <div class="bg-white p-4 rounded-xl shadow mb-6">
        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- HARGA -->
                <div>
                    <label class="text-sm font-semibold">Harga</label>
                    <input type="number" name="min_price"
                        value="{{ request('min_price') }}"
                        placeholder="Min"
                        class="w-full border rounded px-3 py-2 mt-1">

                    <input type="number" name="max_price"
                        value="{{ request('max_price') }}"
                        placeholder="Max"
                        class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <!-- LOKASI -->
                <div>
                    <label class="text-sm font-semibold">Lokasi</label>
                    <input type="text" name="location"
                        value="{{ request('location') }}"
                        class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <!-- KAMAR -->
                <div>
                    <label class="text-sm font-semibold">Kamar</label>
                    <select name="bedrooms" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="">Semua</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                    </select>
                </div>

                <!-- SORT -->
                <div>
                    <label class="text-sm font-semibold">Urutkan</label>
                    <select name="sort" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="">Terbaru</option>
                        <option value="low">Harga Termurah</option>
                        <option value="high">Harga Termahal</option>
                    </select>
                </div>

            </div>

            <div class="mt-4 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Terapkan
                </button>

                <a href="{{ route('rumah.index') }}"
                    class="bg-gray-400 text-white px-4 py-2 rounded">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- ================= TITLE ================= -->
    <h1 class="text-2xl font-bold mb-6">Daftar Rumah</h1>


    <!-- ================= KPR ================= -->
    <div class="mb-12">
        <h2 class="text-xl font-bold mb-4">Rumah Bisa KPR</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($kpr as $listing)
                @include('components.card-listing', ['listing' => $listing])
            @empty
                <p class="text-gray-400">Belum ada rumah KPR</p>
            @endforelse

        </div>
    </div>


    <!-- ================= NON KPR ================= -->
    <div>
        <h2 class="text-xl font-bold mb-4">Rumah Non KPR</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($nonKpr as $listing)
                @include('components.card-listing', ['listing' => $listing])
            @empty
                <p class="text-gray-400">Belum ada rumah Non KPR</p>
            @endforelse

        </div>
    </div>

</div>

@endsection