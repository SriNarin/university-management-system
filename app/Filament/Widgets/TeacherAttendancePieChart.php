<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendancePieChart extends ChartWidget
{
    // 🟢 CORRECT COMBINATION: heading is non-static, sort is static!
    protected ?string $heading = '📊 Overall Attendance Status Distribution';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'half';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'teacher';
    }

    protected function getData(): array
    {
        $teacherId = Auth::id();

        $assignedScheduleIds = DB::table('class_schedules')
            ->where('teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();

        if (empty($assignedScheduleIds)) {
            return [
                'datasets' => [['data' => [0, 0, 0, 0]]],
                'labels' => ['Present', 'Late', 'Absent', 'Permission'],
            ];
        }

        $attendanceCounts = DB::table('attendances')
            ->whereIn('class_schedule_id', $assignedScheduleIds)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $present    = $attendanceCounts['present'] ?? 0;
        $late       = $attendanceCounts['late'] ?? 0;
        $absent     = $attendanceCounts['absent'] ?? 0;
        $permission = $attendanceCounts['permission'] ?? 0;

        return [
            'datasets' => [
                [
                    'label' => 'Attendance Metrics',
                    'data' => [$present, $late, $absent, $permission],
                    'backgroundColor' => [
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#3B82F6',
                    ],
                ]
            ],
            'labels' => ['Present 🟢', 'Late 🟡', 'Absent 🔴', 'Permission 🔵'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}