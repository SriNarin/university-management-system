<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherStatsOverview extends BaseWidget
{
     protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // 🌟 Ensure this widget only renders inside the Teacher Panel context
        return Auth::check() && Auth::user()->role === 'teacher';
    }

    protected function getStats(): array
    {
        $teacherId = Auth::id();

        // 1. Get IDs of all schedules assigned to this teacher
        $assignedScheduleIds = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();

        // Get IDs of all unique classes assigned to this teacher
        $assignedClassIds = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->distinct('school_class_id')
            ->pluck('school_class_id')
            ->toArray();

        // Counts based on schedules table
        $classesCount = count($assignedClassIds);
        $schedulesCount = count($assignedScheduleIds);

        // 3. Quantity of distinct subjects taught by this teacher across their schedules
        $subjectsCount = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->distinct('subject_code') // subject_code is safer and cleaner than the string name
            ->count('subject_code');

        // Initialize dependent counters to zero
        $enrolledStudentsCount = 0;
        
        $assessmentsCount = 0;
        $submissionsCount = 0;

        // 🛡️ SAFETY GUARD: Only query deeper structural components if schedules exist
        if (!empty($assignedScheduleIds) && !empty($assignedClassIds)) {
            
            // 4. Quantity of officially approved student enrollments
            $enrolledStudentsCount = DB::table('class_user')
                ->whereIn('school_class_id', $assignedClassIds)
                ->where('approval_status', 'approved')
                ->distinct('user_id')
                ->count('user_id');
            

            // 6. FIXED: Query 'task_assessments' table by linking via your assigned schedule IDs
            $assessmentsCount = DB::table('task_assessments')
                ->whereIn('class_schedule_id', $assignedScheduleIds)
                ->count();

            // 7. FIXED: Query 'assessment_submissions' by linking through the parent task assessments
            $submissionsCount = DB::table('assessment_submissions')
                ->join('task_assessments', 'assessment_submissions.task_assessment_id', '=', 'task_assessments.id')
                ->whereIn('task_assessments.class_schedule_id', $assignedScheduleIds)
                ->count();
        }

        return [
            Stat::make('🏫 Assigned Classes', $classesCount)
                ->description('Active distinct groups')
                ->color('primary'),

            Stat::make('📅 Class Schedules', $schedulesCount)
                ->description('Weekly study sessions allocated')
                ->color('success'),

            Stat::make('📚 Subjects For teaching', $subjectsCount)
                ->description('Course curriculums managed')
                ->color('warning'),

            Stat::make('📝 Assessment Tasks', $assessmentsCount)
                ->description('Published course assessments')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            Stat::make('📥 Student Submissions', $submissionsCount)
                ->description('Total turned-in files/tasks')
                ->descriptionIcon('heroicon-m-document-arrow-up')
                ->color('danger'),

            Stat::make('🎓 Active Class Enrollees', $enrolledStudentsCount)
                ->description('Approved students in your classes')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

        
        ];
    }
}