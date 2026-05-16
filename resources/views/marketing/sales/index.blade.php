@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-24">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Marketing</p>
            <h1 class="text-3xl font-extrabold text-gray-950">Data Penjualan Saya</h1>
        </div>
        <a href="{{ route('marketing.sales.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white">Input Penjualan</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-5 grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 md:grid-cols-3">
        <select name="category" class="rounded-lg border-gray-300">
            <option value="">Semua kategori</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-lg border-gray-300">
            <option value="">Semua status</option>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white">Filter</button>
    </form>

    <div class="grid gap-4">
        @forelse($sales as $sale)
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700">{{ $sale->categoryLabel() }}</p>
                        <h2 class="text-xl font-extrabold text-gray-950">{{ $sale->product_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $sale->customer_name }} | {{ $sale->deal_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-lg font-extrabold text-gray-950">Rp {{ number_format($sale->deal_price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">Estimasi komisi Rp {{ number_format($sale->commission_total, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'cancel' => 'bg-gray-100 text-gray-700',
                    ][$sale->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $statuses[$sale->status] ?? $sale->status }}</span>
                    @if($sale->status === 'pending')
                        <form method="POST" action="{{ route('marketing.sales.cancel', $sale) }}">
                            @csrf @method('PATCH')
                            <button class="rounded-lg bg-gray-200 px-3 py-1 text-sm font-bold text-gray-700">Batalkan</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white p-8 text-center text-gray-500 shadow-sm ring-1 ring-gray-200">Belum ada data penjualan.</div>
        @endforelse
    </div>

    <div class="mt-5">{{ $sales->links() }}</div>
</div>
@endsection
