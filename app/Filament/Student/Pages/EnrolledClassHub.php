<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Page;
use App\Models\SchoolClass;
use App\Models\LessonMaterial;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class EnrolledClassHub extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static ?string $navigationLabel = 'My Enrolled Classes';
    protected static ?string $title = 'Class Learning Hub';
    protected static ?int $navigationSort = 1; 

    protected string $view = 'filament.student.pages.enrolled-class-hub';

    // State container for select filtering
    public ?int $selectedClassId = null;

    public function mount(): void
    {
        $firstClass = $this->getEnrolledClassesProperty()->first();
        if ($firstClass) {
            $this->selectedClassId = $firstClass->id;
        }
    }

    /**
     * 🔒 Fetcher: Active Approved Student Classes
     */

   public function getEnrolledClassesProperty()
    {
        return SchoolClass::with([
            'academicStructure',
            'classSchedules.teacher' // 🌟 Load schedule items along with the teacher user records
        ])
        ->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('approval_status', 'approved');
        })->get();
    }
    /**
     * 📂 Fetcher: Filter materials based on selection
     */
    public function getLessonMaterialsProperty()
    {
        if (!$this->selectedClassId) return collect();

        return LessonMaterial::whereHas('classSchedule', function ($query) {
            $query->where('school_class_id', $this->selectedClassId);
        })->latest()->get();
    }

    /**
     * 📊 Fetcher: Filter attendance history based on selection
     */
    public function getAttendanceHistoryProperty()
    {
        if (!$this->selectedClassId) return collect();

        return Attendance::where('student_id', Auth::id())
            ->whereHas('classSchedule', function ($query) {
                $query->where('school_class_id', $this->selectedClassId);
            })
            ->latest()
            ->get();
    }
}