<?php

namespace App\Filament\Office\Resources\SubjectFinalGrades\Pages;

use Filament\Resources\Pages\ListRecords;

class ListSubjectFinalGrades extends ListRecords
{
    // 🌟 Absolute literal target mapping string bypasses autoloader confusion
    protected static string $resource = 'App\Filament\Office\Resources\SubjectFinalGrades\SubjectFinalGradeResource';

    protected function getHeaderActions(): array
    {
        return [];
    }
}