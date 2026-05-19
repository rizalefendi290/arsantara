@extends('layouts.app')

@section('content')
<section class="bg-slate-50">
    <div class="mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid w-full overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-900/10 ring-1 ring-slate-200 lg:grid-cols-5">
            <div class="relative isolate bg-[#08234c] px-6 py-8 text-white sm:px-8 lg:col-span-2 lg:py-10">
                <div class="absolute inset-x-0 bottom-0 h-28 bg-[#f4c20d]"></div>
                <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full border border-white/15"></div>
                <div class="absolute right-8 top-10 h-20 w-20 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white">
                                <img src="{{ asset('images/logo_fixx.png') }}" alt="Arsantara Management" class="h-10 w-10 object-contain">
                            </span>
                            <span>
                                <span class="block text-base font-extrabold">Arsantara Management</span>
                                <span class="block text-xs font-semibold text-blue-100">Properti dan kendaraan terpercaya</span>
                            </span>
                        </a>

                        <div class="mt-10">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#f4c20d]">Akun baru</p>
                            <h1 class="mt-3 text-3xl font-black leading-tight tracking-normal sm:text-4xl">
                                Mulai jelajahi Arsantara
                            </h1>
                            <p class="mt-4 max-w-sm text-sm leading-6 text-blue-100">
                                Daftar untuk menyimpan profil, mengirim ulasan, dan mengajukan akses sebagai agen atau pemilik produk.
                            </p>
                        </div>
                    </div>

                    <div class="relative z-10 rounded-xl bg-white/10 p-4 backdrop-blur">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f4c20d] text-[#08234c]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14" stroke-linecap="round" />
                                    <path d="M12 5v14" stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold">Role awal pengguna</p>
                                <p class="mt-1 text-xs leading-5 text-blue-100">Setelah daftar, Anda bisa upgrade role dari halaman profil bila diperlukan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center px-6 py-8 sm:px-8 lg:col-span-3 lg:px-10 lg:py-12">
                <div class="w-full max-w-md">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Daftar akun</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-normal text-slate-950 sm:text-3xl">Buat akun Arsantara</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Isi data berikut untuk membuat akun baru. Password minimal 8 karakter.
                        </p>
                    </div>

                    @if ($errors->register->any() || $errors->any())
                        <div class="mt-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">Pendaftaran belum berhasil.</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach (($errors->register->any() ? $errors->register : $errors)->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold text-slate-900">Nama</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                placeholder="Nama lengkap"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-slate-900">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="nama@email.com"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="register-page-password" class="mb-2 block text-sm font-bold text-slate-900">Password</label>
                            <div class="relative">
                                <input id="register-page-password" type="password" name="password" required autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 pr-12 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                <button type="button" onclick="togglePasswordVisibility('register-page-password', this)"
                                    class="absolute inset-y-0 right-4 inline-flex items-center text-slate-400 transition hover:text-blue-600 focus:outline-none"
                                    aria-label="Tampilkan password" aria-pressed="false">
                                    <svg data-eye-open class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg data-eye-closed class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18" stroke-linecap="round" />
                                        <path d="M10.7 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.1 4.2" />
                                        <path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.2-.9" />
                                        <path d="M9.9 9.9A3 3 0 0 0 14.1 14.1" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="register-page-password-confirmation" class="mb-2 block text-sm font-bold text-slate-900">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="register-page-password-confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 pr-12 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                <button type="button" onclick="togglePasswordVisibility('register-page-password-confirmation', this)"
                                    class="absolute inset-y-0 right-4 inline-flex items-center text-slate-400 transition hover:text-blue-600 focus:outline-none"
                                    aria-label="Tampilkan konfirmasi password" aria-pressed="false">
                                    <svg data-eye-open class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg data-eye-closed class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18" stroke-linecap="round" />
                                        <path d="M10.7 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.1 4.2" />
                                        <path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.2-.9" />
                                        <path d="M9.9 9.9A3 3 0 0 0 14.1 14.1" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            Daftar sekarang
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M5 12h14" stroke-linecap="round" />
                                <path d="m13 6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>

                    <div class="mt-7 flex flex-col gap-3 border-t border-slate-100 pt-5 text-sm sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('home') }}" class="font-semibold text-slate-600 transition hover:text-blue-700">Kembali ke beranda</a>
                        <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" class="text-left font-semibold text-blue-700 transition hover:text-blue-800">
                            Sudah punya akun?
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
