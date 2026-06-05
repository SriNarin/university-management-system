<?php

namespace App\Filament\Teacher\Resources\SubjectFinalGrades\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\ClassSchedule;
use App\Models\SubjectFinalGrade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListSubjectFinalGrades extends ListRecords
{
    // Absolute string layout reference to prevent any autoloader definition mismatches
    protected static string $resource = 'App\Filament\Teacher\Resources\SubjectFinalGrades\SubjectFinalGradeResource';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calculateAllTotals')
                ->label('🔄 Calculate & Compile Total Scores')
                ->color('primary')
                ->action(function () {
                    $teacherId = Auth::id();
                    
                    // Fetch all schedules assigned to the logged-in teacher
                    $schedules = ClassSchedule::where('teacher_id', $teacherId)->get();

                    foreach ($schedules as $schedule) {
                        // Fetch students enrolled in this class group
                        $students = User::whereHas('schoolClasses', function ($query) use ($schedule) {
                            $query->where('class_user.school_class_id', $schedule->school_class_id);
                        })->get();

                        foreach ($students as $student) {
                            // Sum up secured scores across all assessments for this specific schedule module
                            $totalScore = DB::table('assessment_submissions')
                                ->join('task_assessments', 'assessment_submissions.task_assessment_id', '=', 'task_assessments.id')
                                ->where('task_assessments.class_schedule_id', $schedule->id)
                                ->where('assessment_submissions.student_id', $student->id)
                                ->sum('assessment_submissions.secured_score');

                            // 📊 Academic Grading Matrix Evaluation: Computes both Grade Letters and GPA values
                            $gradeLetter = 'F';
                            $gpaValue = 0.00;

                            if ($totalScore >= 95) {
                                $gradeLetter = 'A+';
                                $gpaValue = 4.00;
                            } elseif ($totalScore >= 90) {
                                $gradeLetter = 'A';
                                $gpaValue = 4.00;
                            } elseif ($totalScore >= 85) {
                                $gradeLetter = 'B+';
                                $gpaValue = 3.50;
                            } elseif ($totalScore >= 80) {
                                $gradeLetter = 'B';
                                $gpaValue = 3.50;
                            } elseif ($totalScore >= 75) {
                                $gradeLetter = 'C+';
                                $gpaValue = 3.00;
                            } elseif ($totalScore >= 70) {
                                $gradeLetter = 'C';
                                $gpaValue = 3.00;
                            } elseif ($totalScore >= 60) {
                                $gradeLetter = 'D';
                                $gpaValue = 2.50;
                            } elseif ($totalScore >= 50) {
                                $gradeLetter = 'E';
                                $gpaValue = 2.50;
                            } else {
                                $gradeLetter = 'F';
                                $gpaValue = 2.00;
                            }

                            // Update or Insert directly into the persistent grade ledger table
                            SubjectFinalGrade::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'class_schedule_id' => $schedule->id,
                                ],
                                [
                                    'total_accumulated_score' => $totalScore,
                                    'final_grade_letter' => $gradeLetter, // Stores the computed letter grade matrix
                                    // Note: If you add a `gpa` column to your migration layout later, uncomment the line below:
                                    // 'gpa' => $gpaValue, 
                                ]
                            );
                        }
                    }

                    // Force Livewire layout table state rewrite
                    $this->dispatch('refreshTable');
                }),
        ];
    }
}