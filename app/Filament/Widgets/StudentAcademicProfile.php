<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolClass;

class StudentAcademicProfile extends Widget
{
    protected string $view = 'filament.widgets.student-academic-profile';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
       
        return Auth::user()?->role === 'student';
    }

    /**
     * Get details of the active academic profile for the student
     */
    public function getStudentProfile(): ?array
    {
        $student = Auth::user();
        if (!$student) return null;

        return SchoolClass::whereHas('students', function ($query) use ($student) {
            $query->where('users.id', $student->id);
        })->with(['academicStructure.department.faculty'])->first()?->toArray();
    }

    /**
     * Formats database records into structured column rows matching Image 2
     */
    public function getTimetableGrid(): array
    {
        $student = Auth::user();
        if (!$student) return [];

        // Define our fixed target time blocks matching standard university shifts
        $timeBlocks = [
            '08:00 AM - 10:00 AM',
            '09:14 AM - 11:14 AM',
            '01:00 PM - 03:00 PM',
            '03:15 PM - 05:15 PM'
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Initialize empty rows structure
        $grid = [];
        foreach ($timeBlocks as $time) {
            $grid[$time] = array_fill_keys($days, null);
        }

        // Pull active schedules from the database
        $schedules = DB::table('class_schedules')
            ->join('school_classes', 'class_schedules.school_class_id', '=', 'school_classes.id')
            ->join('class_user', 'school_classes.id', '=', 'class_user.school_class_id')
            ->leftJoin('users', 'class_schedules.teacher_id', '=', 'users.id')
            ->where('class_user.user_id', $student->id)
            ->select(
                'class_schedules.day_of_week',
                'class_schedules.start_time',
                'class_schedules.end_time',
                'class_schedules.subject_name_en as subject',
                'users.name as teacher',
                'school_classes.room_number as room'
            )->get();

        foreach ($schedules as $session) {
            $dayKey = ucfirst(strtolower($session->day_of_week));
            
            $start = date('h:i A', strtotime($session->start_time));
            $end = date('h:i A', strtotime($session->end_time));
            $formattedTimeBlock = "{$start} - {$end}";

            // Find matching or closest existing time block row
            $matchedBlock = null;
            foreach ($timeBlocks as $block) {
                if ($block === $formattedTimeBlock || json_encode(strtotime($start)) === json_encode(strtotime(explode(' - ', $block)[0]))) {
                    $matchedBlock = $block;
                    break;
                }
            }

            // Fallback generation for off-schedule blocks if necessary
            if (!$matchedBlock) {
                $matchedBlock = $formattedTimeBlock;
                if (!isset($grid[$matchedBlock])) {
                    $grid[$matchedBlock] = array_fill_keys($days, null);
                }
            }

            if (array_key_exists($dayKey, $grid[$matchedBlock])) {
                $grid[$matchedBlock][$dayKey] = [
                    'subject' => $session->subject,
                    'teacher' => $session->teacher ?? 'Staff',
                    'room'    => $session->room ?? 'TBD'
                ];
            }
        }

        return $grid;
    }
}