<?php

namespace App\Filament\Faculty\Resources\ClassesAcademicResource\Pages;

use App\Filament\Faculty\Resources\ClassesAcademicResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassesAcademic extends CreateRecord
{
    protected static string $resource = ClassesAcademicResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}