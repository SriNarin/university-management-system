<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacultyClassBarChart extends ChartWidget
{
    protected ?string $heading = '🏫 Active Class Containers per Department';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'faculty_manager';
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $managerId = Auth::id();

        $faculty = DB::table('faculties')->where('manager_id', $managerId)->first();

        if (!$faculty) {
            return ['datasets' => [['data' => [0], 'backgroundColor' => '#9CA3AF']], 'labels' => ['No Assigned Faculty']];
        }

        $classMetrics = DB::table('departments')
            ->join('academic_structures', 'departments.id', '=', 'academic_structures.department_id')
            ->join('school_classes', 'academic_structures.id', '=', 'school_classes.academic_structure_id')
            ->where('departments.faculty_id', $faculty->id)
            ->select('departments.name_en as dept_name', DB::raw('COUNT(school_classes.id) as total_classes'))
            ->groupBy('departments.id', 'departments.name_en')
            ->get();

        if ($classMetrics->isEmpty()) {
            return ['datasets' => [['data' => [0], 'backgroundColor' => '#9CA3AF']], 'labels' => ['No Active Classes']];
        }

        // A mixing blend of distinct colors for the individual bars
        $barColors = [
            '#3B82F6', // Blue
            '#10B981', // Emerald Green
            '#F59E0B', // Amber Gold
            '#EC4899', // Pink
            '#8B5CF6', // Purple
            '#EF4444', // Red
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Running Classes',
                    'data' => $classMetrics->pluck('total_classes')->toArray(),
                    // Each bar item will map onto its respective color element down the index line
                    'backgroundColor' => array_slice($barColors, 0, $classMetrics->count()),
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $classMetrics->pluck('dept_name')->toArray(),
        ];
    }
}