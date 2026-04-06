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
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\OverviewStatsWidget::class,
                \App\Filament\Widgets\MonthlyPageViewsChart::class,
                \App\Filament\Widgets\TopArticlesWidget::class,
                \App\Filament\Widgets\ConsultationStatsWidget::class,
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->renderHook(
                'panels::head.end',
                function (): string {
                    $token = csrf_token();
                    return '<meta name="csrf-token" content="' . $token . '">
                    <script>
                        console.log("CSRF Token set:", "' . $token . '");
                        window.Laravel = { csrfToken: "' . $token . '" };
                    </script>';
                }
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => '
                <script>
                    // Livewire file upload debug
                    document.addEventListener("livewire:initialized", function() {
                        console.log("Livewire initialized");
                    });
                    
                    // Monitor XMLHttpRequest (used by Livewire for file upload)
                    const originalXHR = window.XMLHttpRequest;
                    window.XMLHttpRequest = function() {
                        const xhr = new originalXHR();
                        const originalOpen = xhr.open;
                        const originalSend = xhr.send;
                        
                        xhr.open = function(method, url, ...rest) {
                            this._method = method;
                            this._url = url;
                            console.log("XHR open:", method, url);
                            return originalOpen.apply(this, [method, url, ...rest]);
                        };
                        
                        xhr.send = function(data) {
                            console.log("XHR send:", this._method, this._url, data);
                            
                            this.addEventListener("load", () => {
                                console.log("XHR response:", this._url, this.status, this.statusText);
                                if (this.status >= 400) {
                                    console.log("XHR error response:", this.responseText);
                                }
                            });
                            
                            this.addEventListener("error", () => {
                                console.error("XHR error:", this._url);
                            });
                            
                            this.addEventListener("timeout", () => {
                                console.error("XHR timeout:", this._url);
                            });
                            
                            return originalSend.apply(this, [data]);
                        };
                        
                        return xhr;
                    };
                </script>
                '
            );
    }
}
