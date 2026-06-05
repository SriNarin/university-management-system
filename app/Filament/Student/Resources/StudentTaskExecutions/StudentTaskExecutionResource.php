<?php

namespace App\Filament\Student\Resources;

use App\Models\TaskAssessment;
use App\Models\AssessmentSubmission;
use App\Models\ClassSchedule;
// 🌟 FIX: Removed the plural "s" from StudentTaskExecutions to match your singular directory structure
use App\Filament\Student\Resources\StudentTaskExecutions\Pages;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StudentTaskExecutionResource extends Resource
{
    protected static ?string $model = TaskAssessment::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CommandLine;
    protected static \UnitEnum|string|null $navigationGroup = 'Class Assessments';
    protected static ?string $pluralModelLabel = 'Task Assessments';

    /**
     * 🔒 Security Query Scope: Only displays tasks belonging to classes
     * where the logged-in student is enrolled and approved.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('classSchedule.schoolClass.Users', function (Builder $query) {
            $query->where('class_user.user_id', Auth::id()) // Explicitly target the pivot table column
                  ->where('class_user.approval_status', 'approved');
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Task Submission Workspace')
                    ->description('Review Task Assessment below, verify deadline timings, and fulfill required files for submission on time.')
                    ->schema([
                        Placeholder::make('task_headline')
                            ->label('Current Task Heading')
                            ->content(fn ($record) => "{$record->title} [Category: " . strtoupper($record->task_type) . " — Max Score: {$record->max_score_threshold}]"),

                        Placeholder::make('due_date')
                            ->label('Deadline Timeline')
                            ->content(fn ($record) => $record->deadline_cut_off->timezone('Asia/Phnom_Penh')->format('M d, Y h:i A')),

                        // 📝 INTERACTIVE MULTIPLE CHOICE (QCM) QUIZ / EXAM BUILDER
                        Section::make('Multiple Choice QCM Sheet')
                            ->description('Select the correct answers for each questions below.')
                            ->visible(fn ($record) => in_array($record->task_type, ['quiz', 'homework', 'midterm', 'final_exam', 're_exam']) && !empty($record->qcm_blueprint))
                            ->schema(function ($record) {
                                $fields = [];
                                $blueprint = $record->qcm_blueprint ?? [];
                                foreach ($blueprint as $index => $question) {
                                    $options = $question['options'] ?? [];
                                    $fields[] = Radio::make("qcm_responses.{$question['question_id']}")
                                        ->label(($index + 1) . ". " . ($question['question_text'] ?? 'Question'))
                                        ->options($options)
                                        ->required();
                                }
                                return $fields;
                            }),

                        // 📂 PORTFOLIO ASSIGNMENT / PROJECT FILE ARCHIVE UPLOADER
                        FileUpload::make('attachment_file_path')
                            ->label('Upload Assignment / Project File Portfolio or Files Zip /  Presentation files here...')
                            ->helperText('Supported extensions: PDFs, Microsoft Word Docs, Excel worksheets, PowerPoint slides, and reference images.')
                            ->visible(fn ($record) => in_array($record->task_type, ['assignment', 'project']))
                            ->directory('student-submissions-vault')
                            ->visibility('public')
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
                            ]),

                        Textarea::make('submission_notes')
                            ->label('Optional Explanatory Notes / Comments')
                            ->placeholder('Provide any necessary context or descriptive remarks for the reviewing professor here...')
                            ->rows(3)
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('class_schedule_id') // Targets the foreign key column directly
                    ->label('Teacher')
                    ->searchable()
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        // Safe-navigation checks down the relationship tree chain
                        // Handles both snake_case and camelCase relation call variants
                        $schedule = $record->classSchedule ?? $record->class_schedule;
                        
                        if (!$schedule) {
                            return 'N/A';
                        }

                        $teacher = $schedule->teacher;

                        if (!$teacher) {
                            return 'No Teacher Assigned';
                        }

                        // Checks 'name' first (used in your class hub); falls back to 'name_en' if needed
                        return $teacher->name ?? $teacher->name_en ?? 'Unnamed Teacher';
                    }),
                    
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->color('primary'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->color('info'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('danger'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('secondary'),

                TextColumn::make('classSchedule.schoolClass.class_code')
                    ->label('Class Code')
                    ->badge()
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.shift')
                    ->label('Shift')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('classSchedule.schoolClass.room_number')
                    ->label('Room Number')
                    ->badge()
                    ->color('info'),

                TextColumn::make('classSchedule.subject_name_en')
                    ->label('Subject Title')
                    ->searchable()
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('title')
                    ->label('Task Assessment Title')
                    ->searchable()
                    ->sortable(),
                    

                TextColumn::make('task_type')
                    ->label('Assessment Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'quiz' => 'warning',
                        'assignment' => 'info',
                        'project' => 'success',
                        'midterm', 'final_exam' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('deadline_cut_off')
                    ->label('Submission Deadline')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, h:i A')
                    ->sortable(),

                TextColumn::make('id')
                    ->label('Your Scoring Grade')
                    ->sortable()
                    ->searchable()
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        $submission = AssessmentSubmission::where('task_assessment_id', $record->id)
                            ->where('student_id', Auth::id())
                            ->first();

                        if (!$submission) return '❌ Not Submitted';
                        
                        return "{$submission->secured_score} / {$record->max_score_threshold} [Grade: {$submission->grade_letter}]";
                    })
                    ->badge()
                    ->color(fn ($state) => str_contains($state, 'Not Submitted') ? 'danger' : 'success'),
            ])
            ->filters([])
            ->actions([
                // Handled via ViewAction clicking natively
            ]);
    }

    public static function getPages(): array
    {
        // 🌟 FIX: Updated references to point cleanly to the singular resource pages layout directory maps
        return [
            'index' => Pages\ListStudentTaskExecutions::route('/'),
            'view'  => Pages\ViewStudentTaskExecution::route('/{record}'),
            'edit'  => Pages\ExecuteTaskSubmission::route('/{record}/execute'),
        ];
    }
}