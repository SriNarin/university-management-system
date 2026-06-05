<?php

namespace App\Filament\Teacher\Resources;

use App\Models\LessonMaterial;
use App\Models\ClassSchedule;
use App\Filament\Teacher\Resources\LessonMaterialResource\Pages;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString; // 🌟 Added to securely render native HTML links within table cells

class LessonMaterialResource extends Resource
{
    protected static ?string $model = LessonMaterial::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;
    protected static \UnitEnum|string|null $navigationGroup = 'Lesson Materials';
    protected static ?string $pluralModelLabel = 'Lesson Materials';

    /**
     * 🔒 Security Filter: Restricts the view to only show lesson materials uploaded for
     * schedules assigned to the logged-in teacher.
     */
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
                Section::make('Lesson Document & Vault Properties')
                    ->description('Assign this material to a specific class schedule slot and upload files for student distribution.')
                    ->schema([
                        Select::make('class_schedule_id')
                            ->label('Target Assigned Subject Class')
                            ->options(fn () => ClassSchedule::where('teacher_id', Auth::id())
                                ->with(['schoolClass','academicStructure.department'])
                                ->get()
                                ->mapWithKeys(fn($item) => [
                                    $item->id => "{$item->schoolClass?->class_code} — {$item->subject_name_en} ({$item->day_of_week}) - [{$item->schoolClass?->academicStructure?->department?->name_en} {$item->schoolClass?->academicStructure?->generation} ({$item->schoolClass?->academicStructure?->academic_level}) — {$item->schoolClass?->academicStructure?->year_progress}] — {$item->schoolClass?->shift} — Room {$item->schoolClass?->room_number}"
                                ]))
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('lecture_title_topic')
                            ->label('Lecture Title / Topic Heading')
                            ->placeholder('e.g., Chapter 1: Introduction to Web Architectures')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->autofocus(),

                        FileUpload::make('resource_attachment_path')
                            ->label('Lesson Material Document uploads')
                            ->helperText('Upload PDFs, Word documents, Excel workbooks, PowerPoint slides, or instructional images.')
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
                            ->directory('university-lessons-vault')
                            ->visibility('public') // Allows direct student asset access via storage links
                            ->preserveFilenames()
                            ->required(),

                        Toggle::make('is_visible_to_students')
                            ->label('Publish Visibility Status')
                            ->helperText('If turned off, this material will remain hidden as a draft for the teacher only.')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->searchable()
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->color('info'),

                TextColumn::make('classSchedule.schoolClass.class_code')
                    ->label('Class Code')
                    ->badge()
                    ->sortable()
                    ->color('warning'),

                TextColumn::make('classSchedule.subject_name_en')
                    ->label('Subject / Course Title')
                    ->searchable()
                    ->sortable()
                    ->color('success'),

                TextColumn::make('lecture_title_topic')
                    ->label('Topic / Lesson Content Heading')
                    ->color('info')
                    ->searchable(),

                IconColumn::make('is_visible_to_students')
                    ->label('Published to Students')
                    ->boolean()
                    ->sortable(),

                // 📂 CRASH-FREE SOLUTION: Clickable file link directly inside a standard column cell
                TextColumn::make('resource_attachment_path')
                    ->label('Document Resource')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return 'No Attachment';
                        }
                        return new HtmlString('
                            <a href="' . asset('storage/' . $state) . '" target="_blank" download style="color: #4f46e5; text-decoration: underline; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;">
                                📥 Download File
                            </a>
                        ');
                    }),

                TextColumn::make('created_at')
                    ->label('Uploaded Timestamp')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('faculty')
                    ->relationship('classSchedule.schoolClass.academicStructure.department.faculty', 'name_en')
                    ->searchable()
                    ->label('Faculty'),

                SelectFilter::make('department')
                    ->relationship('classSchedule.schoolClass.academicStructure.department', 'name_en')
                    ->searchable()
                    ->label('Department'),

                SelectFilter::make('class_schedule')
                    ->relationship('classSchedule', 'id')
                    ->searchable()
                    ->label('Class Schedule'),
            ])
            ->actions([]) // ❌ COMPLETELY EMPTY. Safely bypasses any Action::make() execution breakdowns.
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLessonMaterials::route('/'),
            'create' => Pages\CreateLessonMaterial::route('/create'),
            'edit'   => Pages\EditLessonMaterial::route('/{record}/edit'),
        ];
    }
}