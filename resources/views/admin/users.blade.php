@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Semua User</h1>
        </div>

    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pengajuan</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['requests'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white border rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
    </div>

    <div class="bg-white border rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                class="border rounded-lg px-3 py-2"
                placeholder="Cari nama atau email">

            <select name="role" class="border rounded-lg px-3 py-2">
                <option value="">Semua role</option>
                @foreach(['user' => 'User', 'admin' => 'Admin', 'agen' => 'Agen', 'pemilik' => 'Pemilik'] as $value => $label)
                    <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="status" class="border rounded-lg px-3 py-2">
                <option value="">Semua status</option>
                @foreach(['normal' => 'Normal', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
                <a href="{{ route('admin.users') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Pengajuan</th>
                        <th class="p-3 text-center">Listing</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 min-w-48">
                                <input form="update-user-{{ $user->id }}" type="text" name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full border rounded px-2 py-1">
                            </td>

                            <td class="p-3 min-w-56">
                                <input form="update-user-{{ $user->id }}" type="email" name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full border rounded px-2 py-1">
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="role"
                                    class="w-full border rounded px-2 py-1"
                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                    @foreach(['user' => 'User', 'admin' => 'Admin', 'agen' => 'Agen', 'pemilik' => 'Pemilik'] as $value => $label)
                                        <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>

                                @if(auth()->id() === $user->id)
                                    <input form="update-user-{{ $user->id }}" type="hidden" name="role" value="admin">
                                @endif
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="status" class="w-full border rounded px-2 py-1">
                                    @foreach(['normal' => 'Normal', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                                        <option value="{{ $value }}" {{ $user->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="requested_role" class="w-full border rounded px-2 py-1">
                                    <option value="">Tidak ada</option>
                                    <option value="agen" {{ $user->requested_role === 'agen' ? 'selected' : '' }}>Agen</option>
                                    <option value="pemilik" {{ $user->requested_role === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                                </select>
                            </td>

                            <td class="p-3 text-center font-semibold">
                                {{ $user->listings_count }}
                            </td>

                            <td class="p-3">
                                <form id="update-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    @method('PATCH')
                                </form>

                                <div class="flex flex-wrap justify-center gap-2">
                                    <button form="update-user-{{ $user->id }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                        Simpan
                                    </button>

                                    @if($user->requested_role)
                                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                            @csrf
                                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
                                                onclick="return confirm('Setujui pengajuan role user ini?')">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                            @csrf
                                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                                                onclick="return confirm('Tolak pengajuan role user ini?')">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                                            {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                            onclick="return confirm('Hapus user ini? Listing miliknya juga akan terhapus.')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                Tidak ada user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $users->links() }}
    </div>
</div>
@endsection
