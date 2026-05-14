@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-24">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Marketing</p>
            <h1 class="text-3xl font-extrabold text-gray-950">Dashboard Penjualan Saya</h1>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="month" name="month" value="{{ request('month', $month) }}" class="rounded-lg border-gray-300">
            <button class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white">Filter</button>
        </form>
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('marketing.dashboard') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">Dashboard</a>
        <a href="{{ route('marketing.sales.index') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-bold text-gray-700 ring-1 ring-gray-200">Data Penjualan</a>
        <a href="{{ route('marketing.sales.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-bold text-white">Input Penjualan</a>
    </div>

    <div class="grid gap-4 md:grid-cols-5">
        @foreach([
            ['label' => 'Total Closing', 'value' => number_format($stats['total_closing'])],
            ['label' => 'Total Penjualan', 'value' => 'Rp '.number_format($stats['total_sales'], 0, ',', '.')],
            ['label' => 'Total Komisi', 'value' => 'Rp '.number_format($stats['total_commission'], 0, ',', '.')],
            ['label' => 'Target Pribadi', 'value' => 'Rp '.number_format($stats['target_amount'], 0, ',', '.')],
            ['label' => 'Progress Target', 'value' => $stats['target_percent'].'%'],
        ] as $card)
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                <p class="mt-2 text-xl font-extrabold text-gray-950">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-950">Aktivitas Terbaru</h2>
            <a href="{{ route('marketing.sales.index') }}" class="text-sm font-bold text-blue-700">Lihat semua</a>
        </div>
        <div class="divide-y">
            @forelse($sales as $sale)
                <div class="flex flex-col gap-2 py-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="font-bold text-gray-900">{{ $sale->product_name }}</p>
                        <p class="text-sm text-gray-500">{{ $sale->categoryLabel() }} | {{ $sale->customer_name }} | {{ $sale->deal_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-700">Rp {{ number_format($sale->deal_price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ \App\Models\MarketingSale::STATUSES[$sale->status] ?? $sale->status }}</p>
                    </div>
                </div>
            @empty
                <p class="py-8 text-center text-gray-500">Belum ada penjualan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
