@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Approval</p>
            <h1 class="text-3xl font-extrabold text-gray-950">Data Penjualan Marketing</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.sales.export.excel', request()->query()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white">Export Excel</a>
            <a href="{{ route('admin.sales.export.pdf', request()->query()) }}" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white">Export PDF</a>
        </div>
    </div>

    @include('admin.sales._nav')

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-5 grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 md:grid-cols-5">
        <input type="month" name="month" value="{{ request('month') }}" class="rounded-lg border-gray-300">
        <select name="category" class="rounded-lg border-gray-300">
            <option value="">Semua kategori</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="user_id" class="rounded-lg border-gray-300">
            <option value="">Semua marketing</option>
            @foreach($marketings as $marketing)
                <option value="{{ $marketing->id }}" {{ (string) request('user_id') === (string) $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
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

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Marketing</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Produk</th>
                        <th class="p-3">Customer</th>
                        <th class="p-3">Harga Deal</th>
                        <th class="p-3">Komisi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="border-t">
                            <td class="p-3 whitespace-nowrap">{{ $sale->deal_date->format('d/m/Y') }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $sale->marketing->name ?? '-' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $sale->categoryLabel() }}</td>
                            <td class="p-3 min-w-52">
                                <p class="font-bold text-gray-900">{{ $sale->product_name }}</p>
                                <p class="text-gray-500">{{ $sale->notes ?: '-' }}</p>
                            </td>
                            <td class="p-3 whitespace-nowrap">{{ $sale->customer_name }}</td>
                            <td class="p-3 whitespace-nowrap">Rp {{ number_format($sale->deal_price, 0, ',', '.') }}</td>
                            <td class="p-3 whitespace-nowrap">Rp {{ number_format($sale->commission_total, 0, ',', '.') }}</td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="rounded-full px-2 py-1 text-xs font-bold {{ [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'cancel' => 'bg-gray-100 text-gray-700',
                                ][$sale->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $statuses[$sale->status] ?? $sale->status }}</span>
                            </td>
                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    @if($sale->status === 'pending')
                                        <form method="POST" action="{{ route('admin.sales.approve', $sale) }}">
                                            @csrf @method('PATCH')
                                            <button class="rounded bg-green-600 px-3 py-1 text-white">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.sales.reject', $sale) }}">
                                            @csrf @method('PATCH')
                                            <button class="rounded bg-red-600 px-3 py-1 text-white">Reject</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.sales.cancel', $sale) }}">
                                            @csrf @method('PATCH')
                                            <button class="rounded bg-gray-600 px-3 py-1 text-white">Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="p-8 text-center text-gray-500">Belum ada data penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $sales->links() }}</div>
</div>
@endsection
