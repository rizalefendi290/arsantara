@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6">
        <p class="text-sm text-gray-500">Admin</p>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Lowongan Karir</h1>
    </div>

    <div class="mb-6 flex flex-wrap gap-2 rounded-lg border bg-white p-2 shadow-sm">
        <a href="{{ route('admin.careers.index') }}"
            class="rounded-md px-4 py-2 text-sm font-bold {{ request()->routeIs('admin.careers.index') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
            Lowongan
        </a>
        <a href="{{ route('admin.careers.applications') }}"
            class="rounded-md px-4 py-2 text-sm font-bold {{ request()->routeIs('admin.careers.applications*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
            Pelamar
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Tambah Lowongan</h2>

        <form action="{{ route('admin.careers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Judul Lowongan</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded border p-2" placeholder="Contoh: Marketing Staff / BLITAR" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Tipe Pekerjaan</label>
                    <input type="text" name="employment_type" value="{{ old('employment_type', 'Staff') }}" class="w-full rounded border p-2" placeholder="Staff, Full-time, Internship">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded border p-2" placeholder="Contoh: BLITAR">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full rounded border p-2">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Link Lamar</label>
                    <input type="text" name="apply_url" value="{{ old('apply_url') }}" class="w-full rounded border p-2" placeholder="https://... atau wa.me/628...">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded border p-2">
                </div>

                <label class="flex items-end gap-2 pb-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="rounded" checked>
                    Aktif
                </label>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
                    <textarea name="description" rows="5" class="w-full rounded border p-2" placeholder="Tulis ringkasan tugas dan tanggung jawab.">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Kualifikasi</label>
                    <textarea name="requirements" rows="5" class="w-full rounded border p-2" placeholder="Tulis syarat atau kualifikasi kandidat.">{{ old('requirements') }}</textarea>
                </div>
            </div>

            <button class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                Simpan Lowongan
            </button>
        </form>
    </div>

    <div class="rounded-lg border bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-gray-900">Daftar Lowongan</h2>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            @forelse($vacancies as $vacancy)
                <div class="rounded-lg border border-gray-100 p-4">
                    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-950">{{ $vacancy->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                            {{ $vacancy->employment_type ?: 'Staff' }} &bull;
                            {{ $vacancy->location ?: 'Fleksibel' }} &bull;
                            {{ $vacancy->deadline ? $vacancy->deadline->format('d/m/Y') : 'Tanpa deadline' }} &bull;
                            {{ $vacancy->applications_count }} pelamar
                        </p>
                        </div>
                        <span class="rounded px-2 py-1 text-xs font-semibold {{ $vacancy->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $vacancy->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <form action="{{ route('admin.careers.update', $vacancy->id) }}" method="POST" class="space-y-3 border-t pt-4">
                        @csrf
                        @method('PATCH')

                        <input type="text" name="title" value="{{ $vacancy->title }}" class="w-full rounded border p-2 text-sm" required>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <input type="text" name="employment_type" value="{{ $vacancy->employment_type }}" class="rounded border p-2 text-sm" placeholder="Tipe pekerjaan">
                            <input type="text" name="location" value="{{ $vacancy->location }}" class="rounded border p-2 text-sm" placeholder="Lokasi">
                            <input type="date" name="deadline" value="{{ optional($vacancy->deadline)->format('Y-m-d') }}" class="rounded border p-2 text-sm">
                            <input type="text" name="apply_url" value="{{ $vacancy->apply_url }}" class="rounded border p-2 text-sm" placeholder="Link lamar">
                            <input type="number" name="sort_order" value="{{ $vacancy->sort_order }}" min="0" class="rounded border p-2 text-sm">
                            <label class="flex items-center gap-2 rounded border p-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="is_active" value="1" class="rounded" {{ $vacancy->is_active ? 'checked' : '' }}>
                                Aktif
                            </label>
                        </div>

                        <textarea name="description" rows="4" class="w-full rounded border p-2 text-sm" placeholder="Deskripsi">{{ $vacancy->description }}</textarea>
                        <textarea name="requirements" rows="4" class="w-full rounded border p-2 text-sm" placeholder="Kualifikasi">{{ $vacancy->requirements }}</textarea>

                        <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </form>

                    <form action="{{ route('admin.careers.destroy', $vacancy->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="w-full rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                            data-swal-confirm="Hapus lowongan ini?"
                            data-swal-confirm-button="Ya, hapus">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="rounded-lg bg-gray-50 p-6 text-center text-gray-500 xl:col-span-2">
                    Belum ada lowongan pekerjaan.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
