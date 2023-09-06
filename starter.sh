#!/bin/sh
#cd /var/www/html
cd /var/www/production/rank-up

# Run database migrations and other post-startup commands
php artisan storage:link
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

# You can add more commands here if needed

# Start Apache
apache2-foreground
