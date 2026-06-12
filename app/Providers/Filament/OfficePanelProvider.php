<?php

namespace App\Providers\Filament;
use App\Models\User;
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
use App\Filament\Pages\Auth\CustomEditProfile;
use Filament\Support\Facades\FilamentView; 
use Illuminate\Support\Facades\Blade;
class OfficePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('office')
            ->path('office')
           ->renderHook(
                'panels::styles.after',
                fn (): string => Blade::render('<link rel="stylesheet" href="{{ asset(\'css/filament-custom.css\') }}?v=' . time() . '">'),
            )
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Office/Resources'), for: 'App\\Filament\\Office\\Resources')

             ->resources([
                \App\Filament\Office\Resources\MyProfileResource::class,
                \App\Filament\office\Resources\StudentProfiles\StudentProfilesResource::class,
                \App\Filament\Office\Resources\StudentEnrolls\StudentEnrollResource::class,
                \App\Filament\Office\Resources\SubjectFinalGrades\SubjectFinalGradeResource::class,
                \App\Filament\Resources\AnnouncementResource::class,
                 \App\Filament\Resources\SystemNotificationResource::class,
            ])
           
            
            ->discoverPages(in: app_path('Filament/Office/Pages'), for: 'App\\Filament\\Office\\Pages')
             ->pages([
                Pages\Dashboard::class,
            
            ])
            
            ->discoverWidgets(in: app_path('Filament/Office/Widgets'), for: 'App\\Filament\\Office\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,

                // \App\Filament\Widgets\UserProfileOverview::class,
                \App\Filament\Widgets\OfficeStatsOverview::class,
                \App\Filament\Widgets\OfficeClassesPerDepartmentBarChart::class,
                \App\Filament\Widgets\OfficeStudentDistributionPieChart::class,

                
            ])
            ->databaseNotifications() // 🌟 Enable this line in EVERY provider file!
            ->databaseNotificationsPolling('30s')
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