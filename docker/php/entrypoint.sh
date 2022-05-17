#!/usr/bin/env bash

echo "Installing composer dependencies"

chmod 775 -R ./
chown 1000:www-data -R ./

composer install --no-interaction

chmod 775 -R ./
chown 1000:www-data -R ./

# run parent entrypoint foreground process from php-fpm image
exec docker-php-entrypoint "$@"