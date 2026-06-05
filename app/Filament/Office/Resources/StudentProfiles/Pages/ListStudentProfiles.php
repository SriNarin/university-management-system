<?php

namespace App\Filament\Office\Resources\StudentProfiles\Pages;

use App\Filament\Office\Resources\StudentProfiles\StudentProfilesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentProfiles extends ListRecords
{
    protected static string $resource = StudentProfilesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Student Profile'),
        ];
    }
}