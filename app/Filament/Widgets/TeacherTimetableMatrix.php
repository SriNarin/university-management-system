<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherTimetableMatrix extends Widget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.teacher-timetable-matrix';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'teacher';
    }

    /**
     * Build a matrix grid of time slots and days for the authenticated teacher.
     */
    public function getTimetableGrid(): array
    {
        $teacherId = Auth::id();

        // Fetch all raw timetable schedules assigned to this teacher
        $schedules = DB::table('class_schedules')
            ->join('school_classes', 'class_schedules.school_class_id', '=', 'school_classes.id')
            ->where('class_schedules.teacher_id', $teacherId)
            ->select([
                'class_schedules.day_of_week',
                'class_schedules.start_time',
                'class_schedules.end_time',
                'class_schedules.subject_name_en',
                'school_classes.room_number', // 🌟 FIXED: Changed from room_number to match your exact table column schema
                'school_classes.shift',
                'school_classes.class_code',

            ])
            ->get();

        $gridData = [];

        foreach ($schedules as $schedule) {
            // Format time string block, e.g., "08:00 AM - 10:00 AM"
            $startTime = date('h:i A', strtotime($schedule->start_time));
            $endTime = date('h:i A', strtotime($schedule->end_time));
            $timeBlock = "{$startTime} - {$endTime}";

            // Normalize day string capitalization to match template matrix keys
            $day = ucfirst(strtolower($schedule->day_of_week));

            // Assign subject details and classroom targets inside our tracking structure
            $gridData[$timeBlock][$day] = [
                'subject' => $schedule->subject_name_en,
                'class_code' => $schedule->class_code,
                'room' => $schedule->room ?? 'TBD', // 🌟 FIXED: Reference matching database variable key
            ];
        }

        // Sort time blocks chronologically
        ksort($gridData);

        return $gridData;
    }
}