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

class TeacherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('teacher')
            ->path('teacher')
            ->renderHook(
                'panels::styles.after',
                fn (): string => Blade::render('<link rel="stylesheet" href="{{ asset(\'css/filament-custom.css\') }}?v=' . time() . '">'),
            )
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
            
            // 🛠️ Clear out automatically discovered base widgets to avoid rendering overlaps
            ->discoverWidgets(in: app_path('Filament/Teacher/Widgets'), for: 'App\\Filament\\Teacher\\Widgets')
            ->widgets([
                // 1. Overview Summary Cards at the top row
                \App\Filament\Widgets\TeacherStatsOverview::class,
                
                // 2. Graphical data charts down below
                \App\Filament\Widgets\TeacherAttendanceBarChart::class,
                \App\Filament\Widgets\TeacherAttendancePieChart::class,
                
                // 3. User account metadata widget banner at the bottom or corner
                Widgets\AccountWidget::class,
            ])
            
            ->databaseNotifications()
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
                ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}