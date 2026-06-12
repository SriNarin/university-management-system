<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminPieChart extends ChartWidget
{
    protected ?string $heading = '📊 System User Roles Distribution';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Query database to aggregate quantities of each user role type
        $roleMetrics = DB::table('users')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        // Map database records cleanly, defaulting to 0 if a specific role has no users yet
        $adminCount          = $roleMetrics['admin'] ?? 0;
        $facultyManagerCount = $roleMetrics['faculty_manager'] ?? 0;
        $studyOfficeCount    = $roleMetrics['study_office'] ?? 0;
        $teacherCount        = $roleMetrics['teacher'] ?? 0;
        $studentCount        = $roleMetrics['student'] ?? 0;

        return [
            'datasets' => [
                [
                    'label' => 'Users Count',
                    'data' => [
                        $adminCount,
                        $facultyManagerCount,
                        $studyOfficeCount,
                        $teacherCount,
                        $studentCount
                    ],
                    'backgroundColor' => [
                        '#EF4444', // Admin - Red
                        '#EC4899', // Faculty Manager - Pink
                        '#3B82F6', // Study Office - Blue
                        '#F59E0B', // Teacher - Amber
                        '#10B981', // Student - Emerald Green
                    ],
                ],
            ],
            'labels' => [
                'Admins ', 
                'Faculty Managers ', 
                'Study Office Staff ', 
                'Teachers ', 
                'Students '
            ],
        ];
    }
}