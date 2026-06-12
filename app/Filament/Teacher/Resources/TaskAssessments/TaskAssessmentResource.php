<?php

namespace App\Filament\Teacher\Resources\TaskAssessments;


use App\Models\TaskAssessment;
use App\Models\ClassSchedule;
use App\Models\ClassUser;
use App\Models\AssessmentSubmission;
use App\Filament\Teacher\Resources\TaskAssessments\Pages;
use App\Filament\Teacher\Resources\TaskAssessments\RelationManagers;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;




class TaskAssessmentResource extends Resource
{
    protected static ?string $model = TaskAssessment::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;
    protected static \UnitEnum|string|null $navigationGroup = 'Student Task Assessment';
    protected static ?string $pluralModelLabel = 'Task Assessments';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('classSchedule', function (Builder $query) {
            $query->where('teacher_id', Auth::id());
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Create Task Students Assessment for this Subject Course')
                    ->schema([
                        Select::make('class_schedule_id')
                            ->label('Assigned Subject Course')
                            ->options(fn () => ClassSchedule::where('teacher_id', Auth::id())
                                ->with(['schoolClass','academicStructure.department'])
                                ->get()
                                ->mapWithKeys(fn($item) => [$item->id => "{$item->schoolClass?->class_code} — {$item->subject_name_en} [{$item->schoolClass?->academicStructure?->department?->name_en} {$item->schoolClass?->academicStructure?->generation} ({$item->schoolClass?->academicStructure?->academic_level}) — {$item->schoolClass?->academicStructure?->year_progress}] — {$item->schoolClass?->shift} — Room {$item->schoolClass?->room_number}" ]))
                            ->required()
                            ->live(),

                        Select::make('task_type')
                            ->label('Task Assessment Category')
                            ->options([
                                'attendance_weight' => 'Attendance Weight',
                                'quiz'              => 'Quiz Examination',
                                'assignment'        => 'Assignment Task',
                                'homework'          => 'Homework Task',
                                'midterm'           => 'Midterm Examination',
                                'project'           => 'Project Presentation',
                                'final_exam'        => 'Final Examination',
                                're_exam'           => 'Re-Exam Remedial',
                            ])
                            ->required()
                            ->live(),
                        
                        TextInput::make('title')
                            ->label('Task Assessment Title')
                            ->required(),

                        FileUpload::make('Task Assigned Files')
                            ->label('Task Assigned Files')
                            ->multiple()
                            ->image()
                            ->nullable()
                            ->maxSize(1024)
                            ->maxFiles(5)
                            ->columnSpanFull(),

                        TextInput::make('max_score_threshold')
                            ->label('Maximum Available Score ')
                            ->numeric()
                            ->default(100)
                            ->required(),

                        DateTimePicker::make('deadline_cut_off')
                            ->label('Expiry Submission Deadline')
                            ->required(),
                    ])->columns(2),

                Section::make('QCM Multiple Choice Test Question Creation ')
                    ->description('Set up your questions QCM here. When evaluating, matching student answers will execute auto-scoring cycles instantly.')
                    ->visible(fn (callable $get) => in_array($get('task_type'), ['quiz', 'homework', 'midterm', 'final_exam', 're_exam']))
                    ->schema([
                        Repeater::make('qcm_blueprint')
                            ->label('Questions Registry')
                            ->schema([
                                TextInput::make('question_id')
                                    ->label('Question Unique Key Identifier (e.g., q1, q2)')
                                    ->required(),

                                TextInput::make('question_text')
                                    ->label('Question Text Description')
                                    ->required(),
                                
                                KeyValue::make('options')
                                    ->label('Multiple Choice Options Setup Block')
                                    ->keyLabel('Option Key Code (e.g. A, B, C, D)')
                                    ->valueLabel('Option Answer Content text string')
                                    ->required(),

                                TextInput::make('correct_key_answer')
                                    ->label('The Authentic Absolute Correct Key Code')
                                    ->placeholder('e.g., A')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? 'New Quiz Item Row'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('danger'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('warning'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),

                TextColumn::make('classSchedule.schoolClass.class_code')
                    ->label('Class Code')
                    ->weight('bold')
                    ->color('success')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('classSchedule.schoolClass.shift')
                    ->label('Shift')
                    ->color('warning')
                    ->sortable()
                    ->weight('bold')
                    ->searchable()
                    ->badge(),
                TextColumn::make('classSchedule.schoolClass.room_number')
                    ->label('Room Number')
                    ->weight('bold')
                    ->color('info')
                    ->searchable()
                    ->badge(),
                TextColumn::make('classSchedule.subject_name_en')
                    ->label('Subject Title')
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('title')
                    ->label('Task Headline')
                    ->sortable()
                    ->weight('bold')
                    ->color('info')
                    ->searchable(),
                TextColumn::make('task_type')
                    ->label('Type')
                    ->weight('bold')
                    ->color('warning')
                    ->badge(),
                TextColumn::make('max_score_threshold')
                    ->label('Total Score')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('deadline_cut_off')
                    ->label('Deadline Timeline')
                    ->color('danger')
                    ->weight('bold')
                    ->dateTime('M d Y, H:i'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()->color('gray')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('info')->icon('heroicon-s-pencil')->size('sm'),
                DeleteAction::make()->color('danger')->icon('heroicon-s-trash')->size('sm'),
            ]);
    }

    public static function getRelations(): array
{
    return [
        RelationManagers\SubmissionsRelationManager::class,

    ];
}

    public static function getPages(): array

    {
        return [
            'index'  => Pages\ListTaskAssessments::route('/'),
            'create' => Pages\CreateTaskAssessment::route('/create'),
            'edit'   => Pages\EditTaskAssessment::route('/{record}/edit'),
            'grade'  => Pages\GradeSubmissionSheet::route('/{record}/grade'),
        ];
    }
}