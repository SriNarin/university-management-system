<?php

namespace App\Filament\Office\Resources\StudentEnrolls\Pages;

use App\Filament\Office\Resources\StudentEnrolls\StudentEnrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentEnrollments extends ListRecords
{
    protected static string $resource = StudentEnrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Enroll New Student'),
        ];
    }
}