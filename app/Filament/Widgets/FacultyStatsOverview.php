<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Faculty;

class FacultyStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // 🌟 Ensure this widget only renders inside the Faculty Manager Panel context
        return Auth::check() && Auth::user()->role === 'faculty_manager';
    }

    protected function getStats(): array
    {
        $managerId = Auth::id();

        // 1. Find the specific Faculty managed by this user
        $faculty = DB::table('faculties')->where('manager_id', $managerId)->first();

        if (!$faculty) {
            return [
                Stat::make('Departments Program', 0)->description('No active faculty profile found'),
                Stat::make('School Classes Registered', 0),
                Stat::make('Curriculum Subjects', 0),
                Stat::make('Faculty Teachers Staff', 0),
                Stat::make('Enrolled Students', 0),
            ];
        }

        // 2. Quantity of Departments assigned to this faculty
        $departmentIds = DB::table('departments')
            ->where('faculty_id', $faculty->id)
            ->pluck('id')
            ->toArray();
            
        $departmentsCount = count($departmentIds);

        // 3. Quantity of Subjects assigned to these departments (Table 6)
        $subjectsCount = DB::table('subjects')
            ->whereIn('department_id', $departmentIds)
            ->count();

        // 4. Quantity of School Classes linked via Academic Structures (Table 4 & 5)
        $academicStructureIds = DB::table('academic_structures')
            ->whereIn('department_id', $departmentIds)
            ->pluck('id')
            ->toArray();

        $classesCount = DB::table('school_classes')
            ->whereIn('academic_structure_id', $academicStructureIds)
            ->count();

        $classIds = DB::table('school_classes')
            ->whereIn('academic_structure_id', $academicStructureIds)
            ->pluck('id')
            ->toArray();

        // 5. Quantity of Unique Teachers hosting schedules inside this faculty's classes (Table 9)
        $teachersCount = DB::table('class_schedules')
            ->whereIn('school_class_id', $classIds)
            ->distinct('teacher_id')
            ->count('teacher_id');

        // 6. Quantity of Enrolled Students with Approved Status (Table 8 - class_user pivot)
        $enrolledStudentsCount = DB::table('class_user')
            ->whereIn('school_class_id', $classIds)
            ->where('approval_status', 'approved')
            ->distinct('user_id')
            ->count('user_id');

        // 7. Quantity of Total Registered Applicants inside the tracking workflow pipeline (All Pending + Approved)
        $totalPipelineStudents = DB::table('class_user')
            ->whereIn('school_class_id', $classIds)
            ->distinct('user_id')
            ->count('user_id');

        return [
            Stat::make('🏬 Total Departments', $departmentsCount)
                ->description('Active degree programs')
                ->color('primary'),

            Stat::make('🏫 School Classes', $classesCount)
                ->description('Allocated active groups')
                ->color('success'),

            Stat::make('📚 Curriculum Subjects', $subjectsCount)
                ->description('Total course blueprints')
                ->color('warning'),

            Stat::make('👨‍🏫 Faculty Teachers', $teachersCount)
                ->description('Active schedule assignments')
                ->color('info'),

            Stat::make('🎓 Enrolled Students', $enrolledStudentsCount)
                ->description('Approved structural entries')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('📊 Total Allocation Applicants', $totalPipelineStudents)
                ->description('Combined pipeline status tracking')
                ->color('gray'),
        ];
    }
}