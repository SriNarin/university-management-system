<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Faculty;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;

class AdminDataOverview extends BaseWidget
{   
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    // Restrict this widget so it only displays inside the main Admin Panel
    public static function canView(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users Roles', User::count())
                ->description('Grand total profiles in system')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            
            Stat::make('Total Faculty Structures', Faculty::count())
                ->description('Active educational faculties')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            

            Stat::make('Active Teachers', User::where('role', 'teacher')->count())
                ->description('Assigned academic professors')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Study Office Staff', User::where('role', 'study_office')->count())
                ->description('Administrative operators')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),

            // --- 🌟 New Added Data Columns ---
            Stat::make('Total Student Roles', User::where('role', 'student')->count())
                ->description('Enrolled students accounts')
                ->descriptionIcon('heroicon-m-identification')
                ->color('success'),
            
            Stat::make('Total Active Classes', SchoolClass::count())
                ->description('Classrooms active across semesters')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('warning'),

            
        ];
    }
}