#!/bin/sh

# 1. Clear out any old configuration cache keys
php artisan optimize:clear

# 2. Run your live database structures into your Aiven cluster safely
php artisan migrate --force

# 3. Establish asset linkages for profile photos and Filament layout structures
php artisan storage:link

# 4. Boot up the PHP process and Nginx web manager engines smoothly
php-fpm -D && nginx -g "daemon off;"