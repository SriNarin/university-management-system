<?php

namespace App\Filament\Student\Resources\SubjectFinalGrades;

use App\Models\SubjectFinalGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassSchedule;
use App\Models\ClassUser;
use App\Models\SchoolClass;
use Filament\Resources\Resource;
use App\Models\AcademicStructure;


use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\schema;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ViewColumn;



class SubjectFinalGradeResource extends Resource
{
    protected static ?string $model = SubjectFinalGrade::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;
    protected static ?string $pluralModelLabel = 'Official Grade Transcripts';

    public static function getEloquentQuery(): Builder
    {
        // Only show records belonging to the student that are fully signed off by the manager
        return parent::getEloquentQuery()
            ->where('student_id', Auth::id())
            ->where('is_approved_by_manager', true);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               

                TextColumn::make('classSchedule.subject_name_en')
                    ->label('Subject  Course')
                    ->weight('bold')
                    ->sortable()
                    ->searchable()
                    ->color('info'),
                

                TextColumn::make('total_accumulated_score')
                    ->label('Total Score ')
                    ->badge()
                    ->color('success'),

                TextColumn::make('final_grade_letter')
                    ->label('Academic Grade Evaluation')
                    ->badge()
                    ->color('danger'),

                // 🖨️ PRINT UTILITY CELL: Generates clean transcript prints safely without Action::make()
                ViewColumn::make('id')
                    ->label('Official Transcript Certificate')
                    ->view('filament.tables.columns.student-download-action'),

                    // ->alignCenter(),
                    // ->formatStateUsing(function ($record) {
                    //     return new HtmlString('
                    //         <a href="/transcripts/print/' . $record->id . '" target="_blank" style="color: #4f46e5; text-decoration: underline; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;">
                    //             🖨️ Print Transcript Certificate
                    //         </a>
                    //     ');
                    // }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Student\Resources\SubjectFinalGrades\Pages\ListSubjectFinalGrades::route('/'),
        ];
    }
}