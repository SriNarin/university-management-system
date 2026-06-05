<?php

namespace App\Filament\Faculty\Resources\ClassesAcademicResource\Pages;

use App\Filament\Faculty\Resources\ClassesAcademicResource;
use Filament\Resources\Pages\ListRecords;

class ListClassesAcademics extends ListRecords
{
    protected static string $resource = ClassesAcademicResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}