<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl(fn (): string => route('admin.dashboard'))
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->navigationItems([
                NavigationItem::make('Dashboard')
                    ->group('Utama')
                    ->icon('heroicon-o-home')
                    ->url(fn (): string => route('admin.dashboard'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.dashboard'))
                    ->sort(1),
                NavigationItem::make('Tambah Listing')
                    ->group('Utama')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn (): string => route('listings.create'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('listings.create'))
                    ->sort(2),
                NavigationItem::make('Kelola Listing')
                    ->group('Utama')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn (): string => route('listings.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('listings.index') || request()->routeIs('listings.edit'))
                    ->sort(3),
                NavigationItem::make('Rekomendasi')
                    ->group('Utama')
                    ->icon('heroicon-o-star')
                    ->url(fn (): string => route('admin.recommendations.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.recommendations.*'))
                    ->sort(4),
                NavigationItem::make('Berita')
                    ->group('Konten Website')
                    ->icon('heroicon-o-newspaper')
                    ->url(fn (): string => route('posts.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('posts.*'))
                    ->sort(11),
                NavigationItem::make('Testimoni')
                    ->group('Konten Website')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn (): string => route('admin.testimonials.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.testimonials.*'))
                    ->sort(12),
                NavigationItem::make('Karir')
                    ->group('Konten Website')
                    ->icon('heroicon-o-briefcase')
                    ->url(fn (): string => route('admin.careers.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.careers.*'))
                    ->sort(13),
                NavigationItem::make('Pengajuan Role')
                    ->group('Manajemen')
                    ->icon('heroicon-o-check-badge')
                    ->url(fn (): string => route('admin.role-requests.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.role-requests.*'))
                    ->sort(20),
                NavigationItem::make('Organisasi')
                    ->group('Manajemen')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (): string => route('admin.organization.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.organization.*'))
                    ->sort(21),
                NavigationItem::make('Mitra')
                    ->group('Manajemen')
                    ->icon('heroicon-o-building-office-2')
                    ->url(fn (): string => route('admin.partners.index'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.partners.*'))
                    ->sort(22),
                NavigationItem::make('Sales CRM')
                    ->group('Manajemen')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn (): string => route('admin.sales.dashboard'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.sales.*'))
                    ->sort(23),
                NavigationItem::make('User')
                    ->group('Manajemen')
                    ->icon('heroicon-o-users')
                    ->url(fn (): string => route('admin.users'))
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin.users*'))
                    ->sort(24),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\UserActivityChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
