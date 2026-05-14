@extends('layouts.admin')

@section('admin_content')
<div>
    <h1 class="mb-2 text-3xl font-extrabold text-gray-950">Target Penjualan</h1>
    <p class="mb-6 text-gray-600">Target bisa dibuat global atau spesifik untuk marketing tertentu.</p>

    @include('admin.sales._nav')

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.sales.targets.store') }}" class="mb-6 grid gap-3 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 md:grid-cols-6">
        @csrf
        <select name="user_id" class="rounded-lg border-gray-300 md:col-span-2">
            <option value="">Target global semua marketing</option>
            @foreach($marketings as $marketing)
                <option value="{{ $marketing->id }}">{{ $marketing->name }}</option>
            @endforeach
        </select>
        <select name="target_type" class="rounded-lg border-gray-300">
            @foreach($types as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <input type="number" name="period_year" value="{{ now()->year }}" class="rounded-lg border-gray-300" placeholder="Tahun">
        <input type="number" name="period_month" value="{{ now()->month }}" min="1" max="12" class="rounded-lg border-gray-300" placeholder="Bulan">
        <input type="number" name="target_amount" class="rounded-lg border-gray-300" placeholder="Nominal target">
        <textarea name="notes" rows="2" class="rounded-lg border-gray-300 md:col-span-6" placeholder="Catatan target"></textarea>
        <button class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white md:col-span-6">Simpan Target</button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="p-4">Marketing</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Periode</th>
                    <th class="p-4">Target</th>
                    <th class="p-4">Catatan</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($targets as $target)
                    <tr class="border-t">
                        <td class="p-4">{{ $target->marketing->name ?? 'Global' }}</td>
                        <td class="p-4">{{ $target->targetLabel() }}</td>
                        <td class="p-4">{{ $target->period_month ? str_pad($target->period_month, 2, '0', STR_PAD_LEFT).'/' : '' }}{{ $target->period_year }}</td>
                        <td class="p-4">Rp {{ number_format($target->target_amount, 0, ',', '.') }}</td>
                        <td class="p-4">{{ $target->notes ?: '-' }}</td>
                        <td class="p-4 text-center">
                            <form method="POST" action="{{ route('admin.sales.targets.destroy', $target) }}">
                                @csrf @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-gray-500">Belum ada target.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $targets->links() }}</div>
</div>
@endsection
