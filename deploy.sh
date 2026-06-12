#!/bin/sh

# 1. Clear out any old configuration cache keys
php artisan optimize:clear

# 2. Run your live database structures safely
php artisan migrate --force

# 3. CRITICAL FIX: Automatically insert your Admin user if it doesn't exist yet
php artisan tinker --execute=" \
    if (!\App\Models\User::where('email', 'admin@gmail.com')->exists()) { \
        \App\Models\User::create([ \
            'name' => 'admin', \
            'email' => 'admin@gmail.com', \
            'password' => bcrypt('password'), \
            'role' => 'admin', \
            'is_active' => 1, \
            'lang_preference' => 'en' \
        ]); \
    }"

# 4. Establish asset linkages for profile photos
php artisan storage:link

# 5. Boot up the PHP process and Nginx web manager engines smoothly
php-fpm -D && nginx -g "daemon off;"