@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Produk Rekomendasi</h1>
        </div>

        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-semibold">
            {{ $totalRecommended }} produk aktif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white border rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.recommendations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                class="border rounded-lg px-3 py-2"
                placeholder="Cari judul atau lokasi">

            <select name="category_id" class="border rounded-lg px-3 py-2">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('admin.recommendations.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-center">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-white rounded-lg border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Produk</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Pemilik</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Rekomendasi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($listings as $listing)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">
                                <img src="{{ $listing->images->count() ? asset('storage/'.$listing->images->first()->image) : 'https://via.placeholder.com/80' }}"
                                    class="w-16 h-12 object-cover rounded" alt="{{ $listing->title }}">
                            </td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $listing->title }}</p>
                                <p class="text-gray-500">{{ $listing->location }}</p>
                            </td>
                            <td class="p-3">{{ $listing->category->name ?? '-' }}</td>
                            <td class="p-3">{{ $listing->user->name ?? '-' }}</td>
                            <td class="p-3"><x-listing-price :listing="$listing" /></td>
                            <td class="p-3 text-center">
                                @if($listing->is_featured)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">Tidak</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.recommendations.toggle', $listing->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1 rounded text-white {{ $listing->is_featured ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-600 hover:bg-blue-700' }}"
                                        data-swal-confirm="Ubah status rekomendasi produk ini?"
                                        data-swal-confirm-button="Ya, ubah">
                                        {{ $listing->is_featured ? 'Hapus' : 'Jadikan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">Tidak ada produk aktif yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $listings->links() }}
    </div>
</div>
@endsection
