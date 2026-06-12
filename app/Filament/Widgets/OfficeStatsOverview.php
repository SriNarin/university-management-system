<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // 🌟 Ensure this widget only renders inside the Study Office Panel context
        return Auth::check() && Auth::user()->role === 'study_office';
    }

    protected function getStats(): array
    {
        // 1. Total quantity of departments across the entire institution
        $departmentsCount = DB::table('departments')->count();

        // 2. Total quantity of active curriculum subjects (Table 6)
        $subjectsCount = DB::table('subjects')->count();

        // 3. Total quantity of active school classes across all configurations (Table 5)
        $classesCount = DB::table('school_classes')->count();

        // 4. Total quantity of unique teachers assigned to any class schedule (Table 9)
        $teachersCount = DB::table('class_schedules')
            ->distinct('teacher_id')
            ->count('teacher_id');

        // 5. Total quantity of officially approved student class enrollments (Table 8 - class_user pivot)
        $enrolledStudentsCount = DB::table('class_user')
            ->where('approval_status', 'approved')
            ->distinct('user_id')
            ->count('user_id');

        // 6. Total tracking workflow pipeline applicants (Pending + Approved + Rejected)
        $totalPipelineStudents = DB::table('class_user')
            ->distinct('user_id')
            ->count('user_id');

        // 7. Total quantity of faculties (Table 2)
        $facultiesCount = DB::table('faculties')->count();

        // 8. Total users registered with the 'faculty_manager' role (Table 1)
        $facultyManagersCount = DB::table('users')
            ->where('role', 'faculty_manager')
            ->count();

        // 🌟 9. New Element: Total live, visible institutional announcements (Table 15)
        $announcementsCount = DB::table('announcements')
            ->where('is_visible', true)
            ->count();

        return [
            
            Stat::make('🏛️ Total Faculties', $facultiesCount)
                ->description('High-level institutional faculties')
                ->color('primary'),

            Stat::make('👩‍💼 Faculty Manager Roles', $facultyManagersCount)
                ->description('Total assigned academic managers')
                ->color('info'),

            Stat::make('🏢 Total Departments', $departmentsCount)
                ->description('Global active institutional programs')
                ->color('primary'),

            Stat::make('🏫 School Classes', $classesCount)
                ->description('Total active academic groups')
                ->color('success'),

            Stat::make('📚 Curriculum Subjects', $subjectsCount)
                ->description('Global active course catalogs')
                ->color('warning'),

            Stat::make('👨‍🏫 Total Teachers Staff', $teachersCount)
                ->description('Unique instructional educators')
                ->color('info'),

            // --- Row 2 ---
            Stat::make('🎓 Active Enrolled Students', $enrolledStudentsCount)
                ->description('Approved structural student records')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('📊 Enrollment Submissions', $totalPipelineStudents)
                ->description('Total enrollment requests handled')
                ->color('gray'),

            

            
            Stat::make('📢 Broadcasted Announcements', $announcementsCount)
                ->description('Total active institutional notices')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('danger'),
        ];
    }
}