<?php

namespace App\Filament\Resources;

use App\Filament\Shared\AbstractDepartmentResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class DepartmentResource extends AbstractDepartmentResource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static \UnitEnum|string|null $navigationGroup = 'Academic Classes Management';

    public static function canAccess(): bool
    {
        // Enforce structural panel entry isolation checkpoint
        return Auth::check() && Str::lower(Auth::user()->role) === 'admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\DepartmentResource\Pages\ListDepartments::route('/'),
            'create' => \App\Filament\Resources\DepartmentResource\Pages\CreateDepartment::route('/create'),
            'view' => \App\Filament\Resources\DepartmentResource\Pages\ViewDepartment::route('/{record}'),
            'edit' => \App\Filament\Resources\DepartmentResource\Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}