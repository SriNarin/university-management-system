<?php

namespace App\Filament\Resources\Announcement\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ViewAnnouncement extends ViewRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        $role = Str::lower(Auth::user()?->role ?? '');

        if ($role !== 'admin' && $role !== 'faculty_manager') {
            return [];
        }

        return [
            EditAction::make()
                ->label('Modify Announcement'),
        ];
    }
}