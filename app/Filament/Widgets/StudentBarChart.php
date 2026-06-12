<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentBarChart extends ChartWidget
{
    protected ?string $heading = '🏅 Total Class Score Progression';
    protected int | string | array $columnSpan = 'half';
    
    // Kept static to respect Filament v3 parent inheritance requirements
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    protected function getData(): array
    {
        $studentId = Auth::id();

        // Query the cumulative sum of secured_score grouped by each school class code
        $classMetrics = DB::table('assessment_submissions')
            ->join('task_assessments', 'assessment_submissions.task_assessment_id', '=', 'task_assessments.id')
            ->join('class_schedules', 'task_assessments.class_schedule_id', '=', 'class_schedules.id')
            ->join('school_classes', 'class_schedules.school_class_id', '=', 'school_classes.id')
            ->where('assessment_submissions.student_id', $studentId)
            ->select(
                'school_classes.class_code', 
                DB::raw('SUM(assessment_submissions.secured_score) as absolute_total')
            )
            ->groupBy('school_classes.class_code')
            ->get();

        // Safe fallback in case zero assignments have been uploaded or graded yet
        if ($classMetrics->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Accumulated Grade Points',
                        'data' => [0],
                        'backgroundColor' => '#9CA3AF',
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => ['No Class Submissions Graded']
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Accumulated Grade Points',
                    'data' => $classMetrics->pluck('absolute_total')->map(fn($val) => round($val, 2))->toArray(),
                    'backgroundColor' => '#3B82F6', // Beautiful Tailwind blue bar color
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $classMetrics->pluck('class_code')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}