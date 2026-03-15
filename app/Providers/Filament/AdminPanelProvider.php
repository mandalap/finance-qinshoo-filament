<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Assets\Css;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\CheckResourceAccess;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Sistem Keuangan Yayasan')
            ->favicon(asset('favicon.png'))
            ->colors([
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'info' => Color::Sky,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\DashboardPage::class,
            ])
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()
                    ->label('Profile')
                    ->url(fn (): string => \App\Filament\Pages\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            // Widget discovery dinonaktifkan, widget hanya ditampilkan melalui DashboardPage
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            // Render script untuk menangani 403 error
            ->renderHook(
                'panels::head.start',
                fn (): string => <<<HTML
                <script>
                // Override Livewire's error handling untuk authorization errors
                document.addEventListener('DOMContentLoaded', function() {
                    // Cek apakah ada error message di session
                    if (window.sessionStorage.getItem('filament_notification')) {
                        const notification = JSON.parse(window.sessionStorage.getItem('filament_notification'));
                        window.sessionStorage.removeItem('filament_notification');
                        if (window.Livewire) {
                            window.Livewire.dispatch('notify', notification);
                        }
                    }
                });

                // Intercept Livewire updates untuk 403 response
                document.addEventListener('livewire:update', (event) => {
                    const detail = event.detail;
                    if (detail && detail.response && detail.response.status === 403) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        // Redirect ke dashboard dengan notification
                        window.location.href = '/admin?notification=danger';
                    }
                });

                // Override console.error untuk menangkap Livewire errors
                const originalConsoleError = console.error;
                console.error = function(...args) {
                    originalConsoleError.apply(console, args);
                    if (args[0] && args[0].toString().includes('403')) {
                        window.sessionStorage.setItem('filament_notification', JSON.stringify({
                            message: 'Anda tidak memiliki akses ke halaman ini.',
                            type: 'danger'
                        }));
                        window.location.href = '/admin';
                    }
                };
                </script>
                HTML
            )
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
                CheckResourceAccess::class,
            ]);
    }
}
