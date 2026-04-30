@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Listing</h1>
        </div>

        <a href="{{ route('listings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm">
            Tambah Listing
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3 text-left">Pemilik</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Status</th>
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
                            <td class="p-3">{{ $listing->user->name ?? '-' }}</td>
                            <td class="p-3">{{ $listing->category->name ?? '-' }}</td>
                            <td class="p-3"><x-listing-price :listing="$listing" /></td>
                            <td class="p-3 text-center">
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'aktif' => 'bg-green-100 text-green-700',
                                        'sold' => 'bg-blue-100 text-blue-700',
                                    ][$listing->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    @if($listing->status === 'aktif')
                                        <a href="{{ route('listing.show', $listing->id) }}" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                                            View
                                        </a>
                                    @endif

                                    <a href="{{ route('listings.edit', $listing->id) }}" class="bg-yellow-400 hover:bg-yellow-500 px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    @if($listing->status === 'pending')
                                        <form action="{{ route('admin.listings.approve', $listing->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button onclick="return confirm('Publikasikan listing ini?')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('listings.destroy', $listing->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus listing ini?')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">Belum ada listing.</td>
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
