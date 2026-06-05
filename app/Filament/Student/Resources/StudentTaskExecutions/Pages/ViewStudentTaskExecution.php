<?php

// 🌟 FIX: Adjusted namespace from StudentTaskExecutions to StudentTaskExecutionResource
namespace App\Filament\Student\Resources\StudentTaskExecutions\Pages;

use App\Filament\Student\Resources\StudentTaskExecutionResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use App\Filament\Student\Widgets\LessonMaterialsWidget;
use App\Filament\Student\Widgets\AttendanceTrackerWidget;

class ViewStudentTaskExecution extends ViewRecord
{
    protected static string $resource = StudentTaskExecutionResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            LessonMaterialsWidget::class,
            AttendanceTrackerWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return [
            'md' => 1,
            'xl' => 2, // Places columns cleanly side-by-side on large screens
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make()
                ->label('Execute Task Submission')
                ->icon('heroicon-s-pencil-square')
                ->color('success')
                // Hide option if student already turned it in or deadline has passed
                ->hidden(fn ($record) => \App\Models\AssessmentSubmission::where('task_assessment_id', $record->id)->where('student_id', Auth::id())->exists() || now()->gt($record->deadline_cut_off)),
        ];
    }

    
}