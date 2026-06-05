<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimetablePrintController extends Controller
{
    public function print($id)
    {
        $schoolClass = SchoolClass::with('academicStructure.department')->findOrFail($id);
        
        // Pull the live assigned entries for this specific class schedule
        $schedules = ClassSchedule::where('school_class_id', $id)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        return view('print.timetable-template', compact('schoolClass', 'schedules'));
    }

    /**
     * 🚀 AUTOMATED GENERATION ENGINE
     * Compiles raw class assignments into a ready-to-print student schedule file template.
     */
    public function toggleStatus($id)
    {
        $class = SchoolClass::findOrFail($id);
        
        // If turning ON, check if teachers and modules have been assigned first
        if (!$class->is_timetable_published) {
            $hasSchedules = ClassSchedule::where('school_class_id', $id)->exists();

            if (!$hasSchedules) {
                // If no assignments exist yet, return back with a browser alert flag
                return back()->with('error', 'Cannot generate: No subjects or teachers assigned to this class code yet.');
            }

            // Perform final validation compilation and publish the timeline slot
            $class->update([
                'is_timetable_published' => true,
                'timetable_published_at' => now(),
            ]);
        } else {
            // Retract from student dashboards and unpublish file state
            $class->update([
                'is_timetable_published' => false,
                'timetable_published_at' => null,
            ]);
        }

        return back();
    }

    /**
     * 🎓 STUDENT TIMETABLE DOWNLOAD HUB
     * Safely restricts access so students can only pull schedules for their own class code.
     */
    public function printStudentTimetable($classId)
    {
        // 1. Ensure a student is signed in
        if (!Auth::check()) {
            abort(403, 'Unauthorized access.');
        }

        // 2. Fetch class data and ensure it's published by the faculty first
        $schoolClass = SchoolClass::with(['academicStructure.department'])
            ->where('is_timetable_published', true)
            ->findOrFail($classId);
        
        // 3. Compile class schedules matrix data
        $schedules = ClassSchedule::with('teacher')
            ->where('school_class_id', $classId)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        return view('print.timetable-template', compact('schoolClass', 'schedules'));
    }
}