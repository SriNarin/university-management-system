<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// 🌟 FIXED: Class name changed to match the filename exactly
class AdminPieChart extends ChartWidget
{
    protected ?string $heading = '📊 Overall System Statistics';
    protected int | string | array $columnSpan = 'half';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        // 🌟 FIXED: Changed to guard this chart so only admins see it
        return Auth::check() && Auth::user()->role === 'admin';
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Your logic for fetching admin metrics...
        return [
            'datasets' => [
                [
                    'label' => 'Total Breakdown',
                    'data' => [0, 10, 20], // Replace with your logic counters
                    'backgroundColor' => ['#ec4899', '#8b5cf6', '#14b8a6'],
                ],
            ],
            'labels' => ['Admins', 'Teachers', 'Students'],
        ];
    }
}