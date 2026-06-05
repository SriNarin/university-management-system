<?php

namespace App\Filament\Student\Resources\StudentTaskExecutions\Pages;

use App\Filament\Student\Resources\StudentTaskExecutionResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\AssessmentSubmission;
use App\Helpers\GradeCalculator;
use Illuminate\Support\Facades\Auth;

class ExecuteTaskSubmission extends EditRecord
{
    protected static string $resource = StudentTaskExecutionResource::class;
    protected static ?string $title = 'Execute Task';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Intercept save mutations to bypass normal model updaters
        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $studentId = Auth::id();
        $securedScore = 0;
        $qcmResponses = $data['qcm_responses'] ?? null;

        // 🌟 QCM AUTOMATED MACHINE AUTO-CORRECTION GRADING PIPELINE
        if (in_array($record->task_type, ['quiz', 'homework', 'midterm', 'final_exam', 're_exam']) && !empty($record->qcm_blueprint)) {
            $blueprint = $record->qcm_blueprint ?? [];
            $totalQuestions = count($blueprint);
            $correctCount = 0;

            foreach ($blueprint as $question) {
                $qId = $question['question_id'] ?? null;
                $correctKey = $question['correct_key_answer'] ?? null;
                
                // Track matching option selections
                if ($qId && isset($qcmResponses[$qId]) && trim($qcmResponses[$qId]) === trim($correctKey)) {
                    $correctCount++;
                }
            }

            if ($totalQuestions > 0) {
                $securedScore = ($correctCount / $totalQuestions) * $record->max_score_threshold;
            }
        }

        // Translate numeric results to standard Cambodia Grade Letters for auto-corrected tasks
        $derivedGrade = GradeCalculator::getLetter((float)$securedScore, (float)$record->max_score_threshold);

        // Save or log directly to the assessment_submissions tracking table
        AssessmentSubmission::updateOrCreate(
            [
                'task_assessment_id' => $record->id,
                'student_id'         => $studentId,
            ],
            [
                'secured_score'           => $securedScore,
                'grade_letter'            => $derivedGrade,
                'submission_notes'        => $data['submission_notes'] ?? null,
                'attachment_file_path'    => $data['attachment_file_path'] ?? null,
                'student_qcm_responses'   => $qcmResponses,
                'manager_approval_status' => 'pending',
            ]
        );

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}