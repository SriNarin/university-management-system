<?php

namespace App\Filament\Resources\CustomActivityLogResource\Pages;

use App\Filament\Resources\CustomActivityLogResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomActivityLog extends EditRecord
{
    protected static string $resource = CustomActivityLogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}