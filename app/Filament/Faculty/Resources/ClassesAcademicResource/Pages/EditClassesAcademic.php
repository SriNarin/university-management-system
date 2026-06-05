<?php

namespace App\Filament\Faculty\Resources\ClassesAcademicResource\Pages;

use App\Filament\Faculty\Resources\ClassesAcademicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassesAcademic extends EditRecord
{
    protected static string $resource = ClassesAcademicResource::class;

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