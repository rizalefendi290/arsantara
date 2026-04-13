@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-gray-500">Total Listing</h2>
            <p class="text-2xl font-bold">{{ $totalListings }}</p>
        </div>

        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-gray-500">Total User</h2>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-gray-500">Total Kategori</h2>
            <p class="text-2xl font-bold">{{ $totalCategories }}</p>
        </div>
    </div>

    <!-- Tombol tambah -->
    <div class="mb-4 flex gap-2">
        <a href="{{ route('listings.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded">
           + Tambah Listing
        </a>

        <a href="{{ route('carousel.index') }}" 
           class="bg-purple-500 text-white px-41 py-2 rounded">
           Kelola Carousel
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Judul</th>
                    <th class="p-2">Harga</th>
                    <th class="p-2">Lokasi</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listings as $listing)
                <tr class="border-t">
                    <td class="p-2">{{ $listing->title }}</td>
                    <td class="p-2">Rp {{ number_format($listing->price) }}</td>
                    <td class="p-2">{{ $listing->location }}</td>
                    <td class="p-2 flex gap-2">
                        <a href="{{ route('listings.edit',$listing->id) }}" 
                           class="bg-yellow-400 px-2 py-1 rounded">
                           Edit
                        </a>

                        <form action="{{ route('listings.destroy',$listing->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection