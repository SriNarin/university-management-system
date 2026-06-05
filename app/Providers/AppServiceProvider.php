<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Observers\ActivityLogObserver;
use App\Models\User;
use App\Models\Announcement;
use App\Models\SystemNotification;
use App\Models\StudentProfile;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {    }
    public function boot(): void
    {
        

        User::observe(ActivityLogObserver::class);
        Announcement::observe(ActivityLogObserver::class);
        SystemNotification::observe(ActivityLogObserver::class);
        StudentProfile::observe(ActivityLogObserver::class);

        \App\Models\AcademicStructure::observe(ActivityLogObserver::class); 
        \App\Models\AssessmentSubmission::observe(ActivityLogObserver::class);

        
        \App\Models\Attendance::observe(ActivityLogObserver::class); 
        \App\Models\ClassSchedule::observe(ActivityLogObserver::class);
        
        \App\Models\ClassUser::observe(ActivityLogObserver::class); 
        \App\Models\CustomActivityLog::observe(ActivityLogObserver::class);
        
        \App\Models\Department::observe(ActivityLogObserver::class); 
        \App\Models\Faculty::observe(ActivityLogObserver::class);
        
        \App\Models\LessonMaterial::observe(ActivityLogObserver::class); 
        \App\Models\SchoolClass::observe(ActivityLogObserver::class);

        \App\Models\Subject::observe(ActivityLogObserver::class);

        \App\Models\SubjectFinalGrade::observe(ActivityLogObserver::class); 
        \App\Models\SystemNotification::observe(ActivityLogObserver::class);

        \App\Models\Subject::observe(ActivityLogObserver::class);
        
        \App\Models\TaskAssessment::observe(ActivityLogObserver::class);


    }
}
