@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-24">
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Marketing</p>
        <h1 class="text-3xl font-extrabold text-gray-950">Input Penjualan Baru</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('marketing.sales.store') }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-bold text-gray-700">Kategori Penjualan</label>
                <select name="category" class="w-full rounded-lg border-gray-300" required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-bold text-gray-700">Tanggal Deal</label>
                <input type="date" name="deal_date" value="{{ old('deal_date', now()->toDateString()) }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-bold text-gray-700">Nama Produk</label>
                <input name="product_name" value="{{ old('product_name') }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-bold text-gray-700">Harga Deal</label>
                <input type="number" name="deal_price" value="{{ old('deal_price') }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-bold text-gray-700">Nama Customer</label>
                <input name="customer_name" value="{{ old('customer_name') }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-bold text-gray-700">Catatan</label>
                <textarea name="notes" rows="4" class="w-full rounded-lg border-gray-300">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button class="rounded-lg bg-blue-600 px-5 py-2 font-bold text-white">Kirim untuk Approval</button>
            <a href="{{ route('marketing.sales.index') }}" class="rounded-lg bg-gray-100 px-5 py-2 font-bold text-gray-700">Batal</a>
        </div>
    </form>
</div>
@endsection
