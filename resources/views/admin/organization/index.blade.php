@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6">
        <p class="text-sm text-gray-500">Admin</p>
        <h1 class="text-2xl font-bold text-gray-900">Struktur Organisasi</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-6 rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Tambah Anggota</h2>

        <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Foto</label>
                    <input type="file" name="photo" class="w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded border p-2">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Profil Singkat</label>
                    <textarea name="profile" rows="3" class="w-full rounded border p-2" placeholder="Contoh: Bertanggung jawab mengelola strategi operasional dan kemitraan Arsantara.">{{ old('profile') }}</textarea>
                </div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="rounded" checked>
                    Tampilkan di halaman Tentang Kami
                </label>
            </div>

            <button class="mt-4 rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                Simpan Anggota
            </button>
        </form>
    </div>

    <div class="rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Daftar Anggota</h2>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @forelse($members as $member)
                <div class="rounded-lg border border-gray-100 p-4">
                    <div class="flex gap-4">
                        <img src="{{ $member->photo ? asset('storage/'.$member->photo) : asset('images/logo.png') }}"
                            alt="{{ $member->name }}"
                            class="h-24 w-24 rounded-lg object-cover">

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="truncate text-lg font-bold text-gray-900">{{ $member->name }}</h3>
                                <span class="rounded px-2 py-1 text-xs font-semibold {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="font-semibold text-blue-700">{{ $member->position }}</p>
                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $member->profile ?: 'Belum ada profil singkat.' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.organization.update', $member->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 border-t pt-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <input type="file" name="photo" class="rounded border p-2 text-sm">
                            <input type="text" name="name" value="{{ $member->name }}" class="rounded border p-2 text-sm" required>
                            <input type="text" name="position" value="{{ $member->position }}" class="rounded border p-2 text-sm" required>
                            <input type="number" name="sort_order" value="{{ $member->sort_order }}" min="0" class="rounded border p-2 text-sm">
                        </div>

                        <textarea name="profile" rows="3" class="w-full rounded border p-2 text-sm">{{ $member->profile }}</textarea>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="is_active" value="1" class="rounded" {{ $member->is_active ? 'checked' : '' }}>
                                Tampilkan
                            </label>

                            <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('admin.organization.destroy', $member->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="w-full rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                            data-swal-confirm="Hapus anggota struktur organisasi ini?"
                            data-swal-confirm-button="Ya, hapus">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="rounded-lg bg-gray-50 p-6 text-center text-gray-500 lg:col-span-2">
                    Belum ada data struktur organisasi.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
