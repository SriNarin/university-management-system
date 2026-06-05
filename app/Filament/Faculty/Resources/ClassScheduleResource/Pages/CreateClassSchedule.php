<?php

namespace App\Filament\Faculty\Resources\ClassScheduleResource\Pages;

use App\Filament\Faculty\Resources\ClassScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassSchedule extends CreateRecord
{
    protected static string $resource = ClassScheduleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}