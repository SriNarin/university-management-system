<?php

namespace App\Filament\Resources\CustomActivityLogResource\Pages;

use App\Filament\Resources\CustomActivityLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomActivityLog extends CreateRecord
{
    protected static string $resource = CustomActivityLogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}