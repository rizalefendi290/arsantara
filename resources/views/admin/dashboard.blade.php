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
        @php
            $metricCards = [
                ['label' => 'Total Listing', 'value' => $totalListings, 'color' => 'text-blue-600', 'caption' => 'Semua produk yang tersimpan'],
                ['label' => 'Total User', 'value' => $totalUsers, 'color' => 'text-emerald-600', 'caption' => 'Akun pengguna terdaftar'],
                ['label' => 'Listing Aktif', 'value' => $listingStatusCounts['aktif'] ?? 0, 'color' => 'text-violet-600', 'caption' => 'Produk tampil ke publik'],
                ['label' => 'Listing Pending', 'value' => $listingStatusCounts['pending'] ?? 0, 'color' => 'text-amber-600', 'caption' => 'Menunggu review admin'],
            ];
        @endphp

        @foreach($metricCards as $card)
            <div class="p-5 bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                <h2 class="mt-3 text-3xl font-bold {{ $card['color'] }}">{{ number_format($card['value']) }}</h2>
                <p class="mt-2 text-xs font-medium text-gray-500">{{ $card['caption'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-3">
        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl xl:col-span-2">
            <div class="flex flex-col gap-2 p-5 border-b md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Tren Performa 14 Hari</h2>
                    <p class="text-sm text-gray-500">Pengunjung unik, listing baru, dan user baru per hari.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-blue-700">Pengunjung</span>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">Listing</span>
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">User</span>
                </div>
            </div>
            <div class="h-80 p-5">
                <canvas id="dashboardTrendChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="p-5 border-b">
                    <h2 class="text-lg font-bold text-gray-900">Pengunjung</h2>
                    <p class="text-sm text-gray-500">Ringkasan kunjungan unik.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 p-5">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold text-gray-500">Hari ini</p>
                        <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($visitorStats['today']) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold text-gray-500">Minggu ini</p>
                        <p class="mt-1 text-2xl font-bold text-indigo-600">{{ number_format($visitorStats['week']) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold text-gray-500">Bulan ini</p>
                        <p class="mt-1 text-2xl font-bold text-orange-600">{{ number_format($visitorStats['month']) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold text-gray-500">Total</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($visitorStats['total']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
                <div class="p-5 border-b">
                    <h2 class="text-lg font-bold text-gray-900">Status Listing</h2>
                    <p class="text-sm text-gray-500">Distribusi status produk.</p>
                </div>
                <div class="h-64 p-5">
                    <canvas id="listingStatusChart"></canvas>
                </div>
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

    <div class="mb-8 overflow-hidden bg-white shadow-sm ring-1 ring-gray-200 rounded-xl">
        <div class="flex flex-col gap-1 p-4 border-b md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-gray-700">Listing per Kategori</h2>
                <p class="text-sm text-gray-500">Perbandingan jumlah listing di setiap kategori.</p>
            </div>
        </div>
        <div class="h-80 p-5">
            <canvas id="categoryListingChart"></canvas>
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
const dashboardChartData = @json($dashboardChart);
const listingStatusChartData = @json($listingStatusChart);
const categoryChartData = {
    labels: @json($categories->pluck('name')->values()),
    data: @json($categories->pluck('listings_count')->values()),
};

const isDarkMode = () => document.documentElement.classList.contains('dark');
const chartTextColor = () => isDarkMode() ? '#d1d5db' : '#4b5563';
const chartGridColor = () => isDarkMode() ? 'rgba(255,255,255,0.08)' : 'rgba(148,163,184,0.22)';

const sharedChartOptions = () => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: chartTextColor(),
                usePointStyle: true,
                boxWidth: 8,
                boxHeight: 8,
            },
        },
        tooltip: {
            backgroundColor: isDarkMode() ? '#111827' : '#ffffff',
            titleColor: isDarkMode() ? '#f9fafb' : '#111827',
            bodyColor: isDarkMode() ? '#d1d5db' : '#374151',
            borderColor: isDarkMode() ? 'rgba(255,255,255,0.12)' : 'rgba(17,24,39,0.1)',
            borderWidth: 1,
            padding: 12,
        },
    },
});

const dashboardTrendChart = new Chart(document.getElementById('dashboardTrendChart'), {
    type: 'line',
    data: {
        labels: dashboardChartData.labels,
        datasets: [
            {
                label: 'Pengunjung unik',
                data: dashboardChartData.visitors,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                fill: true,
                borderWidth: 3,
                tension: 0.38,
                pointRadius: 3,
                pointHoverRadius: 5,
            },
            {
                label: 'Listing baru',
                data: dashboardChartData.listings,
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                fill: false,
                borderWidth: 2,
                tension: 0.38,
                pointRadius: 3,
            },
            {
                label: 'User baru',
                data: dashboardChartData.users,
                borderColor: '#d97706',
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                fill: false,
                borderWidth: 2,
                tension: 0.38,
                pointRadius: 3,
            },
        ],
    },
    options: {
        ...sharedChartOptions(),
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            x: {
                ticks: { color: chartTextColor() },
                grid: { color: chartGridColor() },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: chartTextColor(),
                    precision: 0,
                },
                grid: { color: chartGridColor() },
            },
        },
    },
});

const listingStatusChart = new Chart(document.getElementById('listingStatusChart'), {
    type: 'doughnut',
    data: {
        labels: listingStatusChartData.labels,
        datasets: [{
            data: listingStatusChartData.data,
            backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444', '#8b5cf6'],
            borderColor: isDarkMode() ? '#111827' : '#ffffff',
            borderWidth: 3,
        }],
    },
    options: {
        ...sharedChartOptions(),
        cutout: '68%',
    },
});

const categoryListingChart = new Chart(document.getElementById('categoryListingChart'), {
    type: 'bar',
    data: {
        labels: categoryChartData.labels,
        datasets: [{
            label: 'Jumlah listing',
            data: categoryChartData.data,
            backgroundColor: 'rgba(37, 99, 235, 0.82)',
            borderRadius: 8,
            maxBarThickness: 48,
        }],
    },
    options: {
        ...sharedChartOptions(),
        scales: {
            x: {
                ticks: { color: chartTextColor() },
                grid: { display: false },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: chartTextColor(),
                    precision: 0,
                },
                grid: { color: chartGridColor() },
            },
        },
    },
});

function refreshChartTheme() {
    [dashboardTrendChart, categoryListingChart].forEach((chart) => {
        chart.options.plugins.legend.labels.color = chartTextColor();
        chart.options.plugins.tooltip.backgroundColor = isDarkMode() ? '#111827' : '#ffffff';
        chart.options.plugins.tooltip.titleColor = isDarkMode() ? '#f9fafb' : '#111827';
        chart.options.plugins.tooltip.bodyColor = isDarkMode() ? '#d1d5db' : '#374151';
        chart.options.plugins.tooltip.borderColor = isDarkMode() ? 'rgba(255,255,255,0.12)' : 'rgba(17,24,39,0.1)';
        chart.options.scales.x.ticks.color = chartTextColor();
        chart.options.scales.y.ticks.color = chartTextColor();
        chart.options.scales.y.grid.color = chartGridColor();

        if (chart.options.scales.x.grid) {
            chart.options.scales.x.grid.color = chartGridColor();
        }

        chart.update();
    });

    listingStatusChart.options.plugins.legend.labels.color = chartTextColor();
    listingStatusChart.data.datasets[0].borderColor = isDarkMode() ? '#111827' : '#ffffff';
    listingStatusChart.update();
}

new MutationObserver(refreshChartTheme).observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class'],
});
</script>
@endsection
