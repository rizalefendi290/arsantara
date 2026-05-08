@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Pengajuan Agen / Pemilik Produk</h1>
            <p class="mt-1 text-sm text-gray-500">Lihat dan proses permintaan upgrade role dari user.</p>
        </div>

        <a href="{{ route('admin.users') }}" class="inline-flex w-fit items-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
            Kelola User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-lg border bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pengajuan</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-lg border bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Menunggu Review</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="rounded-lg border bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Agen</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['agen'] }}</p>
        </div>
        <div class="rounded-lg border bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pemilik Produk</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['pemilik'] }}</p>
        </div>
    </div>

    <div class="mb-6 rounded-lg border bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.role-requests.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}"
                class="rounded-lg border px-3 py-2"
                placeholder="Cari nama, email, atau no HP">

            <select name="role" class="rounded-lg border px-3 py-2">
                <option value="">Semua pengajuan</option>
                <option value="agen" {{ request('role') === 'agen' ? 'selected' : '' }}>Agen</option>
                <option value="pemilik" {{ request('role') === 'pemilik' ? 'selected' : '' }}>Pemilik Produk</option>
            </select>

            <select name="status" class="rounded-lg border px-3 py-2">
                <option value="">Pending saja</option>
                @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.role-requests.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Kontak</th>
                        <th class="p-3 text-left">Pengajuan</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Listing</th>
                        <th class="p-3 text-left">Diajukan</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $user)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="min-w-56 p-3">
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                @if($user->address)
                                    <p class="mt-1 line-clamp-2 text-xs text-gray-500">{{ $user->address }}</p>
                                @endif
                            </td>

                            <td class="min-w-44 p-3">
                                <p class="text-gray-700">{{ $user->phone ?: '-' }}</p>
                            </td>

                            <td class="p-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->requested_role === 'agen' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $user->requested_role === 'agen' ? 'Agen' : 'Pemilik Produk' }}
                                </span>
                            </td>

                            <td class="p-3">
                                @php
                                    $statusClass = match($user->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'pending' => 'bg-orange-100 text-orange-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($user->status ?? 'normal') }}
                                </span>
                            </td>

                            <td class="p-3 text-center font-semibold text-gray-900">
                                {{ $user->listings_count }}
                            </td>

                            <td class="min-w-36 p-3 text-gray-600">
                                {{ optional($user->updated_at)->format('d M Y H:i') }}
                            </td>

                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('admin.users', ['search' => $user->email]) }}"
                                        class="rounded bg-gray-100 px-3 py-1 text-gray-700 hover:bg-gray-200">
                                        Detail
                                    </a>

                                    @if($user->requested_role)
                                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                            @csrf
                                            <button class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700"
                                                data-swal-confirm="Setujui pengajuan {{ $user->name }} sebagai {{ $user->requested_role === 'agen' ? 'Agen' : 'Pemilik Produk' }}?"
                                                data-swal-confirm-button="Ya, setujui">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                            @csrf
                                            <button class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600"
                                                data-swal-confirm="Tolak pengajuan {{ $user->name }}?"
                                                data-swal-confirm-button="Ya, tolak">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                Belum ada pengajuan agen atau pemilik produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $requests->links() }}
    </div>
</div>
@endsection
