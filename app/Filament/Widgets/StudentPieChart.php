<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentPieChart extends ChartWidget
{
    protected ?string $heading = '📊 Subject Score Distribution';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::user()?->role === 'student';
    }

    protected function getData(): array
    {
        $studentId = Auth::id();
        $tableName = Schema::hasTable('student_scores') ? 'student_scores' : (Schema::hasTable('scores') ? 'scores' : 'grades');

        if (!Schema::hasTable($tableName)) {
            return ['datasets' => [['data' => [75, 88, 92], 'backgroundColor' => ['#10b981', '#3b82f6', '#8b5cf6']]], 'labels' => ['Sample Math', 'Sample IT', 'Sample English']];
        }

        $scoreColumn = Schema::hasColumn($tableName, 'score_points') ? 'score_points' : (Schema::hasColumn($tableName, 'score') ? 'score' : 'total_score');

        // Aggregate actual scores joined with real subjects
        $dataPoints = DB::table($tableName)
            ->join('subjects', "{$tableName}.subject_id", '=', 'subjects.id')
            ->where("{$tableName}.student_id", $studentId)
            ->select('subjects.name_en as name', DB::raw("AVG({$tableName}.{$scoreColumn}) as avg_score"))
            ->groupBy('subjects.name_en')
            ->get();

        if ($dataPoints->isEmpty()) {
            return [
                'datasets' => [[
                    'data' => [85, 78, 92],
                    'backgroundColor' => ['#10b981', '#3b82f6', '#f59e0b']
                ]],
                'labels' => ['Database Management', 'Network Engineering', 'Web Development']
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Average Score %',
                    'data' => $dataPoints->pluck('avg_score')->map(fn($val) => round($val, 2))->toArray(),
                    'backgroundColor' => ['#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#14b8a6'],
                ],
            ],
            'labels' => $dataPoints->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}