@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Listing</h1>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.listings.export.excel', request()->query()) }}"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow-sm">
                Export Excel
            </a>

            <a href="{{ route('admin.listings.export.pdf', request()->query()) }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm">
                Export PDF
            </a>

            <a href="{{ route('listings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm">
                Tambah Listing
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('listings.index') }}" class="mb-5 rounded-lg border bg-white p-4 shadow-sm">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-6">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul, lokasi, pemilik..."
                class="rounded border-gray-300 md:col-span-2">

            <select name="category_id" class="rounded border-gray-300">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="rounded border-gray-300">
                <option value="">Semua status</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="condition" class="rounded border-gray-300">
                <option value="">Semua kondisi</option>
                <option value="baru" {{ request('condition') === 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="bekas" {{ request('condition') === 'bekas' ? 'selected' : '' }}>Bekas</option>
            </select>

            <select name="is_kpr" class="rounded border-gray-300">
                <option value="">Semua rumah</option>
                <option value="1" {{ request('is_kpr') === '1' ? 'selected' : '' }}>Rumah KPR</option>
                <option value="0" {{ request('is_kpr') === '0' ? 'selected' : '' }}>Rumah Non KPR</option>
            </select>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Terapkan Filter
            </button>
            <a href="{{ route('listings.index') }}" class="rounded bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">
                Reset
            </a>
        </div>
    </form>

    <div class="bg-white rounded-lg border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3 text-left">Pemilik</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Beranda</th>
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
                                <span class="rounded bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700">
                                    {{ $listing->product_code ?: $listing->buildProductCode() }}
                                </span>
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
                            <td class="p-3 text-center">
                                @if($listing->is_featured)
                                    <span class="rounded bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
                                        Rekomendasi
                                    </span>
                                @else
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">
                                        Tidak
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    @if($listing->status === 'aktif')
                                        <a href="{{ route('listing.show', $listing->id) }}" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                                            View
                                        </a>

                                        <form action="{{ route('admin.recommendations.toggle', $listing->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="px-3 py-1 rounded text-white {{ $listing->is_featured ? 'bg-slate-600 hover:bg-slate-700' : 'bg-blue-600 hover:bg-blue-700' }}"
                                                data-swal-confirm="{{ $listing->is_featured ? 'Hapus produk ini dari rekomendasi beranda?' : 'Jadikan produk ini rekomendasi dan tampilkan di beranda?' }}"
                                                data-swal-confirm-button="{{ $listing->is_featured ? 'Ya, hapus' : 'Ya, tampilkan' }}">
                                                {{ $listing->is_featured ? 'Hapus Beranda' : 'Tampilkan di Rekomendasi' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="rounded bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                                            Approve dulu
                                        </span>
                                    @endif

                                    <a href="{{ route('listings.edit', $listing->id) }}" class="bg-yellow-400 hover:bg-yellow-500 px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    @if($listing->status === 'pending')
                                        <form action="{{ route('admin.listings.approve', $listing->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                                data-swal-confirm="Publikasikan listing ini?"
                                                data-swal-confirm-button="Ya, publikasikan">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('listings.destroy', $listing->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
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
                            <td colspan="9" class="p-8 text-center text-gray-500">Belum ada listing.</td>
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
