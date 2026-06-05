<?php

namespace App\Filament\Student\Resources\StudentTaskExecutions\Pages;

use App\Filament\Student\Resources\StudentTaskExecutionResource;
use Filament\Resources\Pages\ListRecords;

class ListStudentTaskExecutions extends ListRecords
{
    protected static string $resource = StudentTaskExecutionResource::class;
}