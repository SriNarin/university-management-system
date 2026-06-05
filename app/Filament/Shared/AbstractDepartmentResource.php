<?php

namespace App\Filament\Shared;

use App\Models\Department;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

// FORM SCHEMA BUILDER IMPORTS
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater; // <-- FIXED: Changed from HasManyRepeater to standard Repeater

// TABLE LAYOUT IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

abstract class AbstractDepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $recordTitleAttribute = 'name_en';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Department & Classes Management')
                ->description('Define the department details and manage its associated academic structures and class schedules.')
                ->schema([
                    Select::make('faculty_id')
                        ->label('Assigned  Faculty')
                        ->relationship('faculty', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required()
                        // AUTOMATIC FILL: Fetches the managed faculty ID automatically
                        ->default(function () {
                            if (Auth::check() && strtolower(Auth::user()->role) === 'faculty_manager') {
                                return \App\Models\Faculty::where('manager_id', Auth::id())->first()?->id;
                            }
                            return null;
                        })
                        // REPLACEMENT RULES FOR MANAGERS:
                        // If they are a faculty manager, lock the input field so they can't change it, 
                        // but tell Filament to explicitly include it in the SQL save query payload!
                        ->disabled(fn () => Auth::check() && strtolower(Auth::user()->role) === 'faculty_manager')
                        ->dehydrated(true),
                    
                    

                    TextInput::make('name_en')
                        ->label('Department Title (English)')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('name_kh')
                        ->label('Department Title (Khmer)')
                        ->nullable()
                        ->maxLength(255),
                ])->columns(2),


            Section::make('Academic Structure & Classes Management')
                ->description('Manage active classroom shifts, generations, levels, and sections inside this department.')
                ->schema([
                    // FIXED: Changed from HasManyRepeater to Repeater
                    Repeater::make('academicStructures')
                        ->relationship('academicStructures')
                        ->label('Academic Classes Structures ')
                        ->collapsible()
                        ->cloneable()
                        ->defaultItems(0)
                        ->schema([
                            TextInput::make('generation')
                                ->label('Generation (e.g., Gen 25)')
                                ->required(),

                            Select::make('academic_level')
                                ->label('Degree Level')
                                ->options([
                                    'bachelor' => 'Bachelor Degree',
                                    'master' => 'Master Program',
                                    'phd' => 'Doctoral (PhD)',
                                ])->required()
                                ->preload(),

                            Select::make('year_progress')
                                ->label('Current Year Standing')
                                ->options([
                                    'foundation' => 'Foundation Year',
                                    'year_1' => 'Year 1',
                                    'year_2' => 'Year 2',
                                    'year_3' => 'Year 3',
                                    'year_4' => 'Year 4',
                                    'graduated' => 'Alumni / Graduated',
                                ])->required()
                                ->preload(),

                            

                            // FIXED: Changed nested element from HasManyRepeater to Repeater
                            Repeater::make('schoolClasses')
                                ->relationship('schoolClasses')
                                ->label('Assigned Group Classes Section ')
                                ->schema([
                                    Select::make('semester')
                                        ->label('Term Semester')
                                        ->options([
                                            'semester_1' => 'Semester 1',
                                            'semester_2' => 'Semester 2',
                                            'summer' => 'Summer Term',
                                            'winter' => 'Winter Term',
                                            'autumn' => 'Autumn Term',
                                            'spring' => 'Spring Term',
                                            'fall' => 'Fall Term',
                                            'other' => 'Other Term',
                                        ])->required()
                                        ->preload(),

                                   
                                    Select::make('shift')
                                        ->label('Study Shift Timing')
                                        ->options([
                                            'morning' => 'Morning Session',
                                            'afternoon' => 'Afternoon Session',
                                            'evening' => 'Evening Session',
                                            'weekend' => 'Weekend Track',
                                            'full_day' => 'Full Day Program',
                                            'online' => 'Online Program',
                                            'other' => 'Other Shift',

                                        ])->required()
                                        ->preload()
                                        ->live(),
                                    TextInput::make('class_code')
                                        ->label('Class Room Section Code ')
                                        ->placeholder('e.g., M1, E2, WE1')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('room_number')
                                        ->label('Room Number')
                                        ->nullable()
                                        ->placeholder('e.g., 303, 505')
            
                                        // Dynamic Requirement Rule: Required ONLY if the shift is NOT set to 'online'
                                        ->required(fn ($get) => $get('shift') !== 'online')
                                        
                                        // Dynamic Visibility Rule: Hide the field completely if 'online' is selected
                                        ->hidden(fn ($get) => $get('shift') === 'online')
                                        
                                        // Optional Safety: Clear out any typed value if the user changes shift to online mid-edit
                                        ->live(),    // No unique constraints here
                                        ])->columns(1)->defaultItems(1)

                                        
                                ])->columns(2)
                        ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => 
                $query->withCount('schoolClasses')
            )

            ->columns([
                TextColumn::make('id')
                    ->label('ID Ref')
                    ->sortable(),

                TextColumn::make('faculty.name_en')
                    ->label('Faculty')
                    ->searchable()
                    ->sortable(),
                   

                TextColumn::make('name_en')
                    ->label('Department (EN)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name_kh')
                    ->label('Department (KH)')
                    ->searchable(),

                TextColumn::make('school_classes_count')
                    ->label('Actual Classes Count')
                    ->counts('schoolClasses')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                
                

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('faculty_id')
                    ->label('Filter By Faculty Body')
                    ->relationship('faculty', 'name_en')
                    ->searchable(),
                    
            ])
            ->actions([
                ViewAction::make()->color('gray'),
                EditAction::make()->color('info'),
                DeleteAction::make(),
            ]);
    }
}