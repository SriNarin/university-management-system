<?php

namespace App\Filament\Office\Resources\StudentEnrolls\Pages;

use App\Filament\Office\Resources\StudentEnrolls\StudentEnrollResource;
use App\Filament\Office\Resources\StudentEnrolls\Schemas\StudentEnrollForm;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentEnrollment extends EditRecord
{
    protected static string $resource = StudentEnrollResource::class;

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