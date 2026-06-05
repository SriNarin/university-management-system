<?php

namespace App\Filament\Teacher\Resources\SubjectFinalGrades;

use App\Models\SubjectFinalGrade;
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
                TextColumn::make('student.name')->label('Student Name')->searchable(),
                TextColumn::make('classSchedule.schoolClass.class_code')->label('Class Code')->badge(),
                TextColumn::make('classSchedule.subject_name_en')->label('Subject Course'),
                
                TextInputColumn::make('total_accumulated_score')
                    ->label('Total Score')
                    ->type('number')
                    ->disabled(fn ($record) => $record->is_submitted_to_office),

                TextInputColumn::make('final_grade_letter')
                    ->label('Final Grade')
                    ->disabled(fn ($record) => $record->is_submitted_to_office),

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
            // 🌟 Pointing to your exact file layout folder name
            'index' => \App\Filament\Teacher\Resources\SubjectFinalGrades\Pages\ListSubjectFinalGrades::route('/'),
        ];
    }
}