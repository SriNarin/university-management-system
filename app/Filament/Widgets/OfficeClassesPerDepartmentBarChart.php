<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeClassesPerDepartmentBarChart extends ChartWidget
{
    // 🌟 FIXED: Removed 'static' to match Filament's parent class definition
    protected ?string $heading = '🏫 Active Class Containers per Department';
    
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'study_office';
    }

    protected function getData(): array
    {
        // Query class allocations grouped across organizational departments
        $data = DB::table('school_classes')
            ->join('academic_structures', 'school_classes.academic_structure_id', '=', 'academic_structures.id')
            ->join('departments', 'academic_structures.department_id', '=', 'departments.id')
            ->select('departments.name_en as dept_name', DB::raw('COUNT(school_classes.id) as total_classes'))
            ->groupBy('departments.id', 'departments.name_en')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [['data' => [0], 'backgroundColor' => '#9CA3AF']],
                'labels' => ['No Active Classes'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Running Classes',
                    'data' => $data->pluck('total_classes')->toArray(),
                    'backgroundColor' => [
                        '#8B5CF6', // Purple
                        '#3B82F6', // Blue
                        '#10B981', // Green
                        '#F59E0B', // Amber Gold
                    ],
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $data->pluck('dept_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}