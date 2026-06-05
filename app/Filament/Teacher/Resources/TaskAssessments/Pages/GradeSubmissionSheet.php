<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\Pages;

use App\Filament\Teacher\Resources\TaskAssessments\TaskAssessmentResource;
use App\Models\TaskAssessment;
use App\Models\ClassUser;
use App\Models\AssessmentSubmission;
use App\Helpers\GradeCalculator;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;


class GradeSubmissionSheet extends Page
{
    protected static string $resource = TaskAssessmentResource::class;
    
    // Explicitly reuses your layout view file mapping directory structures cleanly 
    protected string $view = 'filament.teacher.resources.attendance-resource.pages.take-attendance-sheet';

    public TaskAssessment $record;
    public ?array $data = [];

    public function mount($record): void
    {
        $this->record = TaskAssessment::with('classSchedule.schoolClass')->findOrFail($record);

        $enrolledStudents = ClassUser::where('school_class_id', $this->record->classSchedule->school_class_id)
            ->where('approval_status', 'approved')
            ->with('user')
            ->get();

        $sheetGrid = [];
        foreach ($enrolledStudents as $enrollment) {
            if (!$enrollment->user) continue;

            $existing = AssessmentSubmission::where('task_assessment_id', $this->record->id)
                ->where('student_id', $enrollment->user->id)
                ->first();

            // 🌟 AUTO-QCM CALCULATION ENGINE LOGIC:
            // If the assessment contains a QCM blueprint and student responses are saved, calculate the score automatically.
            $calculatedScore = $existing?->secured_score ?? 0;
            if ($this->record->qcm_blueprint && $existing?->student_qcm_responses) {
                $blueprint = $this->record->qcm_blueprint;
                $responses = $existing->student_qcm_responses;
                $correctCount = 0;
                $totalQuestions = count($blueprint);

                foreach ($blueprint as $question) {
                    $qId = $question['question_id'] ?? null;
                    $correctKey = $question['correct_key_answer'] ?? null;
                    if ($qId && isset($responses[$qId]) && $responses[$qId] === $correctKey) {
                        $correctCount++;
                    }
                }

                if ($totalQuestions > 0) {
                    $calculatedScore = ($correctCount / $totalQuestions) * $this->record->max_score_threshold;
                }
            }

            $derivedLetter = GradeCalculator::getLetter((float)$calculatedScore, (float)$this->record->max_score_threshold);

            $sheetGrid[] = [
                'student_id'           => $enrollment->user->id,
                'student_name'         => $enrollment->user->name,
                'secured_score'        => $calculatedScore,
                'grade_letter'         => $derivedLetter,
                'submission_notes'     => $existing?->submission_notes ?? '',
                'attachment_file_path' => $existing?->attachment_file_path ?? null,
                'student_qcm_responses'=> $existing?->student_qcm_responses ?? [],
            ];
        }

        $this->form->fill([
            'submissions' => $sheetGrid
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make("Roster Assessment Workspace Panel: {$this->record->title} (Max Weights Metric: {$this->record->max_score_threshold})")
                    ->schema([
                        Repeater::make('submissions')
                            ->label('')
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->schema([
                                TextInput::make('student_name')
                                    ->label('Enrolled Student Name')
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('student_id')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->extraAttributes(['style' => 'display:none;'])
                                    ->label(''),

                                TextInput::make('secured_score')
                                    ->label('Secured Score (Numeric)')
                                    ->numeric()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $max = $this->record->max_score_threshold;
                                        $letter = GradeCalculator::getLetter((float)$state, (float)$max);
                                        $set('grade_letter', $letter);
                                    }),

                                TextInput::make('grade_letter')
                                    ->label('Grade Letter Code Representation')
                                    ->disabled()
                                    ->dehydrated(true),

                                TextInput::make('submission_notes')
                                    ->label('Internal Evaluator Critique Notes'),

                                // 🌟 FILE PORTFOLIO UPLOADER: Supports Word, Excel, PowerPoint, PDF, and Images
                                FileUpload::make('attachment_file_path')
                                    ->label('Student Attachment Vault (Word/Excel/PPT/PDF/Images)')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'application/vnd.ms-excel',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                        'application/vnd.ms-powerpoint',
                                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp'
                                    ])
                                    ->directory('academic-submissions-vault')
                                    ->visibility('private'),
                            ])->columns(3),
                    ])
            ])
            ->statePath('data');
    }

    public function saveAttendanceSheet() 
    {
        $formValues = $this->form->getState();
        $submissions = $formValues['submissions'] ?? [];

        foreach ($submissions as $row) {
            AssessmentSubmission::updateOrCreate(
                [
                    'task_assessment_id' => $this->record->id,
                    'student_id'         => $row['student_id'],
                ],
                [
                    'secured_score'        => $row['secured_score'],
                    'grade_letter'         => $row['grade_letter'],
                    'submission_notes'     => $row['submission_notes'],
                    'attachment_file_path' => $row['attachment_file_path'],
                ]
            );
        }

        Notification::make()
            ->title('Grades Record File Mapping Matrix Saved Successfully')
            ->success()
            ->send();

        return redirect(TaskAssessmentResource::getUrl('index'));
    }
}