<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Shared\AbstractSubjectResource;
use App\Filament\Faculty\Resources\SubjectResource\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use BackedEnum;


class SubjectResource extends AbstractSubjectResource
{
     protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static \UnitEnum|string|null $navigationGroup = 'Academic Classes Management';

    protected static ?string $slug = 'subjects';

    /**
     * CRITICAL SAFETY SCOPE: 
     * Scopes the query so Faculty Managers only see/manage subjects inside their own Faculty departments!
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('department.faculty', function (Builder $query) {
                $query->where('manager_id', Auth::id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'view'   => Pages\ViewSubject::route('/{record}'),
            'edit'   => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}