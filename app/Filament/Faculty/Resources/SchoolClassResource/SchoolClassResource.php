<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Shared\AbstractSchoolClassResource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class SchoolClassResource extends AbstractSchoolClassResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static \UnitEnum|string|null $navigationGroup = 'Manage Department & Class';
    
    protected static ?string $pluralModelLabel = 'Classes Academic';

    public static function canAccess(): bool
    {
        return Auth::check() && Str::lower(Auth::user()->role) === 'faculty_manager';
    }

    public static function getEloquentQuery(): Builder
    {
        // Scopes data dynamically so faculty managers only view their own classes
        return parent::getEloquentQuery()
            ->whereHas('academicStructure.department.faculty', function (Builder $query) {
                $query->where('manager_id', Auth::id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Faculty\Resources\SchoolClassResource\Pages\ListSchoolClasses::route('/'),
            'view' => \App\Filament\Faculty\Resources\SchoolClassResource\Pages\ViewSchoolClass::route('/{record}'),
            'edit' => \App\Filament\Faculty\Resources\SchoolClassResource\Pages\EditSchoolClass::route('/{record}/edit'),
        ];
    }
}