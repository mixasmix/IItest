#!/usr/bin/env bash
set -e
mkdir -p /var/www/api/var
mkdir -p /var/www/api/var/cache
mkdir -p /var/www/api/vendor
chmod 777 -R var
chmod 777 -R var/cache
chmod 777 -R vendor
composer install --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction
composer install
#bin/console swagger

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
        set -- php-fpm "$@"
fi

exec "$@"
