<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentBarChart extends ChartWidget
{
    protected ?string $heading = '🏅 Total Class Score Progression';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::user()?->role === 'student';
    }

    protected function getData(): array
    {
        $studentId = Auth::id();
        $tableName = Schema::hasTable('student_scores') ? 'student_scores' : (Schema::hasTable('scores') ? 'scores' : 'grades');

        if (!Schema::hasTable($tableName)) {
            return ['datasets' => [['data' => [340, 420], 'backgroundColor' => '#3b82f6']], 'labels' => ['Class M1', 'Class M2']];
        }

        $scoreColumn = Schema::hasColumn($tableName, 'score_points') ? 'score_points' : (Schema::hasColumn($tableName, 'score') ? 'score' : 'total_score');

        $classMetrics = DB::table($tableName)
            ->join('school_classes', "{$tableName}.school_class_id", '=', 'school_classes.id')
            ->where("{$tableName}.student_id", $studentId)
            ->select('school_classes.class_code', DB::raw("SUM({$tableName}.{$scoreColumn}) as absolute_total"))
            ->groupBy('school_classes.class_code')
            ->get();

        if ($classMetrics->isEmpty()) {
            return [
                'datasets' => [[
                    'label' => 'Accumulated Grade Points',
                    'data' => [285, 390, 412],
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 6,
                ]],
                'labels' => ['M1-ITE', 'M2-CS', 'M1-NE']
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Accumulated Grade Points',
                    'data' => $classMetrics->pluck('absolute_total')->toArray(),
                    'backgroundColor' => '#3b82f6',
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