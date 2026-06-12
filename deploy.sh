#!/bin/sh

# 1. Clear out any old configuration cache keys
php artisan optimize:clear

# 2. Safely run migrations without dropping any existing tables
php artisan migrate --force

# 3. FIXED: Fail-safe insertion block for your admin user accounts
php artisan tinker --execute="
try {
    if (!\App\Models\User::where('email', 'admin@gmail.com')->exists()) {
        \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'is_active' => 1,
            'lang_preference' => 'en'
        ]);
        echo 'Admin user seeded successfully!\n';
    } else {
        echo 'Admin user already exists.\n';
    }
} catch (\Exception \$e) {
    echo 'Seeding skipped: ' . \$e->getMessage() . '\n';
}"

# 4. Establish asset linkages for profile photos
php artisan storage:link

# 5. Clear everything out so the app runs completely fresh
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Boot up the PHP process and Nginx web manager engines smoothly
php-fpm -D && nginx -g "daemon off;"