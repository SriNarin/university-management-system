<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\Pages;

use App\Filament\Teacher\Resources\TaskAssessments\TaskAssessmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaskAssessment extends EditRecord
{
    protected static string $resource = TaskAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
