<?php

namespace App\Filament\Resources\SystemNotificationResource\Pages;

use App\Filament\Resources\SystemNotificationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemNotification extends ViewRecord
{
    protected static string $resource = SystemNotificationResource::class;

    /**
     * Top-right actions on the view/read record screen.
     */
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Modify Message'),
        ];
    }
}