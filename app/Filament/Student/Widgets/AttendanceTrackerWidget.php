<?php

namespace App\Filament\Student\Widgets;

use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class AttendanceTrackerWidget extends BaseWidget
{
    protected static ?string $heading = 'Historical Session Attendance Tracker';

    protected int | string | array $columnSpan = 'half';

    public static function canView(): bool
    {
        // Disables the widget from showing up automatically on the main Dashboard view
        if (Route::is('filament.student.pages.dashboard')) {
            return false;
        }

        return true;
    }
    public static function shouldRegister(): bool
    {
        // Prevents this card from bleeding into the Task Assessments dashboard view!
        return Request::is('*enrolled-class-hub*');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('student_id', Auth::id())
                    ->latest()
            )
            ->columns([
                TextColumn::make('classSchedule.session_date')
                    ->label('Session Learning Date')
                    ->date('M d, Y')
                    ->default(fn($record) => $record->created_at?->format('M d, Y') ?? 'N/A')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Tracked Metrics Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->colors([
                        'success' => 'present',
                        'warning' => fn ($state) => in_array(strtolower($state), ['permission', 'excused']),
                        'danger' => 'absent',
                    ]),
            ]);
    }
}