<?php

namespace App\Filament\Teacher\Resources;

use App\Models\ClassSchedule;
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
    protected static ?string $model = ClassSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'My Teaching Schedule';
    
    protected static ?string $pluralModelLabel = 'Teaching Schedules';

    protected static ?string $slug = 'my-teaching-schedule';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::check()) {
                    $currentTeacherId = (int) Auth::user()->id;

                    // Query schedule rows matching this teacher where parent class timetable is published
                    $query->where('teacher_id', $currentTeacherId)
                          ->whereHas('schoolClass', function (Builder $subQuery) {
                              $subQuery->where('is_timetable_published', true);
                          });
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->columns([
                 TextColumn::make('schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('danger'),
                
                TextColumn::make('schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('info'),

                TextColumn::make('schoolClass.class_code')
                    ->label('Class')
                    ->badge()
                    ->weight('bold')
                    ->color('success'),

                // 📚 SUBJECT DETAILS
                TextColumn::make('subject_name_en')
                    ->label('Subject')
                    ->weight('bold')
                    ->description(fn (ClassSchedule $record): string => "Code: {$record->subject_code}"),

                // 📅 DAY OF WEEK
                TextColumn::make('day_of_week')
                    ->label('Day')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color('danger')
                    ->weight('bold'),

                // ⏰ TIME SLOT DURATION
                TextColumn::make('start_time')
                    ->label('Teaching Time')
                    ->formatStateUsing(fn ($record): string => 
                        date('h:i A', strtotime($record->start_time)) . ' - ' . date('h:i A', strtotime($record->end_time))
                    )
                    ->color('info')
                    ->weight('bold'),
                
                TextColumn::make('schoolClass.shift')
                    ->label('Shift')
                    ->badge()
                    ->weight('bold')
                    ->color(fn (string $state): string => match ($state) {
                        'morning' => 'warning',
                        'afternoon' => 'info',
                        'evening' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('schoolClass.room_number')
                    ->label('Room')
                    ->badge()
                    ->weight('bold')
                    ->color('info'),

                // Tables\Columns\ViewColumn::make('id')
                //     ->label('Print Sheet')
                //     ->view('filament.tables.columns.teacher-schedule-print-action')
                //     ->alignCenter(),
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