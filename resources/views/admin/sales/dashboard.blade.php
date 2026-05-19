@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">CRM Sales</p>
            <h1 class="text-3xl font-extrabold text-gray-950">Dashboard Penjualan Marketing</h1>
        </div>

        <form method="GET" class="flex flex-wrap gap-2 rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-200">
            <input type="month" name="month" value="{{ request('month', $month) }}" class="rounded-lg border-gray-300 text-sm">
            <select name="category" class="rounded-lg border-gray-300 text-sm">
                <option value="">Semua kategori</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="user_id" class="rounded-lg border-gray-300 text-sm">
                <option value="">Semua marketing</option>
                @foreach($marketings as $marketing)
                    <option value="{{ $marketing->id }}" {{ (string) request('user_id') === (string) $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">Filter</button>
        </form>
    </div>

    @include('admin.sales._nav')

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['label' => 'Total Penjualan', 'value' => 'Rp '.number_format($stats['total_sales'], 0, ',', '.')],
            ['label' => 'Total Komisi', 'value' => 'Rp '.number_format($stats['total_commission'], 0, ',', '.')],
            ['label' => 'Total Marketing', 'value' => number_format($stats['total_marketing'])],
            ['label' => 'Pending Approval', 'value' => number_format($stats['pending_sales'])],
            ['label' => 'Target Tercapai', 'value' => $stats['target_percent'].'%'],
        ] as $card)
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                <p class="mt-2 text-2xl font-extrabold text-gray-950">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 xl:col-span-2">
            <div class="flex flex-col gap-2 border-b p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-950">Tren Penjualan Bulanan</h2>
                    <p class="text-sm text-gray-500">Penjualan, komisi, dan jumlah closing per hari.</p>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-bold text-blue-700">{{ $month }}</span>
            </div>
            <div class="h-80 p-5">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b p-5">
                <h2 class="text-lg font-bold text-gray-950">Progress Target</h2>
                <p class="text-sm text-gray-500">Perbandingan realisasi dengan target bulan ini.</p>
            </div>
            <div class="h-72 p-5">
                <canvas id="targetProgressChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 xl:col-span-2">
            <div class="border-b p-5">
                <h2 class="text-lg font-bold text-gray-950">Performa Marketing</h2>
                <p class="text-sm text-gray-500">Ranking berdasarkan total penjualan approved.</p>
            </div>
            <div class="h-96 p-5">
                <canvas id="marketingRankingChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="text-lg font-bold text-gray-950">Ranking Marketing</h2>
            <div class="mt-4 space-y-3">
                @forelse($ranking as $index => $row)
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-100 p-3">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-gray-900">#{{ $index + 1 }} {{ $row->marketing->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $row->total_closing }} closing</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-blue-700">Rp {{ number_format($row->total_sales, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Komisi Rp {{ number_format($row->total_commission, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada ranking.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const salesChartData = @json($salesChart);
const rankingChartData = @json($rankingChart);
const salesStats = @json($stats);

const rupiah = (value) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
}).format(value || 0);

const isDarkMode = () => document.documentElement.classList.contains('dark');
const chartTextColor = () => isDarkMode() ? '#d1d5db' : '#4b5563';
const chartGridColor = () => isDarkMode() ? 'rgba(255,255,255,0.08)' : 'rgba(148,163,184,0.22)';

const sharedOptions = () => ({
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

const salesTrendChart = new Chart(document.getElementById('salesTrendChart'), {
    type: 'line',
    data: {
        labels: salesChartData.labels,
        datasets: [
            {
                label: 'Penjualan',
                data: salesChartData.sales,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                fill: true,
                borderWidth: 3,
                tension: 0.38,
                pointRadius: 2,
                yAxisID: 'y',
            },
            {
                label: 'Komisi',
                data: salesChartData.commissions,
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                borderWidth: 2,
                tension: 0.38,
                pointRadius: 2,
                yAxisID: 'y',
            },
            {
                label: 'Closing',
                data: salesChartData.closings,
                borderColor: '#d97706',
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                borderWidth: 2,
                tension: 0.38,
                pointRadius: 2,
                yAxisID: 'closing',
            },
        ],
    },
    options: {
        ...sharedOptions(),
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            x: {
                ticks: { color: chartTextColor(), maxTicksLimit: 8 },
                grid: { color: chartGridColor() },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: chartTextColor(),
                    callback: (value) => rupiah(value).replace('Rp', 'Rp '),
                },
                grid: { color: chartGridColor() },
            },
            closing: {
                beginAtZero: true,
                position: 'right',
                ticks: {
                    color: chartTextColor(),
                    precision: 0,
                },
                grid: { drawOnChartArea: false },
            },
        },
    },
});

const achieved = Math.min(Number(salesStats.total_sales || 0), Number(salesStats.target_amount || 0));
const remaining = Math.max(Number(salesStats.target_amount || 0) - Number(salesStats.total_sales || 0), 0);
const targetProgressChart = new Chart(document.getElementById('targetProgressChart'), {
    type: 'doughnut',
    data: {
        labels: ['Tercapai', 'Sisa target'],
        datasets: [{
            data: salesStats.target_amount > 0 ? [achieved, remaining] : [Number(salesStats.total_sales || 0), 0],
            backgroundColor: ['#2563eb', '#e5e7eb'],
            borderColor: isDarkMode() ? '#111827' : '#ffffff',
            borderWidth: 3,
        }],
    },
    options: {
        ...sharedOptions(),
        cutout: '70%',
        plugins: {
            ...sharedOptions().plugins,
            tooltip: {
                ...sharedOptions().plugins.tooltip,
                callbacks: {
                    label: (context) => `${context.label}: ${rupiah(context.raw)}`,
                },
            },
        },
    },
    plugins: [{
        id: 'targetCenterText',
        afterDraw(chart) {
            const { ctx, chartArea } = chart;
            const centerX = (chartArea.left + chartArea.right) / 2;
            const centerY = (chartArea.top + chartArea.bottom) / 2;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.fillStyle = chartTextColor();
            ctx.font = '700 28px Figtree, sans-serif';
            ctx.fillText(`${salesStats.target_percent}%`, centerX, centerY - 2);
            ctx.font = '500 12px Figtree, sans-serif';
            ctx.fillText('target', centerX, centerY + 18);
            ctx.restore();
        },
    }],
});

const marketingRankingChart = new Chart(document.getElementById('marketingRankingChart'), {
    type: 'bar',
    data: {
        labels: rankingChartData.labels,
        datasets: [
            {
                label: 'Penjualan',
                data: rankingChartData.sales,
                backgroundColor: 'rgba(37, 99, 235, 0.84)',
                borderRadius: 8,
                maxBarThickness: 40,
            },
            {
                label: 'Komisi',
                data: rankingChartData.commissions,
                backgroundColor: 'rgba(5, 150, 105, 0.78)',
                borderRadius: 8,
                maxBarThickness: 40,
            },
        ],
    },
    options: {
        ...sharedOptions(),
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    color: chartTextColor(),
                    callback: (value) => rupiah(value).replace('Rp', 'Rp '),
                },
                grid: { color: chartGridColor() },
            },
            y: {
                ticks: { color: chartTextColor() },
                grid: { display: false },
            },
        },
    },
});

function refreshSalesChartTheme() {
    [salesTrendChart, marketingRankingChart].forEach((chart) => {
        chart.options.plugins.legend.labels.color = chartTextColor();
        chart.options.plugins.tooltip.backgroundColor = isDarkMode() ? '#111827' : '#ffffff';
        chart.options.plugins.tooltip.titleColor = isDarkMode() ? '#f9fafb' : '#111827';
        chart.options.plugins.tooltip.bodyColor = isDarkMode() ? '#d1d5db' : '#374151';
        chart.options.plugins.tooltip.borderColor = isDarkMode() ? 'rgba(255,255,255,0.12)' : 'rgba(17,24,39,0.1)';
        chart.options.scales.x.ticks.color = chartTextColor();
        chart.options.scales.y.ticks.color = chartTextColor();

        if (chart.options.scales.x.grid) {
            chart.options.scales.x.grid.color = chartGridColor();
        }

        if (chart.options.scales.y.grid) {
            chart.options.scales.y.grid.color = chartGridColor();
        }

        if (chart.options.scales.closing) {
            chart.options.scales.closing.ticks.color = chartTextColor();
        }

        chart.update();
    });

    targetProgressChart.options.plugins.legend.labels.color = chartTextColor();
    targetProgressChart.data.datasets[0].borderColor = isDarkMode() ? '#111827' : '#ffffff';
    targetProgressChart.update();
}

new MutationObserver(refreshSalesChartTheme).observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class'],
});
</script>
@endsection
