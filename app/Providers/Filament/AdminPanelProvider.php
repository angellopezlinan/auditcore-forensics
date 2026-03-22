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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;
use App\Filament\Pages\EditProfile;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->profile(\App\Filament\Pages\EditProfile::class)
            ->brandName('AuditCore Forensics')
            ->font('Inter') // Fuente corporativa
            ->colors([
                'primary' => Color::hex('#0F172A'), // Navy/Dark Slate para AuditCore
                'gray' => Color::Slate,
            ])
            ->tenant(\App\Models\Team::class)
            ->tenantRegistration(\App\Filament\Pages\Tenancy\RegisterTeam::class)
            ->tenantMiddleware([
                \App\Http\Middleware\SyncSpatieTenant::class,
            ], isPersistent: true)
            ->plugin(FilamentShieldPlugin::make())
            ->plugin(
                TwoFactorAuthenticationPlugin::make()
                    ->addTwoFactorMenuItem()
                    ->enforceTwoFactorSetup()
            )
            ->plugin(\ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin::make())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Setup2FA::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                PanelsRenderHook::HEAD_END,
                fn (): string => str_contains(request()->url(), 'two-factor')
                    ? Blade::render('<style>.fi-topbar { display: none !important; } .fi-sidebar { display: none !important; } .fi-user-menu { display: none !important; } .fi-main { margin-inline-start: 0 !important; }</style>')
                    : ''
            );
    }
}
