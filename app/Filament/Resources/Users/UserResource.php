<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Pages\ViewUsers;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; 
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

// CORRECT FILAMENT SCHEMAS FIELDS IMPORTS 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

// CORRECT FILAMENT TABLES COLUMNS & FILTERS IMPORTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

// CORRECT FILAMENT ACTIONS IMPORTS
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static \UnitEnum|string|null $navigationGroup = 'Users Role Management';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Create User account with specific role and language preference')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->password()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
                    Select::make('role')
                        ->options([
                            'admin' => 'Admin',
                            'faculty_manager' => 'Faculty Manager',
                            'study_office' => 'School Study Office',
                            'teacher' => 'Teacher',
                            'student' => 'Student',
                        ])->required(),
                    Select::make('lang_preference')
                        ->options([
                            'en' => 'English', 
                            'kh' => 'Khmer'
                        ])
                        ->default('en')
                        ->required(),
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
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->colors([
                        'primary' => 'admin',
                        'warning' => 'faculty_manager',
                        'secondary' => 'study_office',
                        'success' => 'teacher',
                        'info' => 'student',
                    ]),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Phnom_Penh')
                    ->sortable()
                    ->dateTime('M d Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'faculty_manager' => 'Faculty Manager',
                        'study_office' => 'School Study Office',
                        'teacher' => 'Teacher',
                        'student' => 'Student'
                    ])
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}