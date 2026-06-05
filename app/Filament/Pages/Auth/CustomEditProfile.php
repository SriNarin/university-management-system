<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Schemas\schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CustomEditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    // Points to the blade view template file
    protected string $view = 'filament.pages.auth.custom-edit-profile';

    // Keeps this page hidden from your left sidebar menu navigation
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'My Profile Account';
    protected static ?string $slug = 'user-profile-settings';

    public ?array $data = [];

    // 🌟 Static methods that stop Filament from throwing "getLabel does not exist"
    public static function getLabel(): string
    {
        return 'My Profile';
    }

    public static function getNavigationLabel(): string
    {
        return 'My Profile';
    }

    public function mount(): void
    {
        // Safely pull the active user row into the form
          $user = Auth::user();
          if ($user && method_exists($user, 'toArray')) {
              $this->form->fill($user->toArray());
          }
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Personal & Account Information')
                    ->description('Manage your profile credentials visible across the university system.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email', ignoreRecord: true),
                    ])->columns(2),

                Section::make('System Credentials & Security')
                    ->description('Leave the password fields blank if you do not want to alter your password.')
                    ->schema([
                        TextInput::make('role')
                            ->label('Assigned System Role')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state)))
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-zinc-800 opacity-80']),

                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state) => filled($state)),

                        TextInput::make('passwordConfirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->required(fn ($get) => filled($get('password')))
                            ->same('password')
                            ->dehydrated(false),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Profile Changes')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $user = Auth::user();

        $updateData = [
            'name'  => $state['name'],
            'email' => $state['email'],
        ];

        if (!empty($state['password'])) {
            $updateData['password'] = Hash::make($state['password']);
        }

       if ($user instanceof \Illuminate\Database\Eloquent\Model) {
          $user->update($updateData);
      } else {
          // Handle the case when the $user object is not an instance of the expected class
      }

        Notification::make()
            ->title('Profile Updated Successfully!')
            ->success()
            ->send();
    }
}