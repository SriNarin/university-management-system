<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentTimetableMatrix extends Widget
{
    public ?string $activeClassId = null;

    // 🚀 METHOD 1: TOGGLE PUBLISH (For Faculty Managers)
    public function togglePublishStatus(): void
    {
        if (!Auth::user() || Auth::user()->role !== 'faculty_manager') {
            return;
        }

        $class = DB::table('school_classes')->where('id', $this->activeClassId)->first();
        
        if ($class) {
            $newStatus = !$class->is_timetable_published;
            
            DB::table('school_classes')
                ->where('id', $this->activeClassId)
                ->update([
                    'is_timetable_published' => $newStatus,
                    'timetable_published_at' => $newStatus ? now() : null,
                ]);
        }
    }

    // 🖨️ METHOD 2: TRIGGER BROWSER PRINT MODAL
    public function triggerPrintLayout(): void
    {
        // This dispatches a safe browser event to handle pristine printing
        $this->dispatch('open-timetable-print-window', classId: $this->activeClassId);
    }

    // Keep your existing getEnrolledClasses(), getStudentProfile(), and getTimetableGrid() methods below...
}