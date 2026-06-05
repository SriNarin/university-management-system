<?php

namespace App\Filament\Teacher\Resources\LessonMaterialResource\Pages;

use App\Filament\Teacher\Resources\LessonMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLessonMaterial extends CreateRecord
{
    protected static string $resource = LessonMaterialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}