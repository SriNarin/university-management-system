<?php

namespace App\Filament\Faculty\Resources\SubjectFinalGrades; // 🌟 Match your real subfolder precisely

use App\Models\SubjectFinalGrade;
use App\Models\AcademicStructure;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class SubjectFinalGradeResource extends Resource
{
    protected static ?string $model = SubjectFinalGrade::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
    protected static \UnitEnum|string|null $navigationGroup = 'Students & Result Management';
    protected static ?string $pluralModelLabel = 'Students Grades Results';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_submitted_to_office', true);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->label('Student')->searchable()->sortable()->weight('bold')->color('danger'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')->label('Faculty')->weight('bold')->color('warning')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')->label('Department')->weight('bold')->color('info')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.academic_level')->label('Academic Level')->weight('bold')->color('success')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.year_progress')->label('Year Progress')->weight('bold')->color('primary')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.academicStructure.generation')->label('Generation')->weight('bold')->color('warning')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.class_code')->label('Class Code')->badge()->color('info')->weight('bold')->sortable()->searchable(),
                TextColumn::make('classSchedule.schoolClass.shift')->label('Class shift')->badge()->color('success')->weight('bold')->sortable()->searchable(),
                  TextColumn::make('classSchedule.schoolClass.room_number')->label('Room Number')->badge()->color('info')->weight('bold')->sortable()->searchable(),
                TextColumn::make('classSchedule.subject_name_en')->label('Subject Course')->weight('bold')->color('success')->sortable()->searchable(),

                TextColumn::make('total_accumulated_score')->label('Official Scores')->weight('bold')->color('danger')->sortable()->searchable(),
                TextColumn::make('final_grade_letter')->label('Grade Rank')->badge()->sortable()->searchable()->weight('bold')->color('success'),

                ToggleColumn::make('is_approved_by_manager')
                    ->label('Approved')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->approved_by_manager_at = $state ? now() : null;
                        $record->save();
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjectFinalGrades::route('/'),
        ];
    }
}