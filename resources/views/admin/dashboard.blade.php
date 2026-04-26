@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">

    <!-- TITLE -->
    <h1 class="text-2xl md:text-3xl font-bold mb-6">Dashboard Admin</h1>

    <!-- ================= STATISTIK ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-500 text-sm">Total Listing</p>
            <h2 class="text-3xl font-bold text-blue-600">{{ $totalListings }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-500 text-sm">Total User</p>
            <h2 class="text-3xl font-bold text-green-600">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-gray-500 text-sm">Total Kategori</p>
            <h2 class="text-3xl font-bold text-purple-600">{{ $totalCategories }}</h2>
        </div>

    </div>

    <!-- ================= QUICK ACTION ================= -->
    <div class="flex flex-wrap gap-3 mb-6">

        <a href="{{ route('listings.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
           + Tambah Listing
        </a>

        <a href="{{ route('carousel.index') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow">
           Kelola Carousel
        </a>

        <a href="{{ route('posts.index') }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow">
           Kelola Berita
        </a>

        <a href="{{ route('admin.testimonials.index') }}" 
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Kelola Testimoni
        </a>

    </div>

    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="font-semibold text-gray-700">Listing Terbaru</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($listings as $listing)
                    <tr class="border-t hover:bg-gray-50">

                        <!-- IMAGE -->
                        <td class="p-3">
                            <img 
                                src="{{ $listing->images->count() 
                                        ? asset('storage/'.$listing->images->first()->image) 
                                        : 'https://via.placeholder.com/80' }}"
                                class="w-16 h-12 object-cover rounded">
                        </td>

                        <!-- TITLE -->
                        <td class="p-3 font-medium">
                            {{ $listing->title }}
                        </td>

                        <!-- PRICE -->
                        <td class="p-3 text-blue-600 font-semibold">
                            Rp {{ number_format($listing->price) }}
                        </td>

                        <!-- LOCATION -->
                        <td class="p-3 text-gray-500">
                            {{ $listing->location }}
                        </td>

                        <!-- ACTION -->
                        <td class="p-3">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('listing.show',$listing->id) }}"
                                   class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm">
                                   View
                                </a>

                                <a href="{{ route('listings.edit',$listing->id) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 px-3 py-1 rounded text-sm">
                                   Edit
                                </a>

                                <form action="{{ route('listings.destroy',$listing->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus data?')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-6 text-gray-400">
                            Belum ada data listing
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection