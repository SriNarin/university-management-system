<?php

namespace App\Filament\Shared\MyProfileResource\Pages;

use App\Filament\Shared\MyProfileResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageMyProfile extends ManageRecords
{
    protected static string $resource = MyProfileResource::class;

    protected function getHeaderActions(): array
    {
        return []; // No top-level "Create New User" buttons allowed for individual profiles
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Profile Synchronized')
            ->body('Your core database access entries have been safely updated.')
            ->success()
            ->send();
    }
}