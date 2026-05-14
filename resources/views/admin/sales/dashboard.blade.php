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
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-950">Grafik Penjualan</h2>
                <span class="text-sm text-gray-500">{{ $month }}</span>
            </div>
            <div class="flex h-72 items-end gap-2 border-b border-l border-gray-200 px-2">
                @forelse($chart as $point)
                    @php
                        $height = $stats['total_sales'] > 0 ? max(8, ($point->total / $stats['total_sales']) * 260) : 8;
                    @endphp
                    <div class="group flex flex-1 flex-col items-center gap-2">
                        <div class="w-full rounded-t-lg bg-blue-600 transition group-hover:bg-blue-700" style="height: {{ $height }}px"></div>
                        <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($point->date)->format('d') }}</span>
                    </div>
                @empty
                    <div class="flex h-full w-full items-center justify-center text-sm text-gray-500">Belum ada penjualan approved pada periode ini.</div>
                @endforelse
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
@endsection
