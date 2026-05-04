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
        <div id="consultationWidget" class="fixed bottom-5 right-5 z-[70]">
            <button type="button" id="consultationOpenButton" aria-label="Buka konsultasi gratis"
                class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#f3bd12] text-[#09264f] shadow-xl shadow-slate-900/20 ring-4 ring-white transition hover:bg-[#e5ad05] focus:outline-none focus:ring-blue-200">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M4 4.8C4 3.8 4.8 3 5.8 3h12.4c1 0 1.8.8 1.8 1.8v8.4c0 1-.8 1.8-1.8 1.8h-5.5l-4.8 3.7c-.6.5-1.5 0-1.5-.8V15h-.6c-1 0-1.8-.8-1.8-1.8V4.8Zm5 4.1a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Z" />
                </svg>
            </button>

            <div id="consultationOverlay" class="fixed inset-0 hidden bg-slate-950/55 backdrop-blur-sm md:hidden" aria-hidden="true"></div>

            <section id="consultationModal" aria-hidden="true" aria-labelledby="consultationTitle"
                class="fixed inset-x-4 bottom-4 hidden max-h-[calc(100vh-2rem)] overflow-y-auto rounded-[1.75rem] bg-white text-slate-950 shadow-2xl md:inset-auto md:bottom-24 md:right-5 md:w-[380px] md:max-h-[calc(100vh-7rem)]">
                <div class="relative overflow-hidden rounded-t-[1.75rem] bg-[#08234c] px-6 pb-32 pt-7 text-white md:pb-24">
                    <button type="button" id="consultationCloseButton" aria-label="Tutup konsultasi"
                        class="absolute right-4 top-4 z-20 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="relative z-10 max-w-[15rem]">
                        <p id="consultationTitle" class="text-4xl font-black leading-none tracking-normal md:text-3xl">KONSULTASI</p>
                        <p class="mt-1 text-5xl font-black leading-none tracking-normal text-[#f4c20d] md:text-4xl">GRATIS</p>
                        <p class="mt-5 text-lg font-medium leading-7 text-white/90 md:text-base">
                            Butuh info lebih lanjut? Kami siap membantu Anda.
                        </p>
                    </div>

                    <div class="absolute bottom-0 right-0 h-40 w-52 rounded-tl-full bg-[#f4c20d] md:h-32 md:w-44"></div>
                    <div class="absolute bottom-0 right-5 z-10 flex items-end gap-2 md:right-4">
                        <div class="flex h-36 w-24 items-end justify-center rounded-t-full bg-white/95 pt-3 shadow-lg md:h-28 md:w-20">
                            <div class="mb-8 h-16 w-16 rounded-full bg-slate-200 ring-4 ring-white md:h-14 md:w-14"></div>
                        </div>
                        <div class="flex h-40 w-24 items-end justify-center rounded-t-full bg-white/95 pt-3 shadow-lg md:h-32 md:w-20">
                            <div class="mb-10 h-16 w-16 rounded-full bg-slate-200 ring-4 ring-white md:h-14 md:w-14"></div>
                        </div>
                    </div>

                    <svg class="absolute right-8 top-9 h-20 w-20 text-blue-300/35 md:h-16 md:w-16" viewBox="0 0 80 80" fill="none" stroke="currentColor" stroke-width="5" aria-hidden="true">
                        <path d="M18 18h44a10 10 0 0 1 10 10v16a10 10 0 0 1-10 10H34L18 64l4-10h-4A10 10 0 0 1 8 44V28a10 10 0 0 1 10-10Z" />
                        <path d="M29 36h.1M40 36h.1M51 36h.1" stroke-linecap="round" />
                    </svg>
                </div>

                <div class="relative -mt-20 px-4 pb-4 md:-mt-14">
                    <div class="space-y-3 rounded-2xl bg-white/95 p-4 shadow-xl ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M5 5.2C5 4.5 5.5 4 6.2 4h11.6c.7 0 1.2.5 1.2 1.2v7.1c0 .7-.5 1.2-1.2 1.2h-6.2l-4.2 3.2c-.4.3-1 .1-1-.5v-2.7h-.2c-.7 0-1.2-.5-1.2-1.2V5.2Zm4 3.2a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm3 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm3 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-base font-extrabold text-[#081b43]">Punya pertanyaan?</p>
                                <p class="text-sm font-medium text-slate-600">Kami siap jawab</p>
                            </div>
                        </div>

                        <div class="h-px bg-slate-200"></div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path d="M7 4h10v16H7z" />
                                    <path d="M10 8h4M10 12h4M10 16h2" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-base font-extrabold text-[#081b43]">Butuh saran terbaik?</p>
                                <p class="text-sm font-medium text-slate-600">Kami bantu carikan solusi</p>
                            </div>
                        </div>

                        <div class="h-px bg-slate-200"></div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#082b5c] text-white">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.8 5 5.7v5.2c0 4.5 2.9 8.6 7 10.1 4.1-1.5 7-5.6 7-10.1V5.7l-7-2.9Zm3.7 7-4.3 4.3-2-2 1.3-1.3.7.7 3-3 1.3 1.3Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-base font-extrabold text-[#081b43]">Aman & Terpercaya</p>
                                <p class="text-sm font-medium text-slate-600">Pelayanan cepat & ramah</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-4 rounded-2xl bg-white p-4 shadow-xl ring-1 ring-slate-200">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#f4c20d] text-[#08234c]">
                                <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                    <path d="M4 12a8 8 0 0 1 16 0" />
                                    <path d="M4 12v3a2 2 0 0 0 2 2h1v-6H6a2 2 0 0 0-2 2Zm16 0v3a2 2 0 0 1-2 2h-1v-6h1a2 2 0 0 1 2 2Zm-7 7h2" />
                                    <path d="M17 17c0 1.1-.9 2-2 2" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xl font-black leading-tight text-[#081b43] md:text-lg">Bingung atau hanya ingin tanya-tanya?</p>
                                <p class="mt-1 text-base font-medium text-[#123264] md:text-sm">Hubungi kami sekarang!</p>
                            </div>
                        </div>

                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                            class="mt-4 flex min-h-14 w-full items-center justify-center gap-3 rounded-xl bg-green-500 px-4 py-3 text-center text-white shadow-lg shadow-green-500/25 transition hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-200">
                            <svg class="h-8 w-8 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.1 3a8.7 8.7 0 0 0-7.5 13.1L3.5 21l5-1.3A8.7 8.7 0 1 0 12.1 3Zm0 15.7c-1.4 0-2.8-.4-4-1.2l-.3-.2-2.5.7.7-2.4-.2-.4a7 7 0 1 1 6.3 3.5Zm3.9-5.2c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.5.1-.2.2-.6.7-.7.9-.1.1-.3.2-.5.1a5.7 5.7 0 0 1-2.8-2.5c-.2-.3 0-.4.1-.6l.4-.4c.1-.2.1-.3.2-.5v-.4c-.1-.1-.5-1.2-.7-1.6-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 2s.9 2.3 1 2.5c.1.2 1.8 2.8 4.4 3.9.6.3 1.1.4 1.5.5.6.2 1.2.1 1.6.1.5-.1 1.3-.5 1.5-1 .2-.5.2-1 .2-1.1 0-.1-.2-.2-.4-.3Z" />
                            </svg>
                            <span class="leading-tight">
                                <span class="block text-2xl font-black md:text-xl">HUBUNGI KAMI</span>
                                <span class="block text-base font-medium md:text-sm">via WhatsApp</span>
                            </span>
                        </a>

                        <div class="mt-4 grid grid-cols-2 divide-x divide-slate-200 text-center text-sm font-semibold text-[#123264]">
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
