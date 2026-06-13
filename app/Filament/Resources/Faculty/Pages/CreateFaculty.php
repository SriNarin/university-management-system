<?php

namespace App\Filament\Resources\Faculty\Pages;

use App\Filament\Resources\Faculty\FacultyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaculty extends CreateRecord
{
    protected static string $resource = FacultyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

