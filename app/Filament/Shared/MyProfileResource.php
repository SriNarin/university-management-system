<?php

namespace App\Filament\Shared;

use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\schema;
use Filament\Actions\{EditAction, DeleteAction};


class MyProfileResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
     protected static \UnitEnum|string|null $navigationGroup =  'My Profile';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $pluralModelLabel = 'My Profile';
    protected static ?string $modelLabel = 'Profile Settings';
    protected static ?string $slug = 'my-profile';

    // 🔒 CRITICAL SECURITY LAYER: Force each user role to only access their own row record!
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', Auth::id());
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('🏛️ Institutional Account Profile Matrix')
                    ->description('View your account role classification and update your core identity details.')
                    ->schema([
                        Grid::make(3)->schema([
                            FileUpload::make('avatar_url') 
                                ->label('My Profile Picture')
                                ->image()
                                ->avatar()
                                ->directory('profile-photos')
                                ->maxSize(2048)
                                ->columnSpanFull(),

                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            TextInput::make('role')
                                ->label('Assigned System Role')
                                ->disabled() // Prevents privilege escalation
                                ->dehydrated(true)
                                ->formatStateUsing(fn ($state) => str_replace('_', ' ', strtoupper($state))),
                        ]),
                    ]),

                Section::make('🔒 Security Key Management')
                    ->description('To change your password, provide your current password followed by your new 8-character token.')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('current_password')
                                ->label('Current Password')
                                ->password()
                                ->revealable()
                                ->required(fn ($get) => filled($get('password')))
                                ->rule(static function () {
                                    return function ($attribute, $value, $fail) {
                                        if (!Hash::check($value, Auth::user()->password)) {
                                            $fail('The current password provided does not match your account.');
                                        }
                                    };
                                }),

                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->minLength(8)
                                ->dehydrated(fn ($state) => filled($state))
                               ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->placeholder('Leave empty to keep existing password'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('My Profile Picture')
                    ->circular()
                   
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->name))
                    ->extraImgAttributes(['alt' => 'Profile Picture']),

                TextColumn::make('name')
                    ->label('Profile Name')
                    ->fontFamily('sans')
                    ->weight('bold')
                    ->color('danger')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email Identity')
                    ->searchable()
                    ->color('info')
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Role Group')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    

                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'faculty_manager' => 'warning',
                        'study_office' => 'info',
                        'teacher' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', strtoupper($state))),

                IconColumn::make('is_active')
                    ->label('Account Status')
                    ->color('success')
                    ->sortable()
                    ->searchable()
                    
                    ->boolean(),
            ])
            ->actions([
                EditAction::make()
                    ->label('Manage Profile')
                    ->modalHeading('Edit Security Settings')
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
          
            'index' => MyProfileResource\Pages\ManageMyProfile::route('/'),
        ];
    }
}