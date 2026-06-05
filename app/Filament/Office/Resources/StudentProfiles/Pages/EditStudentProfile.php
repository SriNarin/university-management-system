<?php

namespace App\Filament\Office\Resources\StudentProfiles\Pages;

use App\Filament\Office\Resources\StudentProfiles\StudentProfilesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentProfiles extends EditRecord
{
    protected static string $resource = StudentProfilesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}