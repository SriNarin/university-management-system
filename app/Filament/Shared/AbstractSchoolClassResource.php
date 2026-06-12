<?php

namespace App\Filament\Shared;

use App\Models\SchoolClass;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

// UI COMPONENT IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

abstract class AbstractSchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static ?string $recordTitleAttribute = 'class_code';

    // Form schema if you ever want to quickly edit a class code directly
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Class Section Details')
                ->schema([
                    TextInput::make('class_code')
                        ->label('Class Room Section Code')
                        ->required()
                        ->maxLength(255),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Ref')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                // 1. UNIQUE CLASS CODE
                TextColumn::make('class_code')
                    ->label('Class Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('academicStructure.department.faculty.name_en')
                    ->label('Faculty')
                    ->searchable()
                    ->color('warning')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('academicStructure.department.name_en')
                    ->label('Department')
                    ->searchable()
                    ->color('info')
                    ->weight('bold')
                    ->sortable(),

                // 3. GENERATION
                TextColumn::make('academicStructure.generation')
                    ->label('Generation')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->weight('bold')
                    ->color('warning'),

                // 4. ACADEMIC LEVEL
                TextColumn::make('academicStructure.academic_level')
                    ->label('Degree Level')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->weight('bold')
                    ->color('info'),

                // 5. YEAR PROGRESS STATUS
                TextColumn::make('academicStructure.year_progress')
                    ->label('Year Progress')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('warning'),

                // 6. SHIFT TIMING
                TextColumn::make('shift')
                    ->label('Study Shift')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->badge()
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),

                // 7. BASE ROOM NUMBER
                TextColumn::make('room_number')
                    ->label('Room No.')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->weight('bold')
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->toggleable(isToggledHiddenByDefault: false),
               
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Filter By Department')
                    ->relationship('academicStructure.department', 'name_en')
                    ->searchable(),
                
                SelectFilter::make('class_code')
                    ->label('Filter By Class Code')
                    ->options(fn () => SchoolClass::query()
                        ->select('class_code')
                        ->distinct()
                        ->pluck('class_code', 'class_code')
                        ->toArray()
                    ),

                SelectFilter::make('generation')
                    ->label('Filter By Generation')
                    ->relationship('academicStructure', 'generation')
                    ->searchable()
                     ->options(fn () => SchoolClass::query()
                        ->select('academic_structures.generation')
                        ->join('academic_structures', 'school_classes.academic_structure_id', '=', 'academic_structures.id')
                        ->distinct()
                        ->pluck('generation', 'generation')
                        ->toArray()
                     ),
        
                SelectFilter::make('academic_level')
                    ->label('Filter By Degree Level')
                    ->options([
                        'bachelor' => 'Bachelor',
                        'master' => 'Master',
                        'phd' => 'PhD',
                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'],
                        fn ($q, $value) => $q->whereHas('academicStructure', fn ($sub) => $sub->where('academic_level', $value))
                    )),
                    
                SelectFilter::make('shift')
                    ->label('Filter By Shift')
                    ->options([
                        'morning' => 'Morning',
                        'afternoon' => 'Afternoon',
                        'evening' => 'Evening',
                        'weekend' => 'Weekend',
                        'full_day' => 'Full Day',
                        'online' => 'Online',

                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'],
                        fn ($q, $value) => $q->where('shift', $value) // 🌟 FIXED: Direct column query on local table
                    )),

                SelectFilter::make('year_progress')
                    ->label('Filter By Year Progress')
                    ->options([
                      'foundation' => 'Foundation',
                        'first_year' => 'First Year',
                        'second_year' => 'Second Year',
                        'third_year' => 'Third Year',
                        'fourth_year' => 'Fourth Year',
                        'graduated' => 'Graduated',
                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'],
                        fn ($q, $value) => $q->whereHas('academicStructure', fn ($sub) => $sub->where('year_progress', $value))
                    )),
                
                  
                    
            ])
            ->actions([
                ViewAction::make()->color('info')->icon('heroicon-s-eye')->size('sm'),
                EditAction::make()->color('danger')->icon('heroicon-s-pencil')->size('sm'),
                DeleteAction::make(),
            ]);
}
}