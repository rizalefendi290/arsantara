@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="flex flex-col gap-3 mb-6 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-gray-500">Admin</p>
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Kelola Semua User</h1>
        </div>

    </div>

    @if(session('success'))
        <div class="px-4 py-3 mb-5 text-green-700 border border-green-200 rounded-lg bg-green-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="px-4 py-3 mb-5 text-red-700 border border-red-200 rounded-lg bg-red-50">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 mb-5 text-red-700 border border-red-200 rounded-lg bg-red-50">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 mb-6 lg:grid-cols-4">
        <div class="p-5 bg-white border rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="p-5 bg-white border rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Pengajuan</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['requests'] }}</p>
        </div>
        <div class="p-5 bg-white border rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="p-5 bg-white border rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
    </div>

    <div class="p-4 mb-6 bg-white border rounded-lg shadow-sm">
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}"
                class="px-3 py-2 border rounded-lg"
                placeholder="Cari nama atau email">

            <select name="role" class="px-3 py-2 border rounded-lg">
                <option value="">Semua role</option>
                @foreach(['user' => 'User', 'admin' => 'Admin', 'agen' => 'Agen', 'pemilik' => 'Pemilik', 'marketing' => 'Marketing'] as $value => $label)
                    <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="status" class="px-3 py-2 border rounded-lg">
                <option value="">Semua status</option>
                @foreach(['normal' => 'Normal', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden bg-white border rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-600 bg-gray-50">
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
                                    class="w-full px-2 py-1 border rounded">
                            </td>

                            <td class="p-3 min-w-56">
                                <input form="update-user-{{ $user->id }}" type="email" name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-2 py-1 border rounded">
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="role"
                                    class="w-full px-2 py-1 border rounded"
                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                    @foreach(['user' => 'User', 'admin' => 'Admin', 'agen' => 'Agen', 'pemilik' => 'Pemilik', 'marketing' => 'Marketing'] as $value => $label)
                                        <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>

                                @if(auth()->id() === $user->id)
                                    <input form="update-user-{{ $user->id }}" type="hidden" name="role" value="admin">
                                @endif
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="status" class="w-full px-2 py-1 border rounded">
                                    @foreach(['normal' => 'Normal', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                                        <option value="{{ $value }}" {{ $user->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="p-3 min-w-36">
                                <select form="update-user-{{ $user->id }}" name="requested_role" class="w-full px-2 py-1 border rounded">
                                    <option value="">Tidak ada</option>
                                    <option value="agen" {{ $user->requested_role === 'agen' ? 'selected' : '' }}>Agen</option>
                                    <option value="pemilik" {{ $user->requested_role === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                                </select>
                            </td>

                            <td class="p-3 font-semibold text-center">
                                {{ $user->listings_count }}
                            </td>

                            <td class="p-3">
                                <form id="update-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    @method('PATCH')
                                </form>

                                <div class="flex flex-wrap justify-center gap-2">
                                    <button form="update-user-{{ $user->id }}"
                                        class="px-3 py-1 text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Simpan
                                    </button>

                                    @if($user->requested_role)
                                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                            @csrf
                                            <button class="px-3 py-1 text-white bg-green-600 rounded hover:bg-green-700"
                                                data-swal-confirm="Setujui pengajuan role user ini?"
                                                data-swal-confirm-button="Ya, setujui">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                            @csrf
                                            <button class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600"
                                                data-swal-confirm="Tolak pengajuan role user ini?"
                                                data-swal-confirm-button="Ya, tolak">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700"
                                            {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                            data-swal-confirm="Hapus user ini? Listing miliknya juga akan terhapus."
                                            data-swal-confirm-button="Ya, hapus">
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
