@extends('layouts.admin')

@section('admin_content')
<div>
    <h1 class="mb-2 text-3xl font-extrabold text-gray-950">Master Komisi</h1>
    <p class="mb-6 text-gray-600">Atur persentase dan nominal fix per kategori penjualan.</p>

    @include('admin.sales._nav')

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.sales.commissions.update') }}" class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        @csrf @method('PATCH')
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Persen Komisi</th>
                    <th class="p-4">Komisi Fix</th>
                    <th class="p-4">Aktif</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $value => $label)
                    @php $rule = $rules[$value] ?? null; @endphp
                    <tr class="border-t">
                        <td class="p-4 font-bold text-gray-900">{{ $label }}</td>
                        <td class="p-4">
                            <input type="number" step="0.01" min="0" max="100" name="rules[{{ $value }}][commission_percent]"
                                value="{{ old('rules.'.$value.'.commission_percent', $rule->commission_percent ?? 0) }}"
                                class="w-full rounded-lg border-gray-300" placeholder="2">
                        </td>
                        <td class="p-4">
                            <input type="number" min="0" name="rules[{{ $value }}][commission_fixed]"
                                value="{{ old('rules.'.$value.'.commission_fixed', $rule->commission_fixed ?? 0) }}"
                                class="w-full rounded-lg border-gray-300" placeholder="500000">
                        </td>
                        <td class="p-4">
                            <input type="checkbox" name="rules[{{ $value }}][is_active]" value="1" {{ ($rule?->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t bg-gray-50 p-4">
            <button class="rounded-lg bg-blue-600 px-5 py-2 font-bold text-white">Simpan Master Komisi</button>
        </div>
    </form>
</div>
@endsection
