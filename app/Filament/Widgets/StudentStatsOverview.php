<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentStatsOverview extends BaseWidget
{
   protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    protected function getStats(): array
    {
        $studentId = Auth::id();

        // 1. Get IDs of all approved classes the student is enrolled in (Table 8)
        $enrolledClassIds = DB::table('class_user')
            ->where('user_id', $studentId)
            ->where('approval_status', 'approved')
            ->pluck('school_class_id')
            ->toArray();

        $classesCount = count($enrolledClassIds);

        // 2. Quantity of distinct subjects inside those enrolled classes (Table 9)
        $subjectsCount = DB::table('class_schedules')
            ->whereIn('school_class_id', $enrolledClassIds)
            ->distinct('subject_name_en')
            ->count('subject_name_en');

        // 3. 🌟 FIXED: Join task_assessments with class_schedules to match your database schema
        $tasksCount = DB::table('task_assessments')
            ->join('class_schedules', 'task_assessments.class_schedule_id', '=', 'class_schedules.id')
            ->whereIn('class_schedules.school_class_id', $enrolledClassIds)
            ->count();

        // 4. Quantity of attendance logs recorded for this student (Table 10)
        $attendanceCount = DB::table('attendances')
            ->where('student_id', $studentId) // 🌟 Note: Using 'student_id' from your migration schema
            ->count();

        return [
            Stat::make('🎓 Enrolled Classes', $classesCount)
                ->description('Active approved class targets')
                ->color('primary'),

            Stat::make('📚 Active Subjects', $subjectsCount)
                ->description('Course curriculums tracking')
                ->color('success'),

            Stat::make('📝 Task Assessments', $tasksCount)
                ->description('Assigned class tasks & quizzes')
                ->color('warning'),

            Stat::make('✅ Tracked Attendances', $attendanceCount)
                ->description('Your total live attendance check-ins')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
        ];
    }
}