<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminBarChart extends ChartWidget
{
    protected ?string $heading = '🏛️ Faculty Structural Mapping';

    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 3;
    public static function canView(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    protected function getData(): array
    {
        $faculties = Faculty::all();
        
        $labels = [];
        $departmentsData = [];
        $classesData = [];

        foreach ($faculties as $faculty) {
            $labels[] = $faculty->name_en ?? $faculty->name ?? 'Faculty';

            // 1. Safe count for departments linked to this faculty
            $departments = Department::where('faculty_id', $faculty->id)->get();
            $departmentsData[] = $departments->count();

            // 2. Fetch classes safely by adapting to your database schema columns
            $departmentIds = $departments->pluck('id')->toArray();
            
            if (Schema::hasColumn('school_classes', 'academic_structure_id')) {
                // If it maps through an intermediate academic structures track table
                $classCount = SchoolClass::whereIn('academic_structure_id', function($query) use ($departmentIds) {
                    $query->select('id')->from('academic_structures')->whereIn('department_id', $departmentIds);
                })->count();
            } elseif (Schema::hasColumn('school_classes', 'department_id')) {
                $classCount = SchoolClass::whereIn('department_id', $departmentIds)->count();
            } else {
                // Fallback: If it uses direct or custom native structures, grab total counts safely
                $classCount = SchoolClass::count();
            }

            $classesData[] = $classCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quantity of Departments',
                    'data' => $departmentsData,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Quantity of Classes',
                    'data' => $classesData,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}