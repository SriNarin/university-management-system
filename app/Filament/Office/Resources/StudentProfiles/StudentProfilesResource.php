<?php

namespace App\Filament\Office\Resources\StudentProfiles;

use App\Models\StudentProfile;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class StudentProfilesResource extends Resource
{

    protected static ?string $model = StudentProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;
    protected static \UnitEnum|string|null $navigationGroup ='Student Management';
    protected static ?string $pluralModelLabel = 'Student Profiles';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Students Personal Information')
                ->schema([
                    Select::make('user_id')
                        ->label('Student Account')
                        ->options(User::where('role', 'student')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabledOn('edit'),

                    TextInput::make('student_id_card')
                        ->label('student ID Card ')
                        ->placeholder('e.g., ID-2026001')
                        ->unique(ignoreRecord: true)
                        ->required(),

                    TextInput::make('phone_number')
                        ->label('Contact Phone Number')
                        ->tel()
                        ->required(),

                    DatePicker::make('date_of_birth')
                        ->label('Date of Birth')
                        ->required(),

                    TextInput::make('age')
                        ->label('Age')
                        ->numeric()
                        ->required(),

                    Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other'
                        ])->required()
                        ->preload(),
                        

                    TextInput::make('current_address')
                        ->label('Student Current Address')
                        ->columnSpanFull()
                        ->required(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                TextColumn::make('student_id_card')
                    ->label('Student ID Card')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date(),
                TextColumn::make('age')
                    ->label('Age'),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge(),
                TextColumn::make('phone_number')
                    ->label('Phone Number'),
                TextColumn::make('current_address')
                    ->label('Current Address')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->dateTime('M d Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
        ])
         
        ->filters([
            selectFilter::make('name')
                ->label('Filter by Student Name')
                ->options(User::where('role', 'student')->pluck('name', 'id'))
                ->searchable()
                ->preload(),
            selectFilter::make('gender')
                ->label('Filter by Gender')
                ->options([ 
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other'
                ])->searchable()
                ->preload(),
            selectFilter::make('academic_level')
                ->label('Filter by Academic Level')
                ->options([
                    'bachelor' => 'Bachelor Degree',
                    'master' => 'Master Degree',
                    'phd' => 'phD Degree',
                ])
                ->searchable()
                ->preload(),
            selectFilter::make('year_progress')
                ->label('Filter by Year Progress')
                ->options([
                    'first_year' => 'First Year',
                    'second_year' => 'Second Year',
                    'third_year' => 'Third Year',
                    'fourth_year' => 'Fourth Year',
                ])
                ->searchable()
                ->preload(),
            selectFilter::make('department')
                ->label('Filter by Department')
                ->options(fn () => User::where('role', 'student')->where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->preload(),
            
            selectFilter::make('class_code')
                ->label('Filter by Class Code')
                ->options(fn () => User::where('role', 'student')->where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->preload(),
            
        ])
        
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
            'index' => Pages\ListStudentProfiles::route('/'),
            'create' => Pages\CreateStudentProfiles::route('/create'),
            'view' => Pages\ViewStudentProfiles::route('/{record}'),
            'edit' => Pages\EditStudentProfiles::route('/{record}/edit'),

        ];
    }
}