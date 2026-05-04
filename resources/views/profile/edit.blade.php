@extends('layouts.app')

@section('content')
@php
    $roleLabels = [
        'admin' => 'Admin',
        'agen' => 'Agen',
        'pemilik' => 'Pemilik',
        'user' => 'User',
    ];

    $statusClass = [
        'approved' => 'bg-green-100 text-green-700',
        'pending' => 'bg-yellow-100 text-yellow-700',
        'rejected' => 'bg-red-100 text-red-700',
        'normal' => 'bg-blue-100 text-blue-700',
    ][$user->status ?? 'normal'] ?? 'bg-gray-100 text-gray-700';

    $dashboardRoute = match($user->role) {
        'admin' => route('admin.dashboard'),
        'agen' => route('agent.dashboard'),
        'pemilik' => route('owner.dashboard'),
        default => route('home'),
    };

    $profilePhotoUrl = $user->profile_photo
        ? asset('storage/'.$user->profile_photo)
        : null;
@endphp

@php
    $heroSlides = [
        [
            'image' => asset('images/hero.png'),
            'label' => 'Profile Akun',
            'title' => 'Halo, '.$user->name,
            'text' => 'Kelola informasi akun, keamanan, status role, dan akses dashboard Anda dari satu halaman.',
        ],
        [
            'image' => asset('images/thumbnail_properti.png'),
            'label' => 'Akses Properti',
            'title' => 'Pantau aktivitas akun Anda',
            'text' => 'Perbarui data profil agar komunikasi dan akses layanan Arsantara berjalan lancar.',
        ],
        [
            'image' => asset('images/thumbnail_kendaraan.png'),
            'label' => 'Dashboard Pengguna',
            'title' => 'Kelola role dan status akun',
            'text' => 'Ajukan peningkatan role untuk menjadi agen atau pemilik produk saat dibutuhkan.',
        ],
    ];
@endphp

<x-hero-carousel :slides="$heroSlides" height="min-h-[380px]" inner-height="min-h-[380px]" content-width="max-w-3xl" />

<main class="bg-gradient-to-b from-blue-50 via-white to-white">
    <div class="mx-auto max-w-7xl px-6 py-12">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">{{ session('error') }}</div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[340px_minmax(0,1fr)]">
            <aside class="space-y-6">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        @if($profilePhotoUrl)
                            <img src="{{ $profilePhotoUrl }}"
                                class="h-16 w-16 rounded-2xl object-cover ring-2 ring-blue-100"
                                alt="{{ $user->name }}">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-extrabold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-sm text-gray-500">{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold uppercase text-blue-700">
                            {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                        </span>
                        <span class="rounded-full px-3 py-1 text-xs font-bold uppercase {{ $statusClass }}">
                            {{ ucfirst($user->status ?? 'normal') }}
                        </span>
                    </div>

                    <a href="{{ $dashboardRoute }}"
                        class="mt-5 block rounded-xl bg-blue-600 px-4 py-3 text-center font-semibold text-white hover:bg-blue-700">
                        Buka Dashboard
                    </a>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="font-bold text-gray-900">Kontak</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <p><span class="font-semibold text-gray-900">No. HP:</span> {{ $user->phone ?: 'Belum diisi' }}</p>
                        <p><span class="font-semibold text-gray-900">Alamat:</span> {{ $user->address ?: 'Belum diisi' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-blue-700">{{ $profileStats['total_listings'] }}</p>
                        <p class="text-xs text-gray-500">Listing</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-green-700">{{ $profileStats['active_listings'] }}</p>
                        <p class="text-xs text-gray-500">Aktif</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm">
                        <p class="text-2xl font-extrabold text-yellow-700">{{ $profileStats['pending_listings'] }}</p>
                        <p class="text-xs text-gray-500">Pending</p>
                    </div>
                </div>

                @if($user->role === 'user')
                    <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-gray-900">Upgrade role</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Ajukan akun sebagai agen atau pemilik agar bisa mengelola listing.</p>
                        <form action="{{ route('submit.request') }}" method="POST" class="mt-4 space-y-3">
                            @csrf
                            <select name="role" class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                <option value="agen">Agen</option>
                                <option value="pemilik">Pemilik</option>
                            </select>
                            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">
                                Ajukan Upgrade
                            </button>
                        </form>
                    </div>
                @elseif($user->status === 'pending')
                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6 text-yellow-800">
                        <h3 class="font-bold">Menunggu verifikasi</h3>
                        <p class="mt-2 text-sm leading-6">Role {{ $roleLabels[$user->role] ?? $user->role }} sedang menunggu persetujuan admin.</p>
                    </div>
                @endif
            </aside>

            <div class="space-y-6">
                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900">Informasi Profile</h2>
                    <p class="mt-1 text-sm text-gray-500">Perbarui nama, email, nomor HP, alamat, dan foto profil Anda.</p>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf
                        @method('patch')

                        <div class="flex flex-col gap-4 rounded-xl bg-blue-50 p-4 md:flex-row md:items-center">
                            @if($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}"
                                    class="h-20 w-20 rounded-2xl object-cover ring-2 ring-white"
                                    alt="{{ $user->name }}">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-600 text-3xl font-extrabold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="flex-1">
                                <label for="profile_photo" class="mb-1 block font-semibold text-gray-800">Foto Profil</label>
                                <input id="profile_photo" name="profile_photo" type="file"
                                    class="w-full rounded-xl border border-blue-100 bg-white px-4 py-3 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:font-semibold file:text-blue-700">
                                <p class="mt-1 text-xs text-gray-500">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="name" class="mb-1 block font-semibold text-gray-800">Nama</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" required>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label for="email" class="mb-1 block font-semibold text-gray-800">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" required>
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <label for="phone" class="mb-1 block font-semibold text-gray-800">Nomor HP</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Contoh: 081234567890">
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="mb-1 block font-semibold text-gray-800">Alamat</label>
                                <textarea id="address" name="address" rows="3"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="text-sm text-gray-600">
                                Email belum terverifikasi.
                                <button form="send-verification" class="font-semibold text-blue-700 hover:underline">
                                    Kirim ulang verifikasi
                                </button>
                            </p>
                        @endif

                        <div class="flex items-center gap-4">
                            <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                                Simpan Profile
                            </button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-600">Profile tersimpan.</p>
                            @endif
                        </div>
                    </form>
                </section>

                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900">Keamanan Akun</h2>
                    <p class="mt-1 text-sm text-gray-500">Gunakan password yang kuat dan tidak mudah ditebak.</p>

                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                        @csrf
                        @method('put')

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label for="current_password" class="mb-1 block font-semibold text-gray-800">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                            <div>
                                <label for="password" class="mb-1 block font-semibold text-gray-800">Password Baru</label>
                                <input id="password" name="password" type="password"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-1 block font-semibold text-gray-800">Konfirmasi</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">
                                Update Password
                            </button>
                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-green-600">Password diperbarui.</p>
                            @endif
                        </div>
                    </form>
                </section>

                <section class="rounded-2xl border border-red-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-red-700">Hapus Akun</h2>
                    <p class="mt-1 text-sm text-gray-500">Tindakan ini permanen. Masukkan password untuk konfirmasi.</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-5 flex flex-col gap-3 md:flex-row md:items-start">
                        @csrf
                        @method('delete')
                        <div class="flex-1">
                            <input name="password" type="password"
                                class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-red-500 focus:ring-red-500"
                                placeholder="Password akun">
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                        </div>
                        <button class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700"
                            data-swal-confirm="Yakin ingin menghapus akun ini?"
                            data-swal-confirm-button="Ya, hapus akun">
                            Hapus Akun
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection
