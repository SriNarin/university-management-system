<?php

namespace App\Filament\Office\Resources\SubjectFinalGrades\Pages;

use App\Filament\Office\Resources\SubjectFinalGrades\SubjectFinalGradeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubjectFinalGrade extends EditRecord
{
    protected static string $resource = SubjectFinalGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
