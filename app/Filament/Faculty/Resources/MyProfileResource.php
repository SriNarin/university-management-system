<?php

namespace App\Filament\Faculty\Resources;
use App\Filament\Shared\MyProfileResource\Pages\ManageMyProfile;
use App\Filament\Shared\MyProfileResource as SharedMyProfileResource;


use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
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

    protected static \UnitEnum|string|null $navigationGroup =  'My Profile Management';

    protected static ?string $pluralModelLabel = 'My Profile ';

    protected static ?string $modelLabel = 'My Profile Settings';

    protected static ?string $slug = 'my-profile';

    /**
     * 🔒 CRITICAL SECURITY LAYER
     * Scopes the resource down so that an Admin can only see and interact 
     * with their own database record row.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', Auth::id());
    }

    /**
     * Form configuration mapping the user data fields cleanly.
     */
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('🏛️ Institutional Account Profile Matrix')
                    ->description('View your account role classification and update your core identity details.')
                    ->schema([
                        Grid::make(3)->schema([
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
                                ->disabled() // Protects structural integrity from accidental role changes
                                ->dehydrated(false)
                                ->formatStateUsing(fn ($state) => str_replace('_', ' ', strtoupper($state))),
                        ]),
                    ]),

                Section::make('🔒 Security Key Management')
                    ->description('To update your password, provide your current password followed by your new 8-character verification token.')
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
                                ->hashStateUsing(fn ($state) => Hash::make($state))
                                ->placeholder('Leave empty to keep existing password'),
                        ]),
                    ]),
            ]);
    }

    /**
     * Table configuration displaying your active administrator credentials profile.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Profile Name')
                    ->fontFamily('sans')
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email Identity')
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Role Group')
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', strtoupper($state))),

                IconColumn::make('is_active')
                    ->label('Account Status')
                    ->boolean(),
            ])
            ->actions([
                  EditAction::make()
                    ->label('Manage Profile')
                    ->modalHeading('Edit Security Settings')
                    ->icon('heroicon-m-pencil-square')
                    ->after(function () {
                        Notification::make()
                            ->title('Profile Synchronized')
                            ->body('Your core account records have been safely updated.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    /**
     * Maps the routing pattern natively using an inline list page pattern 
     * to prevent extra file clutter.
     */
    public static function getPages(): array
    {
        return [
            'index' => ManageMyProfile::route('/'),
        ];
    }
}

/**
 * Native page class subclass declared directly in-line to ensure perfect view initialization
 * and eliminate missing file runtime issues completely.
 */
namespace App\Filament\Shared\MyProfileResource\Pages\MyProfileResource;

use App\Filament\Faculty\Resources\MyProfileResource;
use App\Filament\Shared\MyProfileResource as SharedMyProfileResource;

class ManageFacultyProfile extends SharedMyProfileResource
{
    protected static string $resource = MyProfileResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Hides "Create" buttons so users cannot create duplicate profile rows
    }
}