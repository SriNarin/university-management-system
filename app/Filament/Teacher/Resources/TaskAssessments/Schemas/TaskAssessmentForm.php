<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskAssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('class_schedule_id')
                    ->required()
                    ->numeric(),
                Select::make('task_type')
                    ->options([
            'attendance_weight' => 'Attendance weight',
            'assignment' => 'Assignment',
            'midterm' => 'Midterm',
            'final_exam' => 'Final exam',
        ])
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('max_score_threshold')
                    ->required()
                    ->numeric()
                    ->default(100),
                DateTimePicker::make('deadline_cut_off')
                    ->required(),
            ]);
    }
}
