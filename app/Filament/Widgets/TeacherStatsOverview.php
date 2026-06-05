<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // 🌟 Ensure this widget only renders inside the Teacher Panel context
        return Auth::check() && Auth::user()->role === 'teacher';
    }

    protected function getStats(): array
    {
        $teacherId = Auth::id();

        // 1. Get IDs of all classes that have schedules assigned to this teacher (Table 9)
        $assignedClassIds = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->distinct('school_class_id')
            ->pluck('school_class_id')
            ->toArray();

        // Quantity of distinct school classes assigned
        $classesCount = count($assignedClassIds);

        // 2. Quantity of total schedule slots/sessions assigned to this teacher
        $schedulesCount = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->count();

        // 3. Quantity of distinct subjects taught by this teacher across their schedules
        $subjectsCount = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->distinct('subject_name_en')
            ->count('subject_name_en');

        // 4. Quantity of officially approved student enrollments in those specific classes (Table 8)
        $enrolledStudentsCount = DB::table('class_user')
            ->whereIn('school_class_id', $assignedClassIds)
            ->where('approval_status', 'approved')
            ->distinct('user_id')
            ->count('user_id');

        // 5. Total pipeline student count (Approved + Pending) inside their active classroom rosters
        $totalClassRosterStudents = DB::table('class_user')
            ->whereIn('school_class_id', $assignedClassIds)
            ->distinct('user_id')
            ->count('user_id');

        return [
            Stat::make('🏫 Assigned Classes', $classesCount)
                ->description('Active distinct groups')
                ->color('primary'),

            Stat::make('📅 Class Schedules', $schedulesCount)
                ->description('Weekly study sessions allocated')
                ->color('success'),

            Stat::make('📚 Unique Subjects', $subjectsCount)
                ->description('Course curriculums managed')
                ->color('warning'),

            Stat::make('🎓 Active Class Enrollees', $enrolledStudentsCount)
                ->description('Approved students in your classes')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('📊 Total Managed Roster', $totalClassRosterStudents)
                ->description('Pending and approved pipeline')
                ->color('gray'),
        ];
    }
}