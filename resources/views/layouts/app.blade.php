<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arsantara Management') }}</title>
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
            max-width: 100%;
            overflow-x: hidden;
        }

        @supports (overflow: clip) {
            html,
            body {
                overflow-x: clip;
            }
        }

        body {
            max-width: 100%;
        }

        [x-cloak] {
            display: none !important;
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
        @stack('styles')
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
                            <img src="{{ asset('images/logo_fixx.png') }}"
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
        <div id="consultationWidget" class="fixed bottom-4 right-4 z-[70] sm:bottom-5 sm:right-5">
            <button type="button" id="consultationOpenButton" aria-label="Buka konsultasi gratis"
                class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#f3bd12] text-[#08234c] shadow-2xl shadow-slate-900/25 ring-8 ring-white transition hover:bg-[#e5ad05] focus:outline-none focus:ring-blue-100 sm:h-16 sm:w-16 sm:ring-[10px]">
                <svg class="h-7 w-7 sm:h-9 sm:w-9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M4 4.8C4 3.8 4.8 3 5.8 3h12.4c1 0 1.8.8 1.8 1.8v8.4c0 1-.8 1.8-1.8 1.8h-5.5l-4.8 3.7c-.6.5-1.5 0-1.5-.8V15h-.6c-1 0-1.8-.8-1.8-1.8V4.8Zm5 4.1a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Z" />
                </svg>
            </button>

            <div id="consultationOverlay" class="fixed inset-0 hidden bg-slate-950/60 backdrop-blur-sm md:hidden" aria-hidden="true"></div>

            <section id="consultationModal" aria-hidden="true" aria-labelledby="consultationTitle"
                class="fixed inset-x-5 bottom-5 mx-auto hidden max-h-[calc(100vh-2.5rem)] max-w-[340px] overflow-y-auto rounded-[1.5rem] bg-[#f5f5f5] text-slate-950 shadow-2xl md:inset-auto md:bottom-20 md:right-5 md:mx-0 md:w-[360px] md:max-w-none md:max-h-[calc(100vh-6rem)]">
                <div class="relative min-h-[240px] overflow-hidden rounded-t-[1.5rem] bg-[#08234c] px-5 pb-5 pt-5 text-white md:min-h-[210px] md:px-5 md:pb-5 md:pt-5">
                    <button type="button" id="consultationCloseButton" aria-label="Tutup konsultasi"
                        class="absolute right-4 top-4 z-30 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70 md:h-9 md:w-9">
                        <svg class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>

                    <svg class="absolute right-14 top-7 z-10 h-14 w-18 text-[#3d5a90]/80" viewBox="0 0 96 72" fill="none" stroke="currentColor" stroke-width="5" aria-hidden="true">
                        <path d="M18 12h54a14 14 0 0 1 14 14v14a14 14 0 0 1-14 14H41L20 65l5-11h-7A14 14 0 0 1 4 40V26a14 14 0 0 1 14-14Z" />
                        <path d="M31 34h.1M45 34h.1M59 34h.1" stroke-linecap="round" />
                    </svg>

                    <div class="relative z-20 max-w-[11rem]">
                        <p id="consultationTitle" class="text-[1.85rem] font-black leading-[0.92] tracking-normal text-white md:text-[1.75rem]">KONSULTASI</p>
                        <p class="mt-1 text-[2.45rem] font-black leading-[0.88] tracking-normal text-[#f4c20d] md:text-[2.3rem]">GRATIS</p>
                        <p class="mt-3 max-w-[10.5rem] text-xs font-medium leading-5 text-white/90">
                            Butuh info lebih lanjut? Kami siap membantu Anda!
                        </p>
                    </div>

                    <div class="absolute bottom-0 right-[-20px] z-0 h-[132px] w-[170px] rounded-tl-full bg-[#f4c20d]"></div>
                    <img src="{{ asset('images/consultation-agents.png') }}"
                        alt="Tim konsultasi Arsantara Management"
                        class="absolute bottom-0 right-[-34px] z-10 h-[190px] w-[200px] object-contain object-bottom">
                </div>

                <div class="relative px-3 pb-3">
                    <div class="-mt-4 w-full space-y-0 overflow-hidden rounded-2xl bg-white/95 shadow-xl ring-1 ring-slate-200">
                        <div class="flex items-center gap-3 px-3 py-2.5">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M5 5.2C5 4.5 5.5 4 6.2 4h11.6c.7 0 1.2.5 1.2 1.2v7.1c0 .7-.5 1.2-1.2 1.2h-6.2l-4.2 3.2c-.4.3-1 .1-1-.5v-2.7h-.2c-.7 0-1.2-.5-1.2-1.2V5.2Zm4 3.2a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm3 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm3 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[13px] font-black leading-tight text-[#081b43]">Punya pertanyaan?</p>
                                <p class="mt-1 text-xs font-medium leading-tight text-slate-600">Kami siap jawab</p>
                            </div>
                        </div>

                        <div class="h-px bg-slate-200"></div>

                        <div class="flex items-center gap-3 px-3 py-2.5">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path d="M7 4h10v16H7z" />
                                    <path d="M10 8h4M10 12h4M10 16h2" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[13px] font-black leading-tight text-[#081b43]">Butuh saran terbaik?</p>
                                <p class="mt-1 text-xs font-medium leading-tight text-slate-600">Kami bantu carikan solusi</p>
                            </div>
                        </div>

                        <div class="h-px bg-slate-200"></div>

                        <div class="flex items-center gap-3 px-3 py-2.5">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.8 5 5.7v5.2c0 4.5 2.9 8.6 7 10.1 4.1-1.5 7-5.6 7-10.1V5.7l-7-2.9Zm3.7 7-4.3 4.3-2-2 1.3-1.3.7.7 3-3 1.3 1.3Z" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[13px] font-black leading-tight text-[#081b43]">Aman & Terpercaya</p>
                                <p class="mt-1 text-xs font-medium leading-tight text-slate-600">Pelayanan cepat & ramah</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-3 rounded-2xl bg-white p-3 shadow-2xl ring-1 ring-slate-200">
                        <div class="flex items-center gap-3 px-1">
                            <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#f4c20d] text-[#08234c]">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.35" aria-hidden="true">
                                    <path d="M4 12a8 8 0 0 1 16 0" />
                                    <path d="M4 12v3a2 2 0 0 0 2 2h1v-6H6a2 2 0 0 0-2 2Zm16 0v3a2 2 0 0 1-2 2h-1v-6h1a2 2 0 0 1 2 2Zm-7 7h2" />
                                    <path d="M17 17c0 1.1-.9 2-2 2" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[15px] font-black leading-[1.05] text-[#081b43]">Bingung atau hanya ingin tanya-tanya?</p>
                                <p class="mt-1 text-sm font-medium leading-tight text-[#123264]">Hubungi kami sekarang!</p>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-end pr-9 text-[#f4c20d] md:hidden">
                            <svg class="h-11 w-11" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M34 12c7 10 2 20-12 24" />
                                <path d="m24 25-2 11 10-4" />
                            </svg>
                        </div>

                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                            class="mt-3 flex min-h-11 w-full items-center justify-center gap-3 rounded-xl bg-green-500 px-4 py-2 text-center text-white shadow-lg shadow-green-500/25 transition hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-200">
                            <svg class="h-7 w-7 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.1 3a8.7 8.7 0 0 0-7.5 13.1L3.5 21l5-1.3A8.7 8.7 0 1 0 12.1 3Zm0 15.7c-1.4 0-2.8-.4-4-1.2l-.3-.2-2.5.7.7-2.4-.2-.4a7 7 0 1 1 6.3 3.5Zm3.9-5.2c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.5.1-.2.2-.6.7-.7.9-.1.1-.3.2-.5.1a5.7 5.7 0 0 1-2.8-2.5c-.2-.3 0-.4.1-.6l.4-.4c.1-.2.1-.3.2-.5v-.4c-.1-.1-.5-1.2-.7-1.6-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 2s.9 2.3 1 2.5c.1.2 1.8 2.8 4.4 3.9.6.3 1.1.4 1.5.5.6.2 1.2.1 1.6.1.5-.1 1.3-.5 1.5-1 .2-.5.2-1 .2-1.1 0-.1-.2-.2-.4-.3Z" />
                            </svg>
                            <span class="leading-tight">
                                <span class="block text-lg font-black">HUBUNGI KAMI</span>
                                <span class="block text-xs font-medium">via WhatsApp</span>
                            </span>
                        </a>

                        <div class="mt-3 grid grid-cols-2 divide-x divide-slate-200 text-center text-xs font-semibold text-[#123264]">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5 text-[#082b5c]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.8 5 5.7v5.2c0 4.5 2.9 8.6 7 10.1 4.1-1.5 7-5.6 7-10.1V5.7l-7-2.9Zm3.7 7-4.3 4.3-2-2 1.3-1.3.7.7 3-3 1.3 1.3Z" />
                                </svg>
                                Privasi Aman
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5 text-[#082b5c]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="m13 2-8 12h6l-1 8 9-13h-6l1-7Z" />
                                </svg>
                                Respon Cepat
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        <x-sweet-alert />
        @stack('scripts')
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
        AOS.init({
            duration: 800,
            once: true
        });

        const consultationOpenButton = document.getElementById('consultationOpenButton');
        const consultationCloseButton = document.getElementById('consultationCloseButton');
        const consultationModal = document.getElementById('consultationModal');
        const consultationOverlay = document.getElementById('consultationOverlay');

        function openConsultationModal() {
            consultationModal?.classList.remove('hidden');
            consultationOverlay?.classList.remove('hidden');
            consultationModal?.setAttribute('aria-hidden', 'false');
            consultationOverlay?.setAttribute('aria-hidden', 'false');
        }

        function closeConsultationModal() {
            consultationModal?.classList.add('hidden');
            consultationOverlay?.classList.add('hidden');
            consultationModal?.setAttribute('aria-hidden', 'true');
            consultationOverlay?.setAttribute('aria-hidden', 'true');
        }

        consultationOpenButton?.addEventListener('click', openConsultationModal);
        consultationCloseButton?.addEventListener('click', closeConsultationModal);
        consultationOverlay?.addEventListener('click', closeConsultationModal);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeConsultationModal();
            }
        });
        </script>
    </body>
</html>
