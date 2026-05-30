#!/bin/bash
sleep 5  # wait for MySQL
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php-fpm