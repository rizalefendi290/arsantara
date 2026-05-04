@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6">
        <p class="text-sm text-gray-500">Admin</p>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Mitra</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-6 rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Tambah Mitra</h2>

        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Logo Mitra</label>
                    <input type="file" name="logo" class="w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Mitra</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border p-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Kategori</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="w-full rounded border p-2" placeholder="Contoh: Pembiayaan, Properti, Otomotif">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Website</label>
                    <input type="text" name="website_url" value="{{ old('website_url') }}" class="w-full rounded border p-2" placeholder="https://contoh.com">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded border p-2">
                </div>

                <label class="flex items-end gap-2 pb-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="rounded" checked>
                    Tampilkan di halaman Tentang Kami
                </label>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" class="w-full rounded border p-2" placeholder="Jelaskan hubungan mitra dengan Arsantara.">{{ old('description') }}</textarea>
                </div>
            </div>

            <button class="mt-4 rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                Simpan Mitra
            </button>
        </form>
    </div>

    <div class="rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Daftar Mitra</h2>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @forelse($partners as $partner)
                <div class="rounded-lg border border-gray-100 p-4">
                    <div class="flex gap-4">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-50 p-3">
                            <img src="{{ $partner->logo ? asset('storage/'.$partner->logo) : asset('images/logo.png') }}"
                                alt="{{ $partner->name }}"
                                class="max-h-full max-w-full object-contain">
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="truncate text-lg font-bold text-gray-900">{{ $partner->name }}</h3>
                                <span class="rounded px-2 py-1 text-xs font-semibold {{ $partner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="font-semibold text-blue-700">{{ $partner->category ?: 'Mitra Arsantara' }}</p>
                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $partner->description ?: 'Belum ada deskripsi.' }}</p>
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-600 hover:underline">
                                    {{ $partner->website_url }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 border-t pt-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <input type="file" name="logo" class="rounded border p-2 text-sm">
                            <input type="text" name="name" value="{{ $partner->name }}" class="rounded border p-2 text-sm" required>
                            <input type="text" name="category" value="{{ $partner->category }}" class="rounded border p-2 text-sm" placeholder="Kategori">
                            <input type="text" name="website_url" value="{{ $partner->website_url }}" class="rounded border p-2 text-sm" placeholder="Website">
                            <input type="number" name="sort_order" value="{{ $partner->sort_order }}" min="0" class="rounded border p-2 text-sm">
                        </div>

                        <textarea name="description" rows="3" class="w-full rounded border p-2 text-sm">{{ $partner->description }}</textarea>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="is_active" value="1" class="rounded" {{ $partner->is_active ? 'checked' : '' }}>
                                Tampilkan
                            </label>

                            <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="w-full rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                            data-swal-confirm="Hapus mitra ini?"
                            data-swal-confirm-button="Ya, hapus">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="rounded-lg bg-gray-50 p-6 text-center text-gray-500 lg:col-span-2">
                    Belum ada data mitra.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
