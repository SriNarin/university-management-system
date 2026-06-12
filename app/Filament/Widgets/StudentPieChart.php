<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentPieChart extends ChartWidget
{
    protected ?string $heading = '📊 Subject Score Distribution';
    protected int | string | array $columnSpan = 'half';
    
    // Kept static to adhere to Filament v3 structural rules
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    protected function getData(): array
    {
        $studentId = Auth::id();

        // 1. Get the student's actual grades/scores by joining submissions -> tasks -> schedules
        $dataPoints = DB::table('assessment_submissions')
            ->join('task_assessments', 'assessment_submissions.task_assessment_id', '=', 'task_assessments.id')
            ->join('class_schedules', 'task_assessments.class_schedule_id', '=', 'class_schedules.id')
            ->where('assessment_submissions.student_id', $studentId)
            ->select(
                'class_schedules.subject_name_en as subject_name',
                DB::raw('AVG(assessment_submissions.secured_score) as avg_score')
            )
            ->groupBy('class_schedules.subject_name_en')
            ->get();

        // 2. Fallback to sample placeholder values only if the student has zero tasks graded yet
        if ($dataPoints->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [0],
                        'backgroundColor' => ['#9CA3AF'],
                    ],
                ],
                'labels' => ['No Tasks Graded Yet'],
            ];
        }

        // 3. Generate a distinct, clean array palette of hex colors dynamically
        $colors = [
            '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', 
            '#F59E0B', '#14B8A6', '#F43F5E', '#6366F1'
        ];
        
        // Pad colors array if the student has more subjects than colors listed above
        $backgroundColors = array_slice(
            array_merge($colors, $colors, $colors), 
            0, 
            $dataPoints->count()
        );

        return [
            'datasets' => [
                [
                    'label' => 'Average Score',
                    'data' => $dataPoints->pluck('avg_score')->map(fn($score) => round($score, 2))->toArray(),
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $dataPoints->pluck('subject_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}