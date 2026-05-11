@extends('layouts.admin')

@section('admin_content')
@php
    $statusClass = [
        'pending' => 'bg-yellow-100 text-yellow-700',
        'accepted' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ][$jobApplication->status] ?? 'bg-gray-100 text-gray-700';

    $statusLabel = [
        'pending' => 'Menunggu',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
    ][$jobApplication->status] ?? ucfirst($jobApplication->status);
@endphp

<div>
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm text-gray-500">Admin / Karir / Pelamar</p>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pelamar</h1>
        </div>
        <a href="{{ route('admin.careers.applications') }}" class="rounded bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300">
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="space-y-6">
            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b pb-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-950">{{ $jobApplication->full_name }}</h2>
                        <p class="mt-1 text-gray-600">{{ $jobApplication->email }} - {{ $jobApplication->phone }}</p>
                    </div>
                    <span class="rounded px-3 py-1 text-sm font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Lowongan</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->jobVacancy->title ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Tanggal Apply</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">NIK</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->nik }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Jenis Kelamin & Tanggal Lahir</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->gender }}, {{ $jobApplication->birth_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Sumber Informasi</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->source }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Ekspektasi Gaji</p>
                        <p class="mt-1 font-semibold text-gray-900">Rp {{ number_format($jobApplication->expected_salary, 0, ',', '.') }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Domisili</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Provinsi</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->province }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Kabupaten/Kota</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->city }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Kecamatan</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->district }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Kelurahan/Desa</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->village }}</p>
                    </div>
                </div>
                <p class="mt-4 whitespace-pre-line rounded bg-gray-50 p-4 text-gray-700">{{ $jobApplication->domicile_address }}</p>
            </section>

            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Riwayat Pendidikan</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Jenjang</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->education_level }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Instansi</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->education_institution }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">Jurusan</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->major }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-500">GPA / IPK</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $jobApplication->gpa }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Pengalaman Kerja</h2>
                <p class="mt-4 whitespace-pre-line rounded bg-gray-50 p-4 leading-7 text-gray-700">{{ $jobApplication->work_experience }}</p>
            </section>
        </div>

        <aside class="space-y-4">
            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Action</h2>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('admin.careers.applications.cv', $jobApplication) }}"
                        class="flex w-full items-center justify-center rounded bg-blue-600 px-4 py-3 font-bold text-white hover:bg-blue-700">
                        Download CV
                    </a>
                    <form action="{{ route('admin.careers.applications.accept', $jobApplication) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="w-full rounded bg-green-600 px-4 py-3 font-bold text-white hover:bg-green-700"
                            data-swal-confirm="Terima pelamar ini?"
                            data-swal-confirm-button="Ya, terima">
                            Terima Pelamar
                        </button>
                    </form>
                    <form action="{{ route('admin.careers.applications.reject', $jobApplication) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="w-full rounded bg-red-600 px-4 py-3 font-bold text-white hover:bg-red-700"
                            data-swal-confirm="Tolak pelamar ini?"
                            data-swal-confirm-button="Ya, tolak">
                            Tolak Pelamar
                        </button>
                    </form>
                    <form action="{{ route('admin.careers.applications.destroy', $jobApplication) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="w-full rounded border border-red-200 bg-red-50 px-4 py-3 font-bold text-red-700 hover:bg-red-100"
                            data-swal-confirm="Hapus data pelamar dan file CV?"
                            data-swal-confirm-button="Ya, hapus">
                            Hapus Data Pelamar
                        </button>
                    </form>
                </div>
            </section>

            <section class="rounded-lg border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Persetujuan</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <p class="flex items-center justify-between gap-3">
                        <span>Pernyataan Pelamar</span>
                        <span class="font-bold {{ $jobApplication->statement_accepted ? 'text-green-700' : 'text-red-700' }}">
                            {{ $jobApplication->statement_accepted ? 'Setuju' : 'Tidak' }}
                        </span>
                    </p>
                    <p class="flex items-center justify-between gap-3">
                        <span>Kebijakan Privasi</span>
                        <span class="font-bold {{ $jobApplication->privacy_accepted ? 'text-green-700' : 'text-red-700' }}">
                            {{ $jobApplication->privacy_accepted ? 'Setuju' : 'Tidak' }}
                        </span>
                    </p>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection
