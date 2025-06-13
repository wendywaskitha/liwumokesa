<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Setting;
use App\Filament\Pages\Settings as SettingsPage;

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
            ->brandName(fn() => Setting::get('general.site_name', 'Visit Liwu Mokesa Mubar'))
            ->brandLogo(fn() => $this->getBrandLogo())
            ->favicon(fn() => $this->getFavicon())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                SettingsPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
            ]);
    }

    /**
     * Get the brand logo URL from settings or fallback
     */
    protected function getBrandLogo(): string
    {
        $logoPath = Setting::get('general.site_logo');

        if (!empty($logoPath)) {
            // If setting exists and contains a value, return the full URL
            return asset('storage/' . $logoPath);
        }

        // Return default logo
        return asset('images/logo.png');
    }

    /**
     * Get the favicon URL from settings or fallback
     */
    protected function getFavicon(): string
    {
        $faviconPath = Setting::get('general.site_favicon');

        if (!empty($faviconPath)) {
            // If setting exists and contains a value, return the full URL
            return asset('storage/' . $faviconPath);
        }

        // Return default favicon
        return asset('images/favicon.ico');
    }
}
