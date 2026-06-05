<?php

namespace App\Filament\Teacher\Resources\Attendances;

use App\Filament\Teacher\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Teacher\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Teacher\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Teacher\Resources\Attendances\Pages\TakeAttendanceSheet;
use App\Filament\Teacher\Resources\Attendances\Pages\ViewAttendance;
use App\Models\Attendance;
use App\Models\SchoolClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ClassSchedule;
use App\Models\Student;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;


class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PresentationChartBar;
    protected static \UnitEnum|string|null $navigationGroup = 'Attendance Management';
    protected static ?string $slug = 'student-attendances Logs';

    // 🌟 SECURITY FILTER: Only load records that belong to classes taught by this logged-in teacher
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('classSchedule', function (Builder $query) {
            $query->where('teacher_id', Auth::id());
        });
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Log Details')
                    ->description('Review or adjust this specific attendance log record.')
                    ->schema([
                        TextInput::make('student_name')
                            ->label('Student Name')
                            ->placeholder('No Name Linked')
                            // Pull the student's name from the user relationship
                            ->formatStateUsing(fn ($record) => $record?->student?->name)
                            ->disabled() // Teachers shouldn't change the student's identity here
                            ->dehydrated(false),

                        DatePicker::make('teaching_date')
                            ->label('Session Teaching Date')
                            ->disabled() // Date should remain locked to the actual teaching session
                            ->required(),

                        TextInput::make('class_info')
                            ->label('Assigned Class & Subject')
                            ->formatStateUsing(fn ($record) => 
                                $record?->classSchedule?->schoolClass?->class_code . ' — ' . $record?->classSchedule?->subject_name_en
                            )
                            ->disabled()
                            ->dehydrated(false),

                        // 🌟 THE EDITABLE FIELD: Allows the teacher to quickly fix mistakes
                        Select::make('status')
                            ->label('Attendance Status')
                            ->options([
                                'present' => '🟢 Present',
                                'absent' => '🔴 Absent',
                                'late' => '🟡 Late',
                                'permission' => '🔵 Permission',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                TextColumn::make('teaching_date')
                    ->label('Attendance Date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                    

                TextColumn::make('classSchedule.schoolClass.academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->sortable()
                    ->searchable()
                    ->color('primary'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.department.name_en')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->color('info'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.academic_level')
                    ->label('Academic Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('warning'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.generation')
                    ->label('Generation')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('danger'),

                TextColumn::make('classSchedule.schoolClass.class_code')
                    ->label('Class Code')
                    ->badge()
                    ->searchable()
                    ->color('success'),
                TextColumn::make('classSchedule.schoolClass.shift')
                    ->label('Shift')
                    ->badge()
                    ->searchable()
                    ->color('info'),
                TextColumn::make('classSchedule.schoolClass.room_number')
                    ->label('Room Number')
                    ->badge()
                    ->searchable()
                    ->color('success'),
                    

                TextColumn::make('classSchedule.subject_name_en')
                    ->label('Subject')
                    ->searchable()
                    ->sortable()
                    ->color('warning'),

                TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->color('info'),
                    
                
                // Inline status selector
                TextColumn::make('status')
                    ->label('Attendance Status')
                    ->color( fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'permission' => 'info',
                    })
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                    
            ])
            ->filters([
                // SelectFilter::make('class_code')
                //     ->label('Filter by Class Code')
                //     ->options(fn () => ClassSchedule::where('teacher_id', Auth::id())
                //         ->get()
                //         ->pluck('schoolClass.class_code', 'id'))
                //     ->searchable()
                //     ->preload()
                //     ->multiple(),
                SelectFilter::make('class_schedule_id')
                    ->label('Filter by Schedule Slot')
                    ->options(fn () => ClassSchedule::where('teacher_id', Auth::id())
                        ->get()
                        ->pluck('subject_name_en', 'id'))
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('teaching_date')
                    ->label('Filter by Teaching Date')
                    ->options(fn () => Attendance::distinct('teaching_date')->pluck('teaching_date', 'teaching_date'))
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'permission' => 'Permission',
                    ])->searchable()
                        ->preload()
                        ->multiple(),
                
                    
            ])
           
            ->defaultSort('teaching_date', 'desc')
            ->actions([
                ViewAction::make()->color('gray')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('info')->icon('heroicon-s-pencil')->size('sm'),
                DeleteAction::make()->color('danger')->icon('heroicon-s-trash')->size('sm'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array { return []; }
    public static function getPages(): array {
        return [
            'index' => ListAttendances::route('/'),
            'create' =>TakeAttendanceSheet::route('/take-sheet'),
            'edit' => EditAttendance::route('/{record}/edit'),
            'view' => ViewAttendance::route('/{record}'),
            
        ];
    }
}