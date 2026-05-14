@extends('layouts.admin')

@section('admin_content')
<div>
    <h1 class="mb-2 text-3xl font-extrabold text-gray-950">Manajemen Marketing</h1>
    <p class="mb-6 text-gray-600">Kelola karyawan marketing, status aktif, dan pantau performa closing.</p>

    @include('admin.sales._nav')

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.sales.marketing.store') }}" enctype="multipart/form-data"
        class="mb-6 grid gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 md:grid-cols-3">
        @csrf
        <input name="name" placeholder="Nama marketing" class="rounded-lg border-gray-300" required>
        <input name="email" type="email" placeholder="Email" class="rounded-lg border-gray-300" required>
        <input name="phone" placeholder="No HP" class="rounded-lg border-gray-300">
        <input name="password" type="password" placeholder="Password awal" class="rounded-lg border-gray-300" required>
        <input name="profile_photo" type="file" class="rounded-lg border border-gray-300 p-2">
        <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
            <span class="text-sm font-semibold text-gray-700">Aktif</span>
        </label>
        <button class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white md:col-span-3">Tambah Marketing</button>
    </form>

    <div class="grid gap-4">
        @foreach($marketings as $marketing)
            <form method="POST" action="{{ route('admin.sales.marketing.update', $marketing) }}" enctype="multipart/form-data"
                class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 md:grid-cols-6 md:items-center">
                @csrf @method('PATCH')
                <div class="md:col-span-2">
                    <p class="font-bold text-gray-950">{{ $marketing->name }}</p>
                    <p class="text-sm text-gray-500">{{ $marketing->email }}</p>
                    <p class="text-xs text-blue-700">{{ $marketing->approved_sales_count }} closing approved | Rp {{ number_format($marketing->approved_sales_sum ?? 0, 0, ',', '.') }}</p>
                </div>
                <input name="name" value="{{ $marketing->name }}" class="rounded-lg border-gray-300">
                <input name="email" type="email" value="{{ $marketing->email }}" class="rounded-lg border-gray-300">
                <input name="phone" value="{{ $marketing->phone }}" placeholder="No HP" class="rounded-lg border-gray-300">
                <input name="password" type="password" placeholder="Password baru opsional" class="rounded-lg border-gray-300">
                <input name="profile_photo" type="file" class="rounded-lg border border-gray-300 p-2 md:col-span-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $marketing->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-sm font-semibold">Aktif</span>
                </label>
                <button class="rounded-lg bg-slate-900 px-4 py-2 font-bold text-white md:col-span-3">Simpan</button>
            </form>
        @endforeach
    </div>

    <div class="mt-5">{{ $marketings->links() }}</div>
</div>
@endsection
