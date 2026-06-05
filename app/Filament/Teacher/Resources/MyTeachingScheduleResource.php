<?php

namespace App\Filament\Teacher\Resources;

use App\Models\SchoolClass;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class MyTeachingScheduleResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'My Teaching Schedule';
    
    protected static ?string $pluralModelLabel = 'Teaching Schedules';

    protected static ?string $slug = 'my-teaching-schedule';

    public static function table(Table $table): Table
    {
        return $table
            // 🔒 SCOPE: Ensure precise data type parsing for the logged-in Teacher accounts
            ->modifyQueryUsing(function (Builder $query) {
                // Check if there is an authenticated user session present
                if (Auth::check()) {
                    $currentTeacherId = (int) Auth::user()->id;

                    $query->where('teacher_id', $currentTeacherId)
                          ->where('is_teacher_timetable_published', true);
                } else {
                    // Fallback protection if session fails to load
                    $query->whereRaw('1 = 0');
                }
            })
            ->columns([
                TextColumn::make('academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->color('danger'),
                
                TextColumn::make('academicStructure.department.name_en')
                    ->label('Department')
                    ->color('secondary'),
                
                TextColumn::make('academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color('info'),

                TextColumn::make('class_code')
                    ->label('Class Code')
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('shift')
                    ->label('Shift')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'morning' => 'warning',
                        'afternoon' => 'info',
                        'evening' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('room_number')
                    ->label('Room Number'),

                // Optional print button column layout container reference mapping
                Tables\Columns\ViewColumn::make('id')
                    ->label('Print Session Sheet')
                    ->view('filament.tables.columns.teacher-schedule-print-action')
                    ->alignCenter(),
            ])
            ->actions([])
            ->bulkActions([])
            ->recordUrl(null);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Teacher\Resources\MyTeachingScheduleResource\Pages\ListMyTeachingSchedules::route('/'),

        ];
    }
}