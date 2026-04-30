@extends('layouts.app')

@section('content')
@php
    $adminLinks = [
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
        [
            'label' => 'Carousel',
            'route' => 'carousel.index',
            'active' => request()->routeIs('carousel.*'),
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
            'label' => 'User',
            'route' => 'admin.users',
            'active' => request()->routeIs('admin.users*'),
            'icon' => 'M16 11a4 4 0 1 0-8 0 M4 21a8 8 0 0 1 16 0',
        ],
    ];
@endphp

<div class="min-h-screen bg-gray-50 pt-20">
    <div class="mx-auto flex w-full max-w-screen-2xl gap-0 lg:gap-6 px-0 lg:px-6">
        <aside class="hidden lg:flex lg:w-72 lg:shrink-0">
            <div class="sticky top-24 h-[calc(100vh-7rem)] w-full rounded-lg border bg-white shadow-sm">
                <div class="border-b px-5 py-4">
                    <p class="text-xs font-semibold uppercase text-gray-500">Panel Admin</p>
                    <h2 class="text-lg font-bold text-gray-900">Arsantara</h2>
                </div>

                <nav class="p-3 space-y-1">
                    @foreach($adminLinks as $link)
                        <a href="{{ route($link['route']) }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                                {{ $link['active'] ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $link['icon'] }}" />
                            </svg>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="absolute bottom-0 left-0 right-0 border-t p-4">
                    <a href="{{ route('home') }}" class="block rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Lihat Website
                    </a>
                </div>
            </div>
        </aside>

        <div class="w-full min-w-0">
            <div class="lg:hidden border-b bg-white px-4 py-3">
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($adminLinks as $link)
                        <a href="{{ route($link['route']) }}"
                            class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                                {{ $link['active'] ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <main class="min-h-screen px-4 py-6 lg:px-0">
                @yield('admin_content')
            </main>
        </div>
    </div>
</div>
@endsection
