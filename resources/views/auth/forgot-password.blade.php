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
                            <p class="text-xs font-bold uppercase tracking-wide text-[#f4c20d]">Bantuan akun</p>
                            <h1 class="mt-3 text-3xl font-black leading-tight tracking-normal sm:text-4xl">
                                Pulihkan akses akun Anda
                            </h1>
                            <p class="mt-4 max-w-sm text-sm leading-6 text-blue-100">
                                Kami akan mengirim link reset ke email terdaftar agar Anda bisa membuat password baru dengan aman.
                            </p>
                        </div>
                    </div>

                    <div class="relative z-10 rounded-xl bg-white/10 p-4 backdrop-blur">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f4c20d] text-[#08234c]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3 5 6v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10V6l-7-3Z" stroke-linejoin="round" />
                                    <path d="m9 12 2 2 4-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold">Link reset aman</p>
                                <p class="mt-1 text-xs leading-5 text-blue-100">Gunakan link dari email untuk mengganti password akun Arsantara Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center px-6 py-8 sm:px-8 lg:col-span-3 lg:px-10 lg:py-12">
                <div class="w-full max-w-md">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Lupa password</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-normal text-slate-950 sm:text-3xl">Kirim link reset</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Masukkan email akun Anda. Jika email terdaftar, link reset password akan dikirim ke inbox.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="mt-7 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-slate-900">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                placeholder="nama@email.com"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            @error('email')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            Kirim link reset password
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M5 12h14" stroke-linecap="round" />
                                <path d="m13 6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>

                    <div class="mt-7 flex flex-col gap-3 border-t border-slate-100 pt-5 text-sm sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('home') }}" class="font-semibold text-slate-600 transition hover:text-blue-700">Kembali ke beranda</a>
                        <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" class="text-left font-semibold text-blue-700 transition hover:text-blue-800">
                            Sudah ingat password?
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
