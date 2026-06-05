<?php

namespace App\Filament\Teacher\Resources\MyTeachingScheduleResource\Pages;

use App\Filament\Teacher\Resources\MyTeachingScheduleResource;
use Filament\Resources\Pages\ListRecords;

class ListMyTeachingSchedules extends ListRecords
{
    protected static string $resource = MyTeachingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}