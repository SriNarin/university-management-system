<?php

namespace App\Filament\Faculty\Resources\SchoolClassResource\Pages;

use App\Filament\Faculty\Resources\SchoolClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolClass extends EditRecord
{
    protected static string $resource = SchoolClassResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(), // Allows faculty managers to delete their scoped classes from the edit page header
        ];
    }

    
    
}