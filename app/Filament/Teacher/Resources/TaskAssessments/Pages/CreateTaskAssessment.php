<?php

namespace App\Filament\Teacher\Resources\TaskAssessments\Pages;

use App\Filament\Teacher\Resources\TaskAssessments\TaskAssessmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskAssessment extends CreateRecord
{
    protected static string $resource = TaskAssessmentResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
