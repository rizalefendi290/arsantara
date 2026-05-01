@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-24">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Dashboard {{ ucfirst(auth()->user()->role) }}</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Listing Saya</h1>
        </div>

        <a href="{{ route('agent.listings.create') }}"
            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            Tambah Listing
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('share_url'))
        <x-listing-share
            class="mb-6"
            :url="session('share_url')"
            :title="session('share_title', 'Bagikan listing')"
            :text="session('share_text')"
            :available="session('share_available', true)" />
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Aktif</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Terjual/Disewa</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['sold'] }}</p>
        </div>
    </div>

    <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Produk yang Saya Posting</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listings as $listing)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">
                                <img src="{{ $listing->images->count() ? asset('storage/'.$listing->images->first()->image) : 'https://via.placeholder.com/96x72?text=No+Image' }}"
                                    class="w-20 h-14 object-cover rounded" alt="{{ $listing->title }}">
                            </td>
                            <td class="p-3">
                                <span class="rounded bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700">
                                    {{ $listing->product_code ?: $listing->buildProductCode() }}
                                </span>
                            </td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $listing->title }}</p>
                                <p class="text-gray-500">{{ $listing->location }}</p>
                            </td>
                            <td class="p-3">{{ $listing->category->name ?? '-' }}</td>
                            <td class="p-3"><x-listing-price :listing="$listing" /></td>
                            <td class="p-3">
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
                                        <a href="{{ route('listing.show', $listing->id) }}"
                                            class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                            View
                                        </a>
                                    @endif

                                    <a href="{{ route('agent.listings.edit', $listing->id) }}"
                                        class="px-3 py-1 rounded bg-yellow-400 hover:bg-yellow-500">
                                        Edit
                                    </a>

                                    @if($listing->status !== 'sold')
                                        <form action="{{ route('agent.listings.sold', $listing->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white"
                                                data-swal-confirm="Tandai listing ini sebagai terjual/disewa?"
                                                data-swal-confirm-button="Ya, tandai">
                                                Sold
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('agent.listings.destroy', $listing->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white"
                                            data-swal-confirm="Yakin hapus listing ini?"
                                            data-swal-confirm-button="Ya, hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                Belum ada listing. Mulai posting produk pertama Anda.
                            </td>
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
