<?php

namespace App\Filament\Office\Resources\StudentProfiles\Pages;

use App\Filament\Office\Resources\StudentProfiles\StudentProfilesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentProfiles extends CreateRecord
{
    protected static string $resource = StudentProfilesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}