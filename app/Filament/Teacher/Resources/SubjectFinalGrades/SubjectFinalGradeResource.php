<?php

namespace App\Filament\Teacher\Resources\SubjectFinalGrades;

use App\Models\SubjectFinalGrade;
use App\Models\Subject;
use App\Models\AcademicStructure;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use App\Helpers\GradeCalculator; // 🌟 Imported your GradeCalculator helper
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class SubjectFinalGradeResource extends Resource
{
    protected static ?string $model = SubjectFinalGrade::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
    protected static \UnitEnum|string|null $navigationGroup = 'Students Grades Management';
    protected static ?string $pluralModelLabel = 'Students Scores';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('classSchedule', function ($query) {
            $query->where('teacher_id', Auth::id());
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->label('Student Name')->searchable()->sortable()->weight('bold')->color('danger'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')->label('Faculty')->weight('bold')->color('warning')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')->label('Department')->weight('bold')->color('info')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.academic_level')->label('Academic Level')->weight('bold')->color('danger')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.year_progress')->label('Year Progress')->color('success')->weight('bold')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.class_code')->label('Class Code')->badge()->color('warning')->weight('bold')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.shift')->label('Class shift')->badge()->color('success')->weight('bold')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.room_number')->label('Room Number')->badge()->color('info')->weight('bold')->sortable()->searchable(),
               
                TextColumn::make('classSchedule.subject_name_en')->label('Subject Course')->weight('bold')->color('success')->sortable()->searchable(),

                
                TextInputColumn::make('total_accumulated_score')
                    ->label('Total Score')
                    ->type('number')
                    ->disabled(fn ($record) => $record->is_submitted_to_office)
                    // 🌟 AUTO-CALCULATION ENGINE HOOK
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state === null || $state === '') {
                            $record->update(['final_grade_letter' => null]);
                            return;
                        }

                        // Calculate the new letter using your helper (assuming max score is 100)
                        $calculatedLetter = GradeCalculator::getLetter((float)$state, 100.0);

                        // Save it directly to the record database row
                        $record->update(['final_grade_letter' => $calculatedLetter]);
                    }),

                // 🌟 Converted to a standard TextColumn so it displays the updated grade dynamically
                TextColumn::make('final_grade_letter')
                    ->label('Final Grade')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'A+', 'A', 'B+', 'B' => 'success',
                        'C+', 'C', 'D+', 'D' => 'warning',
                        default => 'danger'
                    }),

                ToggleColumn::make('is_submitted_to_office')
                    ->label('Submit to Office')
                    ->disabled(fn ($record) => $record->is_submitted_to_office)
                    ->beforeStateUpdated(function ($record, $state) {
                        if ($state) { $record->submitted_to_office_at = now(); }
                    }),

                TextColumn::make('is_approved_by_manager')
                    ->label('Faculty Manager Approval')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->is_approved_by_manager ? 'Approved' : 'Pending')
                    ->color(fn ($record) => $record->is_approved_by_manager ? 'success' : 'warning'),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Teacher\Resources\SubjectFinalGrades\Pages\ListSubjectFinalGrades::route('/'),
        ];
    }
}