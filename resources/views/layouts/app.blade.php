<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @yield('meta')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />

        <!-- Scripts -->
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <style>
        html {
            scroll-behavior: smooth;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Hilangkan scrollbar tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Skeleton loading */
        .skeleton {
            animation: pulse 1.5s infinite;
            background: linear-gradient(90deg, #eee, #f5f5f5, #eee);
            background-size: 200% 100%;
        }

        @keyframes pulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95);}
            to { opacity: 1; transform: scale(1);}
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease;
        }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-white via-blue-50 to-blue-100 relative overflow-x-hidden">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')
            @include('components.modal-upgrade')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pt-0">
                @yield('content')
            </main>
        </div>

        <footer class="border-t border-blue-100 bg-white">
            <div data-aos="fade-up" class="mx-auto w-full max-w-screen-xl px-4 py-10 lg:px-6 lg:py-12">
                <div class="grid gap-10 lg:grid-cols-[1.4fr_2fr]">
                    <div>
                        <a href="{{ route('home') }}" class="flex items-center gap-4">
                            <img src="{{ asset('images/logo fi.png') }}"
                                class="h-16 w-16 rounded-2xl object-contain"
                                alt="Arsantara Management">
                            <div>
                                <p class="text-xl font-extrabold text-gray-950">Arsantara Management</p>
                                <p class="text-sm font-semibold text-blue-700">Properti dan kendaraan terpercaya</p>
                            </div>
                        </a>

                        <p class="mt-5 max-w-md text-sm leading-6 text-gray-600">
                            Platform untuk menemukan rumah, tanah, mobil, motor, dan peluang pinjaman dana dalam satu ekosistem yang mudah dikelola.
                        </p>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Rumah</span>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Tanah</span>
                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">Mobil</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Motor</span>
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <a href="#" aria-label="Instagram Arsantara Management"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-pink-100 bg-pink-50 text-pink-600 transition hover:bg-pink-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="5" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                                </svg>
                            </a>
                            <a href="#" aria-label="TikTok Arsantara Management"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-gray-50 text-gray-900 transition hover:bg-gray-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M15.4 3c.3 2.5 1.7 4.1 4.1 4.3v3.1a7.6 7.6 0 0 1-4-1.2v6.4c0 3.2-2.3 5.4-5.6 5.4a5.2 5.2 0 0 1-5.4-5.2c0-3.3 2.7-5.6 6.3-5.2v3.2c-1.8-.3-3.1.5-3.1 2a2.1 2.1 0 0 0 2.2 2.1c1.5 0 2.4-.9 2.4-2.6V3h3.1Z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="Facebook Arsantara Management"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-blue-100 bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 8.8V7.4c0-.7.5-.9 1-.9h1.8V3.4L14.3 3C11.6 3 10 4.6 10 7.1v1.7H7.5V12H10v9h3.5v-9h2.7l.5-3.2H14Z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                        <div>
                            <h2 class="mb-4 text-sm font-bold uppercase text-gray-950">Produk</h2>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li><a href="{{ route('properti') }}" class="hover:text-blue-700">Properti</a></li>
                                <li><a href="{{ route('autoshow') }}" class="hover:text-blue-700">Autoshow</a></li>
                                <li><a href="{{ route('pinjaman.index') }}" class="hover:text-blue-700">Pinjaman Dana</a></li>
                                <li><a href="{{ route('search') }}" class="hover:text-blue-700">Cari Listing</a></li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-4 text-sm font-bold uppercase text-gray-950">Kategori</h2>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li><a href="{{ route('rumah.index') }}" class="hover:text-blue-700">Rumah</a></li>
                                <li><a href="{{ route('tanah.index') }}" class="hover:text-blue-700">Tanah</a></li>
                                <li><a href="{{ route('mobil.index') }}" class="hover:text-blue-700">Mobil</a></li>
                                <li><a href="{{ route('motor.index') }}" class="hover:text-blue-700">Motor</a></li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-4 text-sm font-bold uppercase text-gray-950">Akun</h2>
                            <ul class="space-y-3 text-sm text-gray-600">
                                @auth
                                    <li><a href="{{ route('profile.edit') }}" class="hover:text-blue-700">Profil Saya</a></li>
                                    @if(auth()->user()->role === 'admin')
                                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">Dashboard Admin</a></li>
                                    @elseif(auth()->user()->role === 'agen')
                                        <li><a href="{{ route('agent.dashboard') }}" class="hover:text-blue-700">Dashboard Agen</a></li>
                                    @elseif(auth()->user()->role === 'pemilik')
                                        <li><a href="{{ route('owner.dashboard') }}" class="hover:text-blue-700">Dashboard Pemilik</a></li>
                                    @else
                                        <li><button type="button" onclick="openUpgradeModal('agen')" class="text-left hover:text-blue-700">Jadi Agen</button></li>
                                    @endif
                                @else
                                    <li><button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" class="text-left hover:text-blue-700">Login</button></li>
                                    <li><button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal" onclick="showRegister()" class="text-left hover:text-blue-700">Daftar</button></li>
                                @endauth
                                <li><a href="{{ route('testimoni.index') }}" class="hover:text-blue-700">Ulasan</a></li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-4 text-sm font-bold uppercase text-gray-950">Bantuan</h2>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li><a href="{{ route('about') }}" class="hover:text-blue-700">Tentang Kami</a></li>
                                <li><a href="{{ route('faq') }}" class="hover:text-blue-700">FAQ</a></li>
                                <li><a href="{{ route('terms') }}" class="hover:text-blue-700">Syarat & Ketentuan</a></li>
                                <li><a href="{{ route('privacy') }}" class="hover:text-blue-700">Kebijakan Privasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-3 border-t border-gray-100 pt-6 text-sm text-gray-500 md:flex-row md:items-center md:justify-between">
                    <p>&copy; {{ date('Y') }} Arsantara Management. All rights reserved.</p>
                    <p>Rumah, tanah, mobil, dan motor dalam satu platform.</p>
                </div>
            </div>
        </footer>

        @php
            $whatsappNumber = preg_replace('/\D+/', '', config('services.whatsapp.admin_number', ''));
            $whatsappMessage = rawurlencode('Halo admin Arsantara Management, saya ingin bertanya tentang produk yang tersedia.');
            $whatsappUrl = $whatsappNumber ? "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}" : '#';
        @endphp
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Hubungi Arsantara Management via WhatsApp"
            class="fixed bottom-5 right-5 z-[70] inline-flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg shadow-green-500/30 ring-4 ring-white transition hover:bg-green-600 focus:outline-none focus:ring-green-200">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.1 3a8.7 8.7 0 0 0-7.5 13.1L3.5 21l5-1.3A8.7 8.7 0 1 0 12.1 3Zm0 15.7c-1.4 0-2.8-.4-4-1.2l-.3-.2-2.5.7.7-2.4-.2-.4a7 7 0 1 1 6.3 3.5Zm3.9-5.2c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.5.1-.2.2-.6.7-.7.9-.1.1-.3.2-.5.1a5.7 5.7 0 0 1-2.8-2.5c-.2-.3 0-.4.1-.6l.4-.4c.1-.2.1-.3.2-.5v-.4c-.1-.1-.5-1.2-.7-1.6-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 2s.9 2.3 1 2.5c.1.2 1.8 2.8 4.4 3.9.6.3 1.1.4 1.5.5.6.2 1.2.1 1.6.1.5-.1 1.3-.5 1.5-1 .2-.5.2-1 .2-1.1 0-.1-.2-.2-.4-.3Z" />
            </svg>
        </a>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        <x-sweet-alert />
        @stack('scripts')
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
        AOS.init({
            duration: 800,
            once: true
        });
        </script>
    </body>
</html>
