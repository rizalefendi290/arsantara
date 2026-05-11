@extends('layouts.admin')

@section('admin_content')
<div>
    <div class="mb-6">
        <p class="text-sm text-gray-500">Admin / Karir</p>
        <h1 class="text-2xl font-bold text-gray-900">Pelamar Lowongan Karir</h1>
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

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-lg border bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.careers.applications') }}" class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            <div>
                <label for="job_vacancy_id" class="mb-1 block text-sm font-semibold text-gray-700">Filter Lowongan</label>
                <select id="job_vacancy_id" name="job_vacancy_id" class="w-full rounded border p-2">
                    <option value="">Semua lowongan</option>
                    @foreach($vacancies as $vacancy)
                        <option value="{{ $vacancy->id }}" @selected($selectedVacancy === $vacancy->id)>{{ $vacancy->title }}</option>
                    @endforeach
                </select>
            </div>
            <button class="rounded bg-blue-600 px-5 py-2 font-semibold text-white hover:bg-blue-700">
                Terapkan
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
        <div class="border-b p-5">
            <h2 class="text-lg font-bold text-gray-900">Daftar Pelamar</h2>
            <p class="mt-1 text-sm text-gray-500">Data pelamar yang masuk dari form apply job.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Pelamar</th>
                        <th class="p-3 text-left">Lowongan</th>
                        <th class="p-3 text-left">Domisili</th>
                        <th class="p-3 text-left">Pendidikan</th>
                        <th class="p-3 text-left">Ekspektasi Gaji</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Tanggal Apply</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        @php
                            $statusClass = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'accepted' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ][$application->status] ?? 'bg-gray-100 text-gray-700';

                            $statusLabel = [
                                'pending' => 'Menunggu',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                            ][$application->status] ?? ucfirst($application->status);
                        @endphp
                        <tr class="border-t align-top hover:bg-gray-50">
                            <td class="p-3">
                                <p class="font-bold text-gray-950">{{ $application->full_name }}</p>
                                <p class="mt-1 text-gray-600">{{ $application->email }}</p>
                                <p class="text-gray-600">{{ $application->phone }}</p>
                                <p class="mt-1 text-xs text-gray-500">NIK: {{ $application->nik }}</p>
                                <p class="text-xs text-gray-500">{{ $application->gender }}, {{ optional($application->birth_date)->format('d/m/Y') }}</p>
                            </td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $application->jobVacancy->title ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">Sumber: {{ $application->source }}</p>
                            </td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $application->city }}</p>
                                <p class="text-gray-600">{{ $application->district }}, {{ $application->village }}</p>
                                <p class="text-gray-600">{{ $application->province }}</p>
                                <p class="mt-1 max-w-xs text-xs leading-5 text-gray-500">{{ $application->domicile_address }}</p>
                            </td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $application->education_level }} - {{ $application->major }}</p>
                                <p class="text-gray-600">{{ $application->education_institution }}</p>
                                <p class="text-xs text-gray-500">IPK: {{ $application->gpa }}</p>
                            </td>
                            <td class="p-3 font-bold text-gray-900">
                                Rp {{ number_format($application->expected_salary, 0, ',', '.') }}
                            </td>
                            <td class="p-3">
                                <span class="rounded px-2 py-1 text-xs font-bold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($application->reviewed_at)
                                    <p class="mt-1 text-xs text-gray-500">{{ $application->reviewed_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </td>
                            <td class="p-3 text-gray-600">
                                {{ $application->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('admin.careers.applications.show', $application) }}"
                                        class="rounded bg-yellow-700 px-3 py-2 text-xs font-bold text-white hover:bg-slate-800">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.careers.applications.cv', $application) }}"
                                        class="rounded bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">
                                        Download CV
                                    </a>
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('admin.careers.applications.accept', $application) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="w-full rounded bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700"
                                                data-swal-confirm="Terima pelamar ini?"
                                                data-swal-confirm-button="Ya, terima">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.careers.applications.reject', $application) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="w-full rounded bg-red-600 px-3 py-2 text-xs font-bold text-white hover:bg-red-700"
                                                data-swal-confirm="Tolak pelamar ini?"
                                                data-swal-confirm-button="Ya, tolak">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    <form action="{{ route('admin.careers.applications.destroy', $application) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-full rounded border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100"
                                            data-swal-confirm="Hapus data pelamar dan file CV?"
                                            data-swal-confirm-button="Ya, hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-t bg-gray-50">
                            <td colspan="8" class="p-3">
                                <p class="text-xs font-bold uppercase text-gray-500">Pengalaman Kerja</p>
                                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $application->work_experience }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                Belum ada pelamar yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t p-4">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
