<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        $role = Str::lower(Auth::user()?->role ?? '');
        
        if ($role !== 'admin' && $role !== 'faculty_manager') {
            return [];
        }

        return [
            CreateAction::make()
                ->label('New Announcement')
                ->icon('heroicon-o-megaphone'),
        ];
    }
}