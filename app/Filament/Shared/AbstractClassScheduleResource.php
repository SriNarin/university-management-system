<?php

namespace App\Filament\Shared;

use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;

// Form Components
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;

// Table Components
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

abstract class AbstractClassScheduleResource extends Resource
{
    protected static ?string $model = ClassSchedule::class;

    protected static ?string $recordTitleAttribute = 'subject_name_en';


    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Subjects & Class Schedule Management')
                ->description('Map out academic classes, subjects, and assigned teachers.')
                ->schema([

                    // 1. SELECT ONE SPECIFIC CLASS DIRECTLY
                    Select::make('school_class_id')
                        ->label('Academic Classes Selection')
                        ->relationship('schoolClass', 'class_code')
                        ->getOptionLabelFromRecordUsing(function ($record) {
                            // Step 1: Get data belonging directly to the SchoolClass record
                            $classCode = $record->class_code;
                            $shift = ucfirst($record->shift ?? 'N/A');         // 🌟 FIXED: Pulling directly from $record
                            $room = $record->room_number ?? 'N/A';             // 🌟 FIXED: Pulling directly from $record

                            // Step 2: Step into connected structure metadata layer for structural details
                            $structure = $record->academicStructure;
                            $deptName = $structure?->department?->name_en ?? 'N/A';
                            $gen = $structure?->generation ?? 'N/A';
                            $level = ucfirst($structure?->academic_level ?? 'N/A');
                            $yearProg = ucfirst($structure?->year_progress ?? 'N/A');

                            // Step 3: Combine them into a single descriptive string options item
                            return "Class: {$deptName} [{$gen} ({$level}) — {$yearProg} — {$classCode}  — {$shift} — Room {$room}]";
                        })
                        ->searchable()
                        ->preload()
                        ->required(),

                    // 2. DYNAMIC SUBJECT SELECTION
                    Select::make('subject_lookup')
                        ->label('Select Subject')
                        ->options(fn () => Subject::all()->pluck('title_en', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($subject = Subject::find($state)) {
                                $set('subject_code', $subject->subject_code);
                                $set('subject_name_en', $subject->title_en);
                            } else {
                                $set('subject_code', null);
                                $set('subject_name_en', null);
                            }
                        })
                        ->afterStateHydrated(function ($state, $set, $record) {
                            if ($record) {
                                $matchedSubject = Subject::where('subject_code', $record->subject_code)->first();
                                $set('subject_lookup', $matchedSubject?->id);
                            }
                        }),

                    // Hidden inputs keeping track of required schema data strings
                    Hidden::make('subject_code')->required(),
                    Hidden::make('subject_name_en')->required(),

                    // 3. SELECT ASSIGNED TEACHER
                    Select::make('teacher_id')
                        ->label('Assigned Teacher')
                        ->options(fn () => User::where('role', 'teacher')->where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columns(3),

            Section::make('Session Timeline Rules')
                ->description('Specify weekly day slots and lecture duration targets.')
                ->schema([
                    Select::make('day_of_week')
                        ->label('Day of Week')
                        ->options([
                            'Monday'    => 'Monday',
                            'Tuesday'   => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday'  => 'Thursday',
                            'Friday'    => 'Friday',
                            'Saturday'  => 'Saturday',
                            'Sunday'    => 'Sunday',
                        ])->required()
                        ->preload(),

                    TimePicker::make('start_time')
                        ->label('Lecture Start Time')
                        ->seconds(false)
                        ->required(),

                    TimePicker::make('end_time')
                        ->label('Lecture End Time')
                        ->seconds(false)
                        ->required(),
                ])->columns(3)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Ref ID')->sortable(),

                // Displays the exact targeted single class code badge (e.g., M1, M2)
                
                TextColumn::make('schoolclass.academicStructure.department.name_en')
                    ->label('Department')
                    ->getStateUsing(fn ($record) => 
                        "{$record->schoolClass?->academicStructure?->department?->name_en} " 
                    )
                    ->searchable()
                    ->color('warning')
                    ->sortable(),

                // Traverses up to display the broader parent structural blueprint data row
                TextColumn::make('schoolClass.academicStructure')
                    ->label('Academic Class')
                    ->getStateUsing(fn ($record) => 
                        "{$record->schoolClass?->academicStructure?->generation} — " . 
                        ucfirst($record->schoolClass?->academicStructure?->academic_level ?? '') . " — " . 
                        ucfirst($record->schoolClass?->academicStructure?->year_progress ?? '')
                    )
                    ->searchable()
                    ->color('info')
                    ->sortable(),  

                TextColumn::make('schoolClass.class_code')
                    ->label('Class Code')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject_code')
                    ->label('Subject Code')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('subject_name_en')
                    ->label('Subject Title')
                    ->searchable()
                    ->sortable()
                    ->color('success'),

                TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->searchable()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('schoolClass.room_number')
                    ->label('Room / Shift')
                    ->getStateUsing(fn ($record) => 
                        "Room: " . ($record->schoolClass?->room_number ?? 'Online') . 
                        " (" . strtoupper($record->schoolClass?->shift ?? 'N/A') . ")"
                    )
                    ->sortable(),

                TextColumn::make('day_of_week')
                    ->label('Day Slot')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Time Slot')
                    ->getStateUsing(fn ($record) => 
                        date('h:i A', strtotime($record->start_time)) . ' - ' . date('h:i A', strtotime($record->end_time))
                    )
                    ->sortable()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
              
            ])
            ->filters([
                SelectFilter::make('day_of_week')
                    ->label('Filter Day')
                    ->options([
                        'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'
                    ]),
            ])
            ->actions([
                ViewAction::make()->color('info'),
                EditAction::make()->color('warning'),
                DeleteAction::make(),
            ]);
    }
}