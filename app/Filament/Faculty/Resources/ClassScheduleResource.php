<?php

namespace App\Filament\Faculty\Resources;
use App\Filament\Faculty\Resources\ClassesAcademicResource;
use App\Filament\Shared\AbstractClassScheduleResource;
use App\Filament\Faculty\Resources\ClassScheduleResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ClassScheduleResource extends AbstractClassScheduleResource
{
   

    // Updated directly to use your custom project architecture properties
    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static \UnitEnum|string|null $navigationGroup = 'Manage Department & Class';
    
    protected static ?string $pluralModelLabel = 'Classes Lists';

     protected static ?string $slug = 'class-list';

     
    /**
     * Scopes the query to ensure Faculty Managers only view schedules related to their own faculty departments.
     */
     public static function canAccess(): bool
    {
        return Auth::check() && Str::lower(Auth::user()->role) === 'faculty_manager';
    }

    public static function getEloquentQuery(): Builder
    {
        // Scopes data dynamically so faculty managers only view their own classes
        return parent::getEloquentQuery()
            // Updated chain: schoolClass -> academicStructure -> department -> faculty
            ->whereHas('schoolClass.academicStructure.department.faculty', function (Builder $query) {
                $query->where('manager_id', Auth::id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClassSchedules::route('/'),
            'create' => Pages\CreateClassSchedule::route('/create'),
            'view'   => Pages\ViewClassSchedule::route('/{record}'),
            'edit'   => Pages\EditClassSchedule::route('/{record}/edit'),
        ];
    }
}