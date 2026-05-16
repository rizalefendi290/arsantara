@extends('layouts.admin')

@section('admin_content')
<div>

    <div class="flex flex-col gap-2 mb-6 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm text-gray-500">Dashboard Admin Analytics</p>
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Ringkasan Performa Website</h1>
        </div>

        <div class="px-4 py-2 font-semibold text-green-700 rounded-lg bg-green-50">
            {{ $activeVisitors }} pengunjung aktif sekarang
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 mb-5 text-green-700 border border-green-200 rounded-lg bg-green-50">
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

    <!-- ================= STATISTIK ================= -->
    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-4">

        <div class="p-5 transition bg-white shadow rounded-xl hover:shadow-lg">
            <p class="text-sm text-gray-500">Total Listing</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-blue-600">
                    {{ $totalListings }}
                </h2>
                <div class="w-24 h-16">
                    <canvas id="listingChart"></canvas>
                </div>
            </div>
        </div>

        <div class="p-5 transition bg-white shadow rounded-xl hover:shadow-lg">
            <p class="text-sm text-gray-500">Total User</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-green-600">
                    {{ $totalUsers }}
                </h2>
                <canvas id="userChart" class="w-24 h-16"></canvas>
            </div>
        </div>

        <div class="p-5 transition bg-white shadow rounded-xl hover:shadow-lg">
            <p class="text-sm text-gray-500">Listing Aktif</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-purple-600">
                    {{ $listingStatusCounts['aktif'] ?? 0 }}
                </h2>
                <canvas id="activeListingChart" class="w-24 h-16"></canvas>
            </div>
        </div>

        <div class="p-5 transition bg-white shadow rounded-xl hover:shadow-lg">
            <p class="text-sm text-gray-500">Listing Pending</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-yellow-600">
                    {{ $listingStatusCounts['pending'] ?? 0 }}
                </h2>
                <canvas id="pendingListingChart" class="w-24 h-16"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-5 bg-white shadow rounded-xl">
            <p class="text-sm text-gray-500">Pengunjung Hari Ini</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-blue-600">
                    {{ $visitorStats['today'] }}
                </h2>
                <canvas id="todayVisitorChart" class="w-24 h-16"></canvas>
            </div>
        </div>

        <div class="p-5 bg-white shadow rounded-xl">
            <p class="text-sm text-gray-500">Pengunjung Minggu Ini</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-indigo-600">
                    {{ $visitorStats['week'] }}
                </h2>
                <canvas id="weekVisitorChart" class="w-24 h-16"></canvas>
            </div>
        </div>

        <div class="p-5 bg-white shadow rounded-xl">
            <p class="text-sm text-gray-500">Pengunjung Bulan Ini</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-orange-600">
                    {{ $visitorStats['month'] }}
                </h2>
                <canvas id="monthVisitorChart" class="w-24 h-16"></canvas>
            </div>
        </div>

        <div class="p-5 bg-white shadow rounded-xl">
            <p class="text-sm text-gray-500">Total Pengunjung</p>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ $visitorStats['total'] }}
                </h2>
                <canvas id="totalVisitorChart" class="w-24 h-16"></canvas>
            </div>
        </div>

    </div>

    <div class="mb-8 overflow-hidden bg-white shadow rounded-xl">
        <div class="flex flex-col gap-1 p-4 border-b md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-gray-700">Status Kategori</h2>
                <p class="text-sm text-gray-500">Nonaktifkan kategori sementara tanpa menghapus data listing.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-600 bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-center">Jumlah Listing</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-semibold text-gray-900">{{ $category->name }}</td>
                            <td class="p-3 text-center">{{ $category->listings_count }}</td>
                            <td class="p-3 text-center">
                                <span class="rounded px-2 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded px-3 py-1 text-sm font-semibold {{ $category->is_active ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                        data-swal-confirm="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} kategori {{ $category->name }}?">
                                        {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-3">
        <div class="overflow-hidden bg-white shadow rounded-xl xl:col-span-2">
            <div class="p-4 border-b">
                <h2 class="font-semibold text-gray-700">Produk Paling Banyak Diklik</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-gray-600 bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Produk</th>
                            <th class="p-3 text-left">Kategori</th>
                            <th class="p-3 text-left">Pemilik</th>
                            <th class="p-3 text-center">Klik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topListings as $listing)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $listing->images->count() ? asset('storage/'.$listing->images->first()->image) : 'https://via.placeholder.com/80' }}"
                                            class="object-cover h-10 rounded w-14" alt="{{ $listing->title }}">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $listing->title }}</p>
                                            <p class="text-gray-500">{{ $listing->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3">{{ $listing->category->name ?? '-' }}</td>
                                <td class="p-3">{{ $listing->user->name ?? '-' }}</td>
                                <td class="p-3 font-bold text-center text-blue-600">{{ $listing->views_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-400">Belum ada data klik produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden bg-white shadow rounded-xl">
            <div class="p-4 border-b">
                <h2 class="font-semibold text-gray-700">Agen/Pemilik Paling Aktif</h2>
            </div>
            <div class="divide-y">
                @forelse($activeAgents as $agent)
                    <div class="flex items-center justify-between gap-3 p-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $agent->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($agent->role) }} • {{ $agent->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ $agent->listings_count }}</p>
                            <p class="text-xs text-gray-500">{{ $agent->active_listings_count }} aktif</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400">Belum ada agen atau pemilik aktif.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mb-8 overflow-hidden bg-white shadow rounded-xl">
        <div class="p-4 border-b">
            <h2 class="font-semibold text-gray-700">Pengunjung 7 Hari Terakhir</h2>
        </div>
        <div class="grid grid-cols-1 gap-3 p-4 md:grid-cols-7">
            @forelse($dailyVisitors as $day)
                @php
                    $height = max(18, min(120, ((int) $day->total) * 18));
                @endphp
                <div class="flex flex-col justify-end p-3 rounded-lg bg-gray-50 min-h-40">
                    <div class="bg-blue-500 rounded-t" style="height: {{ $height }}px"></div>
                    <p class="mt-2 font-bold text-center text-gray-900">{{ $day->total }}</p>
                    <p class="text-xs text-center text-gray-500">{{ \Carbon\Carbon::parse($day->visit_date)->format('d M') }}</p>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400 md:col-span-7">Belum ada data pengunjung.</div>
            @endforelse
        </div>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="overflow-hidden bg-white shadow rounded-xl">

        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="font-semibold text-gray-700">Listing Terbaru</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="text-gray-600 bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Judul</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($listings as $listing)
                    <tr class="border-t hover:bg-gray-50">

                        <!-- IMAGE -->
                        <td class="p-3">
                            <img src="{{ $listing->images->count()
                                        ? asset('storage/'.$listing->images->first()->image)
                                        : 'https://via.placeholder.com/80' }}" class="object-cover w-16 h-12 rounded">
                        </td>

                        <!-- TITLE -->
                        <td class="p-3 font-medium">
                            {{ $listing->title }}
                        </td>

                        <!-- PRICE -->
                        <td class="p-3">
                            <x-listing-price :listing="$listing" />
                        </td>

                        <!-- LOCATION -->
                        <td class="p-3 text-gray-500">
                            {{ $listing->location }}
                        </td>

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

                        <!-- ACTION -->
                        <td class="p-3">
                            <div class="flex justify-center gap-2">

                                @if($listing->status === 'aktif')
                                    <a href="{{ route('listing.show',$listing->id) }}"
                                        class="px-3 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300">
                                        View
                                    </a>
                                @endif

                                <a href="{{ route('listings.edit',$listing->id) }}"
                                    class="px-3 py-1 text-sm bg-yellow-400 rounded hover:bg-yellow-500">
                                    Edit
                                </a>

                                @if($listing->status === 'pending')
                                    <form action="{{ route('admin.listings.approve',$listing->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600"
                                            data-swal-confirm="Publikasikan listing ini?"
                                            data-swal-confirm-button="Ya, publikasikan">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('listings.destroy',$listing->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600"
                                        data-swal-confirm="Yakin hapus data?"
                                        data-swal-confirm-button="Ya, hapus">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400">
                            Belum ada data listing
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const listingCtx = document.getElementById('listingChart');

new Chart(listingCtx, {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            data: [3, 5, 4, 7, 6, 9, 12],
            borderColor: '#2563eb',
            backgroundColor: 'transparent',
            borderWidth: 2,
            tension: 0.4,
            pointRadius: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                display: false
            },
            y: {
                display: false
            }
        }
    }
});
</script>
@endsection
