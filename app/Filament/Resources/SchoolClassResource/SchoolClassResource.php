<?php

namespace App\Filament\Resources;

use App\Filament\Shared\AbstractSchoolClassResource;
use App\Filament\Resources\SchoolClassResource\Pages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Support\Icons\Heroicon;



class SchoolClassResource extends AbstractSchoolClassResource
{
     protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;

    protected static \UnitEnum|string|null $navigationGroup = 'Manage Faculty & Department';
    
    protected static ?string $pluralModelLabel = 'Manage Classes Academic';

    public static function canAccess(): bool
    {
        return Auth::check() && Str::lower(Auth::user()->role) === 'admin';
    }

    public static function getPages(): array
    {

        return [
            'index' => \App\Filament\Resources\SchoolClassResource\Pages\ListSchoolClasses::route('/'),
            'view' => \App\Filament\Resources\SchoolClassResource\Pages\ViewSchoolClass::route('/{record}'),
            'edit' => \App\Filament\Resources\SchoolClassResource\Pages\EditSchoolClass::route('/{record}/edit'),
        ];
    }
}