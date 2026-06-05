<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\Pages;

use App\Filament\Teacher\Resources\TaskAssessments\TaskAssessmentResource;
use Filament\Actions\CreateAction;
use App\Models\TaskAssessment;
use App\Models\ClassUser;
use App\Models\AssessmentSubmission;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


class ListTaskAssessments extends ListRecords
{
    protected static string $resource = TaskAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            // 🧮 Safe Global Re-Exam Scanner Action
            Actions\Action::make('runGlobalReExamScanner')
                ->label('Scan and Open Re-Exams')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('This scan goes through your classes. Any approved student whose total combined score drops below 50% will automatically receive an automated Re-Exam assessment line.')
                ->action(function () {
                    // Fetch all assessments belonging to the authenticated teacher's schedules
                    $assessments = TaskAssessment::whereHas('classSchedule', function ($query) {
                        $query->where('teacher_id', Auth::id());
                    })->get();

                    $triggeredLinesCount = 0;

                    foreach ($assessments as $assessment) {
                        $schedule = $assessment->classSchedule;
                        if (!$schedule) continue;

                        $students = ClassUser::where('school_class_id', $schedule->school_class_id)
                            ->where('approval_status', 'approved')
                            ->get();

                        foreach ($students as $student) {
                            $cumulativeEarned = AssessmentSubmission::where('student_id', $student->user_id)
                                ->whereHas('taskAssessment', fn($q) => $q->where('class_schedule_id', $schedule->id))
                                ->sum('secured_score');

                            $cumulativeAvailable = TaskAssessment::where('class_schedule_id', $schedule->id)->sum('max_score_threshold');

                            $totalPercentage = $cumulativeAvailable > 0 ? ($cumulativeEarned / $cumulativeAvailable) * 100 : 0;

                            if ($totalPercentage < 50) {
                                $exist = TaskAssessment::where('class_schedule_id', $schedule->id)
                                    ->where('task_type', 're_exam')
                                    ->where('title', 'like', "%Student ID #{$student->user_id}%")
                                    ->exists();

                                if (!$exist) {
                                    TaskAssessment::create([
                                        'class_schedule_id'   => $schedule->id,
                                        'task_type'           => 're_exam',
                                        'title'               => "Remedial Re-Exam Task Evaluation Module for Student ID #{$student->user_id}",
                                        'max_score_threshold' => 100,
                                        'deadline_cut_off'    => now()->addDays(7),
                                    ]);
                                    $triggeredLinesCount++;
                                }
                            }
                        }
                    }

                    Notification::make()
                        ->title('Re-Exam Analysis Matrix Complete')
                        ->body("Successfully generated {$triggeredLinesCount} Re-Exams for failing Students.")
                        ->warning()
                        ->send();
                }),
        ];
    }
}
