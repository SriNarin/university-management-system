<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceBarChart extends ChartWidget
{
    // 🟢 CORRECT COMBINATION: heading is non-static, sort is static!
    protected ?string $heading = '🏫 Attendance Matrix by Assigned Classes';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'half';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'teacher';
    }

    protected function getData(): array
    {
        $teacherId = Auth::id();

        $schedules = DB::table('class_schedules')
            ->join('school_classes', 'class_schedules.school_class_id', '=', 'school_classes.id')
            ->where('class_schedules.teacher_id', $teacherId)
            ->select('class_schedules.id as schedule_id', 'school_classes.class_code')
            ->get();

        if ($schedules->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $scheduleIds = $schedules->pluck('schedule_id')->toArray();
        $classCodes = $schedules->pluck('class_code')->unique()->values()->toArray();

        $datasetTemplate = array_fill_keys($classCodes, 0);
        
        $presentData = $datasetTemplate;
        $lateData = $datasetTemplate;
        $absentData = $datasetTemplate;
        $permissionData = $datasetTemplate;

        $rawAttendance = DB::table('attendances')
            ->join('class_schedules', 'attendances.class_schedule_id', '=', 'class_schedules.id')
            ->join('school_classes', 'class_schedules.school_class_id', '=', 'school_classes.id')
            ->whereIn('attendances.class_schedule_id', $scheduleIds)
            ->select('school_classes.class_code', 'attendances.status', DB::raw('count(*) as count'))
            ->groupBy('school_classes.class_code', 'attendances.status')
            ->get();

        foreach ($rawAttendance as $record) {
            $code = $record->class_code;
            $status = $record->status;
            $count = $record->count;

            if ($status === 'present') $presentData[$code] = $count;
            if ($status === 'late') $lateData[$code] = $count;
            if ($status === 'absent') $absentData[$code] = $count;
            if ($status === 'permission') $permissionData[$code] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => array_values($presentData),
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Late',
                    'data' => array_values($lateData),
                    'backgroundColor' => '#F59E0B',
                ],
                [
                    'label' => 'Absent',
                    'data' => array_values($absentData),
                    'backgroundColor' => '#EF4444',
                ],
                [
                    'label' => 'Permission',
                    'data' => array_values($permissionData),
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $classCodes,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}