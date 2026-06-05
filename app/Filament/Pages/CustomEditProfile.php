<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\User;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Support\Icons\Heroicon;



class CustomEditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.custom-edit-profile';
    
    // Customization for menu display
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $title = 'Account Profile Settings';
    protected static ?string $slug = 'profile-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->form->fill([
                'name'  => $user->name,
                'email' => $user->email,
            ]);
        }
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('👤 Core Profile Identity')
                    ->description('Manage your university account name and authenticated email address.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make('🔒 Security Access Key')
                    ->description('Provide your current password to authorize a change to a new account password.')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('current_password')
                                ->label('Current Password')
                                ->password()
                                ->revealable()
                                ->placeholder('Required only if changing password'),

                            TextInput::make('new_password')
                                ->label('New Password Block')
                                ->password()
                                ->revealable()
                                ->minLength(8)
                                ->placeholder('Minimum 8 characters long'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function saveProfile(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) return;

        $formData = $this->form->getState();

        // Security check for password updates
        if (!empty($formData['current_password']) || !empty($formData['new_password'])) {
            if (!Hash::check($formData['current_password'], $user->password)) {
                Notification::make()
                    ->title('Authentication Failed')
                    ->body('The provided current password does not match our records.')
                    ->danger()
                    ->send();
                return;
            }

            if (empty($formData['new_password'])) {
                Notification::make()
                    ->title('Password Change Incomplete')
                    ->body('Please supply your new strong security password.')
                    ->warning()
                    ->send();
                return;
            }

            $user->password = Hash::make($formData['new_password']);
        }

        $user->name = $formData['name'];
        $user->email = $formData['email'];
        $user->save();

        Notification::make()
            ->title('Profile Meta Records Saved')
            ->body('Your security matrix has updated successfully across database structures.')
            ->success()
            ->send();

        // Clear secret values from the form inputs
        $this->form->fill([
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }
}