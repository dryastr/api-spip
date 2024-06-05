composer install --ignore-platform-reqs --optimize-autoloader --no-dev --no-scripts --no-interaction
php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\MenuSeeder --force
php artisan db:seed --class=Database\\Seeders\\RoleSeeder --force
composer dump-autoload --no-scripts --no-interaction
