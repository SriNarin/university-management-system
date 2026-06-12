<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeStudentDistributionPieChart extends ChartWidget
{
    // 🌟 FIXED: Removed 'static' to match Filament's parent class definition
    protected ?string $heading = '📊 Student Distribution by Department';
    
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'study_office';
    }

    protected function getData(): array
    {
        // Query approved unique students grouped by their class department mappings
        $data = DB::table('class_user')
            ->join('school_classes', 'class_user.school_class_id', '=', 'school_classes.id')
            ->join('academic_structures', 'school_classes.academic_structure_id', '=', 'academic_structures.id')
            ->join('departments', 'academic_structures.department_id', '=', 'departments.id')
            ->where('class_user.approval_status', 'approved') // 🌟 FIXED: Corrected query string syntax typo
            ->select('departments.name_en as dept_name', DB::raw('COUNT(DISTINCT class_user.user_id) as total_students'))
            ->groupBy('departments.id', 'departments.name_en')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [['data' => [0], 'backgroundColor' => ['#9CA3AF']]],
                'labels' => ['No Enrolled Students'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Students Total',
                    'data' => $data->pluck('total_students')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB', // Sky Blue
                        '#4BC0C0', // Turquoise Teal
                        '#FF6384', // Coral Red
                        '#FF9F40', // Tangerine Orange
                        '#9966FF', // Lavender Purple
                    ],
                ],
            ],
            'labels' => $data->pluck('dept_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}