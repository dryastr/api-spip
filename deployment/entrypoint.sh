#!/usr/bin/env bash
set -e

container_mode=${CONTAINER_MODE:-app}
echo "Container mode: $container_mode"

php() {
  su octane -c "php $*"
}

initialStuff() {
    php artisan optimize:clear; \
    php artisan package:discover --ansi; \
    php artisan event:cache; \
    php artisan config:cache; \
    php artisan route:cache;
}

initialStuffDev() {
    php artisan optimize:clear;
    # ./vendor/bin/pint
    # ./vendor/bin/pest
}

if [ "$1" != "" ]; then
    exec "$@"
elif [ "$container_mode" = "app.production" ]; then
    initialStuff
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.app.conf
elif [ "$container_mode" = "app.local" ]; then
    initialStuffDev
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.app.dev.conf
elif [ "$container_mode" = "scheduler" ]; then
    initialStuff
    exec supercronic /etc/supercronic/laravel
else
    echo "Container mode mismatched."
    exit 1
fi
