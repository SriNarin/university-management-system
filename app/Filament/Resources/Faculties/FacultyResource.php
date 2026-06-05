<?php

namespace App\Filament\Resources\Faculties;

use App\Filament\Resources\Faculties\Pages\CreateFaculty;
use App\Filament\Resources\Faculties\Pages\EditFaculty;
use App\Filament\Resources\Faculties\Pages\ListFaculties;
use App\Filament\Resources\Faculties\Pages\ViewFaculty;

use App\Models\Faculty;
use App\Models\User; // <-- IMPORTED USER MODEL FOR INTERCEPTING ACCOUNT DATA
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; 
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

// CORRECT FILAMENT SCHEMAS FIELDS IMPORTS 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select; // <-- IMPORTED SELECT FOR DROPDOWN OPTIONS
use Filament\Forms\Components\Toggle;

// CORRECT FILAMENT TABLES COLUMNS & FILTERS IMPORUS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

// CORRECT FILAMENT ACTIONS IMPORTS
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static \UnitEnum|string|null $navigationGroup = 'Faculty Management';


    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Create new Faculty')
                ->schema([
                    TextInput::make('name_en')
                        ->required()
                        ->maxLength(255)
                        ->label('Faculty Name (English)'),
                    TextInput::make('name_kh')
                        ->required()
                        ->maxLength(255)
                        ->label('Faculty Name (Khmer)'),
                    // TextInput::make('code')
                    //     ->nullable()
                    //     ->unique(ignoreRecord: true)
                    //     ->maxLength(10)
                    //     ->label('Faculty Code (e.g., FE, FSS)'),
                        
                    // NEW DROPDOWN: Shows only the names of created Users who have the 'faculty_manager' role
                    Select::make('manager_id')
                        ->label('Assigned Faculty Manager')
                        ->options(function () {
                            return User::where('role', 'faculty_manager')
                                ->where('is_active', true)
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->required()
                        ->preload(),

                    Toggle::make('is_active')
                        ->default(true)
                        ->label('is Active'),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('code')->sortable()->searchable(),
                TextColumn::make('name_en')->searchable()->label('Name (EN)'),
                TextColumn::make('name_kh')->label('Name (KH)'),
                
                // Displays the name of the linked Faculty Manager on your data table grid
                TextColumn::make('users.name')
                    ->label('Faculty Manager')
                    ->getStateUsing(function ($record) {
                            // Explicitly fetches the user's name from the DB using the manager_id column value
                            return \App\Models\User::find($record->manager_id)?->name ?? 'No Manager Assigned';
                        })
                    ->searchable()
                    ->sortable(),
                   

                IconColumn::make('is_active')
                    ->label('Action Status')
                    ->boolean() // Tells Filament to look for true/false values
                    ->trueIcon('heroicon-o-check-circle')  // Green check icon when true
                    ->falseIcon('heroicon-o-x-circle')     // Red X icon when false
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->sortable()
                    ->dateTime('M d Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index' => ListFaculties::route('/'),
            'create' => CreateFaculty::route('/create'),
            'view' => ViewFaculty::route('/{record}'),
            'edit' => EditFaculty::route('/{record}/edit'),
        ];
    }
}