<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
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
            ->login()
            ->brandName('Unilak Hostel')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                'panels::head.end',
                fn(): string => '<style>
                    .fi-sidebar {
                        background-color: #f3f4f6 !important; /* Darker gray for sidebar */
                    }
                    .dark .fi-sidebar {
                        background-color: #111827 !important; /* Darker for dark mode */
                    }
                    /* Push logout to bottom if possible, or style the footer */
                    .fi-sidebar-footer {
                        border-top: 1px solid #e5e7eb;
                        padding-top: 1rem;
                        margin-top: auto;
                    }
                    /* Login Card Styling */
                    .fi-login-card {
                        border-radius: 1.5rem !important; /* rounded-2xl */
                        overflow: hidden !important;
                    }
                    /* Hide default brand header on login page */
                    .fi-simple-header {
                        display: none !important;
                    }
                </style>'
            )
            ->renderHook(
                'panels::sidebar.footer',
                fn() => view('filament.components.logout-button')
            )
            ->renderHook(
                'panels::auth.login.form.before',
                fn() => view('filament.auth.login-header')
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
