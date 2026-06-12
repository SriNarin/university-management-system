<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacultyDepartmentPieChart extends ChartWidget
{
    protected ?string $heading = '📊 Student Distribution by Department';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'faculty_manager';
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $managerId = Auth::id();

        $faculty = DB::table('faculties')->where('manager_id', $managerId)->first();

        if (!$faculty) {
            return ['datasets' => [['data' => [0], 'backgroundColor' => ['#9CA3AF']]], 'labels' => ['No Assigned Faculty']];
        }

        $departmentData = DB::table('departments')
            ->join('academic_structures', 'departments.id', '=', 'academic_structures.department_id')
            ->join('school_classes', 'academic_structures.id', '=', 'school_classes.academic_structure_id')
            ->join('class_user', 'school_classes.id', '=', 'class_user.school_class_id')
            ->where('departments.faculty_id', $faculty->id)
            ->where('class_user.approval_status', 'approved')
            ->select('departments.name_en as dept_name', DB::raw('COUNT(DISTINCT class_user.user_id) as total_students'))
            ->groupBy('departments.id', 'departments.name_en')
            ->get();

        if ($departmentData->isEmpty()) {
            return ['datasets' => [['data' => [0], 'backgroundColor' => ['#9CA3AF']]], 'labels' => ['No Students Registered']];
        }

        // Vibrant distinct colors for each department slice
        $colors = [
            '#FF6384', // Coral Red
            '#36A2EB', // Sky Blue
            '#FFCE56', // Sunset Yellow
            '#4BC0C0', // Turquoise Teal
            '#9966FF', // Lavender Purple
            '#FF9F40', // Tangerine Orange
            '#111827', // Obsidian Dark
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Students Total',
                    'data' => $departmentData->pluck('total_students')->toArray(),
                    // Slices colors array dynamically to match row count
                    'backgroundColor' => array_slice($colors, 0, $departmentData->count()),
                ],
            ],
            'labels' => $departmentData->pluck('dept_name')->toArray(),
        ];
    }
}