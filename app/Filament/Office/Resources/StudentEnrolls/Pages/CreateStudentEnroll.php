<?php

namespace App\Filament\Office\Resources\StudentEnrolls\Pages;

use App\Filament\Office\Resources\StudentEnrolls\StudentEnrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentEnrollment extends CreateRecord
{
    protected static string $resource = StudentEnrollResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}