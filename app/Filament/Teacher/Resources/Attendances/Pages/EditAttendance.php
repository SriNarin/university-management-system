<?php

namespace App\Filament\Teacher\Resources\Attendances;

use App\Filament\Teacher\Resources\Attendances\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * 🌟 Customize redirection to go back to the list history table 
     * instead of staying stuck on the single form screen.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}