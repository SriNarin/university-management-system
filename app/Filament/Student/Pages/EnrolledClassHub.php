<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Page;
use App\Models\SchoolClass;
use App\Models\LessonMaterial;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
     * ✅ NEW METHOD: Securely stream the file download straight to the user
     */
    public function downloadMaterial($materialId)
    {
        $material = LessonMaterial::find($materialId);

        if (!$material || empty($material->resource_attachment_path)) {
            $this->dispatch('notify', [
                'status' => 'danger',
                'message' => 'Material record or file assignment not found.'
            ]);
            return;
        }

        $path = $material->resource_attachment_path;

        // Check if the file exists in the public disk setup
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->Storage::download($path);
        }

        // Fallback fallback check for the root default disk app setup
        if (Storage::exists($path)) {
            return Storage::download($path);
        }

        // Throw a user-friendly filament alert notice if it completely fails
        \Filament\Notifications\Notification::make()
            ->title('File Missing')
            ->body('The actual file can no longer be found on the server storage disk paths.')
            ->danger()
            ->send();
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