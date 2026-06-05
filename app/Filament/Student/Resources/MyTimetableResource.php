<?php

namespace App\Filament\Student\Resources;

use App\Models\SchoolClass;
use App\Models\ClassSchedule;
use App\Models\ClassUser;
use App\Models\AcademicStructure;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Tables\Columns\TextColumn;

class MyTimetableResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static string|BackedEnum|null $navigationIcon =  'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Official Classes Timetable';
    
    protected static ?string $pluralModelLabel = 'Official Class Timetables';

    protected static ?string $slug = 'my-classes-timetable';

    public static function table(Table $table): Table
    {
        return $table
            // 🔒 FIXED QUERY SCOPE: Looks through the class_user relation mapping for the logged-in student ID
            ->modifyQueryUsing(function (Builder $query) {
                $studentId = Auth::user()->id;

                $query->whereHas('users', function ($userQuery) use ($studentId) {
                    $userQuery->where('users.id', $studentId);
                });
            })
            ->columns([
                TextColumn::make('academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->color('danger'),
                
                TextColumn::make('academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->color('secondary'),
                
                TextColumn::make('academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('info'),
                
                TextColumn::make('academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('success'),
                
                TextColumn::make('academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('class_code')
                    ->label('Class Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('shift')
                    ->label('Shift')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'morning' => 'warning',
                        'afternoon' => 'info',
                        'evening' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->toggleable(),

                // 🖨️ THE MATCHING DOWNLOAD CUSTOM VIEW CONTAINER ACTION ROW
                Tables\Columns\ViewColumn::make('download_timetable_certificate')
                    ->label('Official Timetable Certificate')
                    ->view('filament.tables.columns.student-timetable-download-action')
                    ->alignCenter(),
            ])
            ->actions([])
            ->bulkActions([])
            ->recordUrl(null); // Turn off row clicks to keep page stable
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Student\Resources\MyTimetableResource\Pages\ListMyTimetables::route('/'),
        ];
    }
}