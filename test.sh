#!/bin/bash
php artisan migrate:fresh --env=testing
php artisan db:seed --env=testing
./vendor/bin/pest --coverage
# php artisan test
# php artisan test --filter UserLoginTest
