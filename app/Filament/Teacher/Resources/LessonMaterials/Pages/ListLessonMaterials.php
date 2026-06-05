<?php

namespace App\Filament\Teacher\Resources\LessonMaterialResource\Pages;

use App\Filament\Teacher\Resources\LessonMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessonMaterials extends ListRecords
{
    protected static string $resource = LessonMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Upload New Material'),
        ];
    }
}