<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@university.edu'],
            [
                'name' => 'Root System Super Admin',
                'password' => $defaultPassword,
                'role' => 'admin',
                'is_active' => true,
                'lang_preference' => 'en',
                'permissions_matrix' => ['all_permissions']
            ]
        );

        // Faculty Manager
        User::updateOrCreate(
            ['email' => 'manager@university.edu'],
            [
                'name' => 'Dr. Keo Meas (Faculty Manager)',
                'password' => $defaultPassword,
                'role' => 'faculty_manager',
                'is_active' => true,
                'lang_preference' => 'kh', // Khmer language preference flag
                'permissions_matrix' => ['bypass_score_locks']
            ]
        );

        // School Study Office
        User::updateOrCreate(
            ['email' => 'office@university.edu'],
            [
                'name' => 'Study Office Management Node',
                'password' => $defaultPassword,
                'role' => 'study_office',
                'is_active' => true,
                'lang_preference' => 'en',
                'permissions_matrix' => ['view_global_analytics']
            ]
        );

        // Academic Teacher
        User::updateOrCreate(
            ['email' => 'teacher@university.edu'],
            [
                'name' => 'Prof. Soun Dara (Academic Lecturer)',
                'password' => $defaultPassword,
                'role' => 'teacher',
                'is_active' => true,
                'lang_preference' => 'kh',
                'permissions_matrix' => ['dispatch_unvetted_lessons']
            ]
        );

        // Student Core Account
        User::updateOrCreate(
            ['email' => 'student@university.edu'],
            [
                'name' => 'Chan Sovann (Undergraduate Student)',
                'password' => $defaultPassword,
                'role' => 'student',
                'is_active' => true,
                'lang_preference' => 'kh',
                'permissions_matrix' => []
            ]
        );
    }
}