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
use App\Filament\Pages\Auth\CustomEditProfile;

class FacultyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('faculty')
            ->path('faculty')
            ->login()
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Faculty/Resources'), for: 'App\\Filament\\Faculty\\Resources')

             ->resources([
                \App\Filament\Faculty\Resources\MyProfileResource::class,
                \App\Filament\Faculty\Resources\DepartmentResource::class,
                \App\Filament\Faculty\Resources\SchoolClassResource::class,
                \App\Filament\Faculty\Resources\SubjectResource::class,
                \App\Filament\Faculty\Resources\ClassScheduleResource::class,
                \App\Filament\Faculty\Resources\StudentApprovalResource::class,
                \App\Filament\Faculty\Resources\SubjectFinalGrades\SubjectFinalGradeResource::class,
                \App\Filament\Resources\AnnouncementResource::class,
                 \App\Filament\Resources\SystemNotificationResource::class,
                
                //  \App\Filament\Widgets\StudentTimetableMatrix::class,

                 \App\Filament\Faculty\Resources\ClassesAcademicResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Faculty/Pages'), for: 'App\\Filament\\Faculty\\Pages')
             ->pages([
                Pages\Dashboard::class,
                
            ])
           
            ->discoverWidgets(in: app_path('Filament/Faculty/Widgets'), for: 'App\\Filament\\Faculty\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // \App\Filament\Widgets\UserProfileOverview::class,
                \App\Filament\Widgets\FacultyStatsOverview::class,
                
                
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