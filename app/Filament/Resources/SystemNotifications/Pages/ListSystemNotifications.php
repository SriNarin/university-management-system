<?php

namespace App\Filament\Resources\SystemNotificationResource\Pages;

use App\Filament\Resources\SystemNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSystemNotifications extends ListRecords
{
    protected static string $resource = SystemNotificationResource::class;

    /**
     * Header actions for the datatable index view.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Compose Message')
                ->icon('heroicon-o-paper-airplane'),
        ];
    }
}