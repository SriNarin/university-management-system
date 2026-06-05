<?php

namespace App\Filament\Faculty\Resources\SubjectFinalGrades; // 🌟 Match your real subfolder precisely

use App\Models\SubjectFinalGrade;
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
    protected static \UnitEnum|string|null $navigationGroup = 'Students Academic Results';
    protected static ?string $pluralModelLabel = 'Students Grades List';

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
                TextColumn::make('student.name')->label('Student')->searchable(),
                TextColumn::make('classSchedule.schoolClass.class_code')->label('Class Code'),
                TextColumn::make('classSchedule.subject_name_en')->label('Subject Course'),
                TextColumn::make('total_accumulated_score')->label('Official Scores')->weight('bold'),
                TextColumn::make('final_grade_letter')->label('Grade Rank')->badge(),

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