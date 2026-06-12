<?php

namespace App\Filament\Office\Resources\SubjectFinalGrades; 

use App\Models\SubjectFinalGrade;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;

// 🌟 FIX 1: Explicitly import your actual List page namespace so Filament knows where to look!
use App\Filament\Office\Resources\SubjectFinalGrades\Pages\ListSubjectFinalGrades;

class SubjectFinalGradeResource extends Resource
{
    protected static ?string $model = SubjectFinalGrade::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
    protected static \UnitEnum|string|null $navigationGroup = 'Grades Result Management';
    protected static ?string $pluralModelLabel = 'Student Scores & Transcripts';

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
                TextColumn::make('student.name')->label('Student Name')->searchable()->sortable()->weight('bold'), 
                TextColumn::make('classSchedule.schoolClass.class_code')->label('Class Enrolled')->badge()->color('warning')->sortable()->searchable(),
                TextColumn::make('classSchedule.subject_name_en')->label('Subject Module')->color('info')->weight('bold')->sortable()->searchable(),
                TextColumn::make('total_accumulated_score')->label('Subject Total Score')->weight('bold')->color('danger')->sortable()->searchable(),
                TextColumn::make('final_grade_letter')->label('Grade')->badge()->sortable()->searchable()->weight('bold')->color('success'),

                ToggleColumn::make('is_approved_by_manager')
                    ->label('Forward to Manager Review')
                    
                    ->beforeStateUpdated(function ($record, $state) {
                        if ($state) {
                            $record->approved_by_manager_at = now();
                        }
                    }),
                
                // 🌟 Custom ViewColumn calling your custom blade icon file
                ViewColumn::make('id')
                    ->label('Print Student Official Transcript')
                    ->view('filament.tables.columns.transcript-download-action')
                    ->alignCenter(),
            ])
            ->actions([
            
                
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            // 🌟 FIX 2: Bind the class explicitly instead of using the broken Pages string macro
            'index' => ListSubjectFinalGrades::route('/'),
        ];
    }
}