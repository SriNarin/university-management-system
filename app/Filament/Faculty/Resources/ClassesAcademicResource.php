<?php

namespace App\Filament\Faculty\Resources;

use App\Models\SchoolClass;
use App\Models\User;
use App\Filament\Faculty\Resources\ClassesAcademicResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class ClassesAcademicResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;

    protected static \UnitEnum|string|null $navigationGroup = 'Academic Classes Management';
    
    protected static ?string $pluralModelLabel = 'Class Timetable Management';

    protected static ?string $slug = 'Classes Timetables';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Ref ID')
                    ->sortable(),

                TextColumn::make('academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->color('danger'),
                
                TextColumn::make('academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->color('secondary'),
                
                TextColumn::make('academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('info'),
                
                TextColumn::make('academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('success'),
                
                TextColumn::make('academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('class_code')
                    ->label('Class Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                // 👨‍🏫 TEACHER DISPLAY COLUMN
                TextColumn::make('teacher.name')
                    ->label('Assigned Teacher')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('info')
                    ->placeholder('No Teacher Assigned'),
               
                Tables\Columns\TextColumn::make('shift')
                    ->label('Shift')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'morning' => 'warning',
                        'afternoon' => 'info',
                        'evening' => 'primary',
                        'weekend' => 'danger',
                        'full_day' => 'success',
                        'online' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->toggleable(),

                // Status indicator for Teacher visibility
                IconColumn::make('is_teacher_timetable_published')
                    ->label('Timetable Ready')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                // 🖨️ Custom View Column with slider toggle and print icon
                Tables\Columns\ViewColumn::make('download_timetable')
                    ->label('🖨️ Print Timetable')
                    ->view('filament.tables.columns.timetable-download-action')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('shift')
                    ->options([
                        'morning' => 'Morning',
                        'afternoon' => 'Afternoon',
                        'evening' => 'Evening',
                    ]),
            ])
            ->actions([
                // 🚀 INTERACTIVE ACTION MODAL: Prompts manager to pick a teacher before posting!
                Action::make('publishToTeacher')
                    ->label(fn (SchoolClass $record): string => $record->is_teacher_timetable_published ? 'Hide From Teacher' : 'Post to Teacher')
                    ->icon('heroicon-o-paper-airplane')
                    ->color(fn (SchoolClass $record): string => $record->is_teacher_timetable_published ? 'danger' : 'success')
                    ->requiresConfirmation()
                    
                    // 📑 Pop up the instructor selection form when publishing
                    ->form(fn (SchoolClass $record) => $record->is_teacher_timetable_published ? [] : [
                        Select::make('teacher_id')
                            ->label('Assign Teacher Instructor')
                            ->relationship('teacher', 'name') // Pulls from your User/Teacher model
                            ->searchable()
                            ->preload()
                            ->default($record->teacher_id)
                            ->required(),
                    ])
                    
                    // 💾 Execute pivot table synchronization alongside status updates
                    ->action(function (SchoolClass $record, array $data) {
                        if ($record->is_teacher_timetable_published) {
                            // --- UNPUBLISHING CODE ---
                            // 1. Remove the teacher from the class_user pivot table if they exist
                            if ($record->teacher_id) {
                                // If using a standard Many-to-Many relationship named 'users' on your SchoolClass model:
                                $record->users()->detach($record->teacher_id);
                                
                                // ALTERNATIVE (If you have a explicit 'teachers' relationship instead):
                                // $record->teachers()->detach($record->teacher_id);
                            }

                            // 2. Turn off the publication flag
                            $record->update([
                                'is_teacher_timetable_published' => false,
                            ]);
                        } else {
                            // --- PUBLISHING CODE ---
                            $newTeacherId = $data['teacher_id'];

                            // 1. Update the parent class row with the teacher reference
                            $record->update([
                                'teacher_id' => $newTeacherId,
                                'is_teacher_timetable_published' => true,
                        ]);

                        // 2. Sync into the class_user pivot table so the dashboard queries can read it!
                        // using syncWithoutDetaching ensures we don't accidentally remove enrolled students!
                        $record->users()->syncWithoutDetaching([$newTeacherId]);
                    }
                }),
            ])
            ->bulkActions([])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassesAcademics::route('/'),
        ];
    }
}