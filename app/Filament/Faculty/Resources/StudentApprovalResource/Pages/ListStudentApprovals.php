<?php

namespace App\Filament\Faculty\Resources\StudentApprovalResource\Pages;

use App\Filament\Faculty\Resources\StudentApprovalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentApprovals extends ListRecords
{
    protected static string $resource = StudentApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // We leave this empty because approvals are managed by the Study Office panel,
            // the Faculty Manager only reviews and approves existing items.
        ];
    }
}