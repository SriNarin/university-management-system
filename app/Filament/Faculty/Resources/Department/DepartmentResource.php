<?php

namespace App\Filament\Faculty\Resources;

use App\Models\Department;
use App\Filament\Shared\AbstractDepartmentResource;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Support\Icons\Heroicon;

use BackedEnum;

class DepartmentResource extends AbstractDepartmentResource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static \UnitEnum|string|null $navigationGroup = 'Department Management';

    public static function canAccess(): bool
    {
        return Auth::check() && Str::lower(Auth::user()->role) === 'faculty_manager';
    }

    /**
     * CRITICAL SECURITY SCENE ISOLATION LAYER: 
     * Limits the data array view context so managers can only see departments 
     * nested inside the specific Faculty record assigned to their user ID.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('faculty', function (Builder $query) {
                $query->where('manager_id', Auth::id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Faculty\Resources\DepartmentResource\Pages\ListDepartments::route('/'),
            'create' => \App\Filament\Faculty\Resources\DepartmentResource\Pages\CreateDepartment::route('/create'),
            'view' => \App\Filament\Faculty\Resources\DepartmentResource\Pages\ViewDepartment::route('/{record}'),
            'edit' => \App\Filament\Faculty\Resources\DepartmentResource\Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}