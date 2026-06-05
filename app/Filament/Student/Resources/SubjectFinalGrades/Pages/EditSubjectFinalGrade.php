<?php

namespace App\Filament\Student\Resources\SubjectFinalGrades\Pages;

use App\Filament\Student\Resources\SubjectFinalGrades\SubjectFinalGradeResource;
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
