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

class TeacherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('teacher')
            ->path('teacher')
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Teacher/Resources'), for: 'App\\Filament\\Teacher\\Resources')
            ->resources([
                \App\Filament\Student\Resources\MyProfileResource::class,
                \App\Filament\Teacher\Resources\Attendances\AttendanceResource::class,
                \App\Filament\Teacher\Resources\TaskAssessments\TaskAssessmentResource::class,
                \App\Filament\Teacher\Resources\LessonMaterialResource::class,
                 \App\Filament\Teacher\Resources\SubjectFinalGrades\SubjectFinalGradeResource::class,
                 \App\Filament\Resources\AnnouncementResource::class,
                  \App\Filament\Resources\SystemNotificationResource::class,
                
             ])
            ->discoverPages(in: app_path('Filament/Teacher/Pages'), for: 'App\\Filament\\Teacher\\Pages')
             ->pages([
                Pages\Dashboard::class,
               
            ])
            
            ->discoverWidgets(in: app_path('Filament/Teacher/Widgets'), for: 'App\\Filament\\Teacher\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // \App\Filament\Widgets\UserProfileOverview::class,
                \App\Filament\Widgets\TeacherStatsOverview::class,
                \App\Filament\Widgets\TeacherTimetableMatrix::class,
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