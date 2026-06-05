<?php

namespace App\Filament\Faculty\Resources\ClassScheduleResource\Pages;

use App\Filament\Faculty\Resources\ClassScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassSchedules extends ListRecords
{
    protected static string $resource = ClassScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->color('warning'),
        ];
    }
}