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

        // 3. Task Assessments assigned to student classes (Table 11)
        $tasksCount = DB::table('task_assessments')
            ->join('class_schedules', 'task_assessments.class_schedule_id', '=', 'class_schedules.id')
            ->whereIn('class_schedules.school_class_id', $enrolledClassIds)
            ->count();

        // 4. 🌟 NEW ELEMENT: Quantity of tasks actually submitted by the student (Table 12)
        $submissionsCount = DB::table('assessment_submissions')
            ->where('student_id', $studentId)
            ->count();

        // 5. Quantity of attendance logs recorded for this student (Table 10)
        $attendanceCount = DB::table('attendances')
            ->where('student_id', $studentId)
            ->count();

        // 6. 📅 NEW SYSTEM: Group sessions count matching days of week (Table 9)
        $weeklySchedules = DB::table('class_schedules')
            ->whereIn('school_class_id', $enrolledClassIds)
            ->select('day_of_week', DB::raw('count(*) as aggregate'))
            ->groupBy('day_of_week')
            ->pluck('aggregate', 'day_of_week')
            ->toArray();

        // Normalize database day strings (handles variations in capitalization)
        $normalizedSchedules = array_change_key_case($weeklySchedules, CASE_LOWER);

        // Map data blocks safely onto a standard Monday-Sunday schedule layout matrix
        $mon = $normalizedSchedules['monday']    ?? ($normalizedSchedules['mon'] ?? 0);
        $tue = $normalizedSchedules['tuesday']   ?? ($normalizedSchedules['tue'] ?? 0);
        $wed = $normalizedSchedules['wednesday'] ?? ($normalizedSchedules['wed'] ?? 0);
        $thu = $normalizedSchedules['thursday']  ?? ($normalizedSchedules['thu'] ?? 0);
        $fri = $normalizedSchedules['friday']    ?? ($normalizedSchedules['fri'] ?? 0);
        $sat = $normalizedSchedules['saturday']  ?? ($normalizedSchedules['sat'] ?? 0);
        $sun = $normalizedSchedules['sunday']    ?? ($normalizedSchedules['sun'] ?? 0);

        return [
            // --- Core Academic Metrics Block ---
            Stat::make('🎓 Enrolled Classes', $classesCount)
                ->description('Active approved class targets')
                ->color('primary'),

            Stat::make('📚 Active Subjects', $subjectsCount)
                ->description('Course curriculums tracking')
                ->color('success'),

            Stat::make('📝 Task Assessments', $tasksCount)
                ->description('Assigned tasks & quizzes')
                ->color('warning'),

            Stat::make('📤 Submitted Tasks', $submissionsCount)
                ->description('Your uploaded assignments')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->color('success'),

            Stat::make('✅ Tracked Attendances', $attendanceCount)
                ->description('Your total live check-ins')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            // --- Day-by-Day Timetable Session Trackers ---
            Stat::make('📅 Monday Classes', $mon . ' Sessions')
                ->description($mon > 0 ? '📅 Class study active' : 'No classes scheduled')
                ->color($mon > 0 ? 'success' : 'gray'),

            Stat::make('📅 Tuesday Classes', $tue . ' Sessions')
                ->description($tue > 0 ? '📅 Class study active' : 'No classes scheduled')
                ->color($tue > 0 ? 'success' : 'gray'),

            Stat::make('📅 Wednesday Classes', $wed . ' Sessions')
                ->description($wed > 0 ? '📅 Class study active' : 'No classes scheduled')
                ->color($wed > 0 ? 'success' : 'gray'),

            Stat::make('📅 Thursday Classes', $thu . ' Sessions')
                ->description($thu > 0 ? '📅 Class study active' : 'No classes scheduled')
                ->color($thu > 0 ? 'success' : 'gray'),

            Stat::make('📅 Friday Classes', $fri . ' Sessions')
                ->description($fri > 0 ? '📅 Class study active' : 'No classes scheduled')
                ->color($fri > 0 ? 'success' : 'gray'),

            Stat::make('📅 Saturday Classes', $sat . ' Sessions')
                ->description($sat > 0 ? 'Weekend study active' : 'No classes scheduled')
                ->color($sat > 0 ? 'warning' : 'gray'),

            Stat::make('📅 Sunday Classes', $sun . ' Sessions')
                ->description($sun > 0 ? 'Weekend study active' : 'No classes scheduled')
                ->color($sun > 0 ? 'danger' : 'gray'),
        ];
    }
}