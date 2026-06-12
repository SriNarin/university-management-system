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

    protected static \UnitEnum|string|null $navigationGroup = 'Manage Department & Class';
    
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

                // // 👨‍🏫 TEACHER DISPLAY COLUMN
                // TextColumn::make('teacher.name')
                //     ->label('Assigned Teacher')
                //     ->searchable()
                //     ->sortable()
                //     ->weight('bold')
                //     ->color('info')
                //     ->placeholder('No Teacher Assigned'),
               
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
                IconColumn::make('is_timetable_published')
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
                    ->label(fn (SchoolClass $record): string => $record->is_timetable_published ? 'Hide From Teachers' : 'Post to All Teachers')
                    ->icon('heroicon-o-paper-airplane')
                    ->color(fn (SchoolClass $record): string => $record->is_timetable_published ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Complete Timetable Matrix')
                    ->modalDescription('This will parse all schedules, read the assigned teachers for each subject slot, and publish them directly to their respective dashboards automatically.')
                    ->action(function (SchoolClass $record) {
                        if ($record->is_timetable_published) {
                            // --- UNPUBLISHING CODE ---
                            
                            // 1. Get all teachers assigned to this class schedules matrix to clean pivot maps
                            $assignedTeacherIds = $record->classSchedules()
                                ->whereNotNull('teacher_id')
                                ->pluck('teacher_id')
                                ->unique()
                                ->toArray();

                            if (!empty($assignedTeacherIds)) {
                                $record->users()->detach($assignedTeacherIds);
                            }

                            // 2. Hide the timetable globally
                            $record->update([
                                'is_timetable_published' => false,
                            ]);
                            
                        } else {
                            // --- AUTOMATIC BULK PUBLISHING CODE ---

                            // 1. Scan the schedule grid slots to extract all assigned unique teacher IDs
                            $assignedTeacherIds = $record->classSchedules()
                                ->whereNotNull('teacher_id')
                                ->pluck('teacher_id')
                                ->unique()
                                ->toArray();

                            // 2. Turn on publication flag on the parent class level
                            $record->update([
                                'is_timetable_published' => true,
                            ]);

                            // 3. 🚀 Automatically sync ALL detected teachers to the pivot array at once
                            // using syncWithoutDetaching prevents breaking existing student enrollment rows!
                            if (!empty($assignedTeacherIds)) {
                                $record->users()->syncWithoutDetaching($assignedTeacherIds);
                            }
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