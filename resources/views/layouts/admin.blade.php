<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') - {{ config('app.name', 'Arsantara Management') }}</title>
    @yield('meta')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 20rem;
        }

        [x-cloak] {
            display: none !important;
        }

        .fi-admin-shell {
            font-family: Figtree, ui-sans-serif, system-ui, sans-serif;
        }

        .fi-admin-shell input,
        .fi-admin-shell select,
        .fi-admin-shell textarea {
            color: #0f172a;
            background-color: #ffffff;
        }

        .fi-admin-shell input::placeholder,
        .fi-admin-shell textarea::placeholder {
            color: #94a3b8;
            opacity: 1;
        }

        .fi-admin-shell table {
            width: 100%;
        }

        .dark .fi-admin-shell .bg-white,
        .dark .fi-admin-shell .bg-white\/70,
        .dark .fi-admin-shell .bg-white\/95 {
            background-color: #111827 !important;
        }

        .dark .fi-admin-shell .bg-gray-50,
        .dark .fi-admin-shell .bg-slate-50 {
            background-color: #1f2937 !important;
        }

        .dark .fi-admin-shell .bg-gray-100,
        .dark .fi-admin-shell .bg-gray-200,
        .dark .fi-admin-shell .bg-slate-100,
        .dark .fi-admin-shell .bg-slate-200 {
            background-color: #374151 !important;
        }

        .dark .fi-admin-shell .text-gray-950,
        .dark .fi-admin-shell .text-gray-900,
        .dark .fi-admin-shell .text-gray-800,
        .dark .fi-admin-shell .text-slate-950,
        .dark .fi-admin-shell .text-slate-900,
        .dark .fi-admin-shell .text-slate-800 {
            color: #f9fafb !important;
        }

        .dark .fi-admin-shell .text-gray-700,
        .dark .fi-admin-shell .text-gray-600,
        .dark .fi-admin-shell .text-slate-700,
        .dark .fi-admin-shell .text-slate-600 {
            color: #d1d5db !important;
        }

        .dark .fi-admin-shell .text-gray-500,
        .dark .fi-admin-shell .text-gray-400,
        .dark .fi-admin-shell .text-slate-500,
        .dark .fi-admin-shell .text-slate-400 {
            color: #9ca3af !important;
        }

        .dark .fi-admin-shell .border,
        .dark .fi-admin-shell .border-gray-100,
        .dark .fi-admin-shell .border-gray-200,
        .dark .fi-admin-shell .border-gray-300,
        .dark .fi-admin-shell .border-slate-100,
        .dark .fi-admin-shell .border-slate-200,
        .dark .fi-admin-shell .border-blue-100,
        .dark .fi-admin-shell .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
        .dark .fi-admin-shell .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .dark .fi-admin-shell .ring-gray-100,
        .dark .fi-admin-shell .ring-gray-200,
        .dark .fi-admin-shell .ring-slate-200 {
            --tw-ring-color: rgba(255, 255, 255, 0.1) !important;
        }

        .dark .fi-admin-shell table {
            color: #d1d5db;
        }

        .dark .fi-admin-shell thead,
        .dark .fi-admin-shell thead.bg-gray-50,
        .dark .fi-admin-shell thead.bg-gray-100 {
            background-color: #1f2937 !important;
            color: #d1d5db !important;
        }

        .dark .fi-admin-shell tbody tr {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .dark .fi-admin-shell tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }

        .dark .fi-admin-shell input,
        .dark .fi-admin-shell select,
        .dark .fi-admin-shell textarea {
            color: #f9fafb;
            background-color: #111827;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .fi-admin-shell input::placeholder,
        .dark .fi-admin-shell textarea::placeholder {
            color: #6b7280;
        }

        .dark .fi-admin-shell option {
            color: #f9fafb;
            background-color: #111827;
        }
    </style>
    @stack('styles')
</head>
<body class="fi-admin-shell bg-gray-50 text-gray-950 antialiased dark:bg-gray-950 dark:text-white">
@php
    $adminGroups = [
        'Utama' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
                'icon' => 'M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z',
            ],
            [
                'label' => 'Tambah Listing',
                'route' => 'listings.create',
                'active' => request()->routeIs('listings.create'),
                'icon' => 'M12 5v14m7-7H5',
            ],
            [
                'label' => 'Kelola Listing',
                'route' => 'listings.index',
                'active' => request()->routeIs('listings.index') || request()->routeIs('listings.edit'),
                'icon' => 'M4 7h16M4 12h16M4 17h16',
            ],
            [
                'label' => 'Rekomendasi',
                'route' => 'admin.recommendations.index',
                'active' => request()->routeIs('admin.recommendations.*'),
                'icon' => 'M12 3l2.6 5.3 5.9.9-4.3 4.2 1 5.9L12 16.6 6.7 19.4l1-5.9L3.5 9.3l5.9-.9L12 3z',
            ],
        ],
        'Konten Website' => [
            [
                'label' => 'Hero & Carousel',
                'route' => 'carousel.index',
                'active' => request()->routeIs('carousel.*') || request()->routeIs('filament.admin.resources.carousels.*'),
                'icon' => 'M4 6h16v12H4z M8 10h8 M8 14h5',
            ],
            [
                'label' => 'Berita',
                'route' => 'posts.index',
                'active' => request()->routeIs('posts.*'),
                'icon' => 'M5 4h14v16H5z M8 8h8 M8 12h8 M8 16h5',
            ],
            [
                'label' => 'Testimoni',
                'route' => 'admin.testimonials.index',
                'active' => request()->routeIs('admin.testimonials.*'),
                'icon' => 'M4 5h16v10H7l-3 3V5z',
            ],
            [
                'label' => 'Karir',
                'route' => 'admin.careers.index',
                'active' => request()->routeIs('admin.careers.*'),
                'icon' => 'M10 6V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v1 M4 8h16v11H4z M4 12h16',
            ],
        ],
        'Manajemen' => [
            [
                'label' => 'Pengajuan Role',
                'route' => 'admin.role-requests.index',
                'active' => request()->routeIs('admin.role-requests.*'),
                'icon' => 'M9 11l3 3L22 4 M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11',
            ],
            [
                'label' => 'Organisasi',
                'route' => 'admin.organization.index',
                'active' => request()->routeIs('admin.organization.*'),
                'icon' => 'M8 7a4 4 0 1 0 8 0M4 21a8 8 0 0 1 16 0M17 11h4M19 9v4',
            ],
            [
                'label' => 'Mitra',
                'route' => 'admin.partners.index',
                'active' => request()->routeIs('admin.partners.*'),
                'icon' => 'M8 12h8M4 8h16M4 16h16M6 4h12v16H6z',
            ],
            [
                'label' => 'Sales CRM',
                'route' => 'admin.sales.dashboard',
                'active' => request()->routeIs('admin.sales.*'),
                'icon' => 'M4 19V5m5 14V9m5 10V7m5 12v-8',
            ],
            [
                'label' => 'User',
                'route' => 'admin.users',
                'active' => request()->routeIs('admin.users*'),
                'icon' => 'M16 11a4 4 0 1 0-8 0 M4 21a8 8 0 0 1 16 0',
            ],
        ],
    ];

    $activeLabel = collect($adminGroups)->flatten(1)->firstWhere('active', true)['label'] ?? 'Admin';
    $adminUser = auth()->user();
    $adminInitials = collect(explode(' ', trim($adminUser?->name ?? 'Admin')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
@endphp

<div class="fi-layout flex min-h-screen w-full overflow-x-clip">
    <div id="admin-sidebar-backdrop" class="fi-sidebar-close-overlay fixed inset-0 z-30 hidden bg-gray-950/50 transition duration-500 lg:hidden"></div>

    <aside id="admin-sidebar"
        class="fi-sidebar fixed inset-y-0 left-0 z-40 flex h-screen w-[--sidebar-width] -translate-x-full flex-col bg-white shadow-xl ring-1 ring-gray-950/5 transition-transform duration-200 dark:bg-gray-900 dark:ring-white/10 lg:z-0 lg:translate-x-0 lg:bg-transparent lg:shadow-none lg:ring-0 dark:lg:bg-transparent">
        <div class="fi-sidebar-header flex h-16 items-center gap-3 bg-white px-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <img src="{{ asset('images/logo_fixx.png') }}" class="h-10 w-10 rounded-lg object-contain" alt="Arsantara">
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">Arsantara</p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Admin Panel</p>
            </div>
        </div>

        <nav class="fi-sidebar-nav flex flex-grow flex-col gap-y-7 overflow-y-auto overflow-x-hidden px-6 py-8">
            @foreach($adminGroups as $group => $links)
                <div class="fi-sidebar-group flex flex-col gap-y-1">
                    <p class="fi-sidebar-group-label px-2 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">{{ $group }}</p>
                    <ul class="fi-sidebar-group-items flex flex-col gap-y-1">
                        @foreach($links as $link)
                            <li class="fi-sidebar-item {{ $link['active'] ? 'fi-active' : '' }}">
                                <a href="{{ route($link['route']) }}"
                                    class="fi-sidebar-item-button group relative flex items-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75
                                        {{ $link['active'] ? 'bg-gray-100 text-amber-600 dark:bg-white/5 dark:text-amber-400' : 'text-gray-700 hover:bg-gray-100 hover:text-amber-600 dark:text-gray-200 dark:hover:bg-white/5 dark:hover:text-amber-400' }}">
                                    <svg class="fi-sidebar-item-icon h-6 w-6 shrink-0 {{ $link['active'] ? 'text-amber-600' : 'text-gray-400 group-hover:text-amber-600' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="{{ $link['icon'] }}" />
                                    </svg>
                                    <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium">{{ $link['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <a href="{{ route('home') }}"
                class="flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-white/5">
                Lihat Website
            </a>
        </div>
    </aside>

    <div class="fi-main-ctn w-screen flex-1 flex-col lg:pl-[--sidebar-width]">
        <header class="fi-topbar sticky top-0 z-20 overflow-x-clip bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex h-16 items-center gap-x-4 px-4 md:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" id="admin-sidebar-open"
                    class="fi-icon-btn relative flex h-10 w-10 items-center justify-center rounded-lg text-gray-500 outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 lg:hidden"
                    aria-label="Buka sidebar admin">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Admin</p>
                    <h1 class="text-base font-semibold text-gray-950 dark:text-white sm:text-lg">{{ $activeLabel }}</h1>
                </div>
            </div>

            <div class="ml-auto flex items-center gap-2">
                <div class="relative"
                    x-data="adminThemeMenu()"
                    x-init="init()"
                    @keydown.escape.window="open = false">
                    <button type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-950 text-sm font-semibold text-white outline-none ring-2 ring-transparent transition hover:ring-amber-200 focus-visible:ring-amber-300 dark:bg-white dark:text-gray-950"
                        aria-label="Menu akun"
                        @click="open = ! open">
                        {{ $adminInitials ?: 'A' }}
                    </button>

                    <div x-cloak
                        x-show="open"
                        x-transition.origin.top.right
                        @click.outside="open = false"
                        class="absolute right-0 z-50 mt-3 w-80 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-gray-950/10 dark:bg-gray-900 dark:ring-white/10">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-5 py-4 text-gray-700 transition hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 dark:text-gray-500">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.75a9.25 9.25 0 1 0 0 18.5 9.25 9.25 0 0 0 0-18.5Zm0 4.5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 12.25a7.2 7.2 0 0 1-5.45-2.5c1.16-1.52 3.03-2.5 5.45-2.5s4.29.98 5.45 2.5A7.2 7.2 0 0 1 12 19.5Z" />
                                </svg>
                            </span>
                            <span class="truncate text-lg font-medium">{{ $adminUser?->name ?? 'Admin' }}</span>
                        </a>

                        <div class="grid grid-cols-3 gap-2 border-y border-gray-100 p-2 dark:border-white/10">
                            <button type="button"
                                class="group relative flex h-14 items-center justify-center rounded-xl transition"
                                :class="theme === 'light' ? 'bg-gray-50 text-amber-500 dark:bg-white/5 dark:text-amber-400' : 'text-gray-400 hover:bg-gray-50 dark:text-gray-500 dark:hover:bg-white/5'"
                                aria-label="Mode terang"
                                @click="setTheme('light')">
                                <span class="pointer-events-none absolute -top-16 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-lg ring-1 ring-gray-950/5 group-hover:block dark:bg-gray-800 dark:text-gray-200 dark:ring-white/10">
                                    Enable light theme
                                    <span class="absolute left-1/2 top-full h-3 w-3 -translate-x-1/2 -translate-y-1/2 rotate-45 bg-white ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10"></span>
                                </span>
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0-5a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1Zm0 17a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1ZM3 11h1a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2Zm17 0h1a1 1 0 1 1 0 2h-1a1 1 0 1 1 0-2ZM5.64 4.22l.7.7a1 1 0 1 1-1.41 1.42l-.71-.71a1 1 0 0 1 1.42-1.41Zm13.43 13.44.71.71a1 1 0 0 1-1.41 1.41l-.71-.7a1 1 0 1 1 1.41-1.42ZM19.78 5.64l-.71.7a1 1 0 0 1-1.41-1.41l.7-.71a1 1 0 0 1 1.42 1.42ZM6.34 19.07l-.7.71a1 1 0 0 1-1.42-1.41l.71-.71a1 1 0 0 1 1.41 1.41Z" />
                                </svg>
                            </button>
                            <button type="button"
                                class="group relative flex h-14 items-center justify-center rounded-xl transition"
                                :class="theme === 'dark' ? 'bg-gray-50 text-amber-500 dark:bg-white/5 dark:text-amber-400' : 'text-gray-400 hover:bg-gray-50 dark:text-gray-500 dark:hover:bg-white/5'"
                                aria-label="Mode gelap"
                                @click="setTheme('dark')">
                                <span class="pointer-events-none absolute -top-16 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-lg ring-1 ring-gray-950/5 group-hover:block dark:bg-gray-800 dark:text-gray-200 dark:ring-white/10">
                                    Enable dark theme
                                    <span class="absolute left-1/2 top-full h-3 w-3 -translate-x-1/2 -translate-y-1/2 rotate-45 bg-white ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10"></span>
                                </span>
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M20.8 15.18A8.52 8.52 0 0 1 8.82 3.2a.75.75 0 0 0-.75-1.25A10 10 0 1 0 22.05 15.93a.75.75 0 0 0-1.25-.75Z" />
                                </svg>
                            </button>
                            <button type="button"
                                class="group relative flex h-14 items-center justify-center rounded-xl transition"
                                :class="theme === 'system' ? 'bg-gray-50 text-amber-500 dark:bg-white/5 dark:text-amber-400' : 'text-gray-400 hover:bg-gray-50 dark:text-gray-500 dark:hover:bg-white/5'"
                                aria-label="Mode sistem"
                                @click="setTheme('system')">
                                <span class="pointer-events-none absolute -top-16 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-lg ring-1 ring-gray-950/5 group-hover:block dark:bg-gray-800 dark:text-gray-200 dark:ring-white/10">
                                    Enable system theme
                                    <span class="absolute left-1/2 top-full h-3 w-3 -translate-x-1/2 -translate-y-1/2 rotate-45 bg-white ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10"></span>
                                </span>
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M4 5h16v10H4z" />
                                    <path d="M8 21h8M12 15v6" />
                                </svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 px-5 py-4 text-left text-lg font-medium text-gray-700 transition hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5">
                                <svg class="h-7 w-7 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M15 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3" />
                                    <path d="M10 17 15 12 10 7" />
                                    <path d="M15 12H3" />
                                </svg>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </header>

        <main class="fi-main mx-auto h-full w-full px-4 py-6 md:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                @yield('admin_content')
            </div>
        </main>
    </div>
</div>

<x-sweet-alert />
@stack('scripts')

<script>
    function adminThemeMenu() {
        return {
            open: false,
            theme: 'light',
            mediaQuery: window.matchMedia('(prefers-color-scheme: dark)'),
            init() {
                this.theme = localStorage.getItem('theme') || 'light';
                this.applyTheme();

                this.mediaQuery.addEventListener('change', () => {
                    if (this.theme === 'system') {
                        this.applyTheme();
                    }
                });
            },
            setTheme(theme) {
                this.theme = theme;
                localStorage.setItem('theme', theme);
                this.applyTheme();
            },
            applyTheme() {
                const shouldUseDark = this.theme === 'dark' || (this.theme === 'system' && this.mediaQuery.matches);
                document.documentElement.classList.toggle('dark', shouldUseDark);
            },
        };
    }

    const adminSidebar = document.getElementById('admin-sidebar');
    const adminSidebarOpen = document.getElementById('admin-sidebar-open');
    const adminSidebarBackdrop = document.getElementById('admin-sidebar-backdrop');

    function openAdminSidebar() {
        adminSidebar?.classList.remove('-translate-x-full');
        adminSidebarBackdrop?.classList.remove('hidden');
    }

    function closeAdminSidebar() {
        adminSidebar?.classList.add('-translate-x-full');
        adminSidebarBackdrop?.classList.add('hidden');
    }

    adminSidebarOpen?.addEventListener('click', openAdminSidebar);
    adminSidebarBackdrop?.addEventListener('click', closeAdminSidebar);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAdminSidebar();
        }
    });
</script>
</body>
</html>
