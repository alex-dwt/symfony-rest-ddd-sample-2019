#!/usr/bin/env bash

sleep 15

./bin/console cache:clear

./bin/console doctrine:schema:update --force

ini=/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
echo "xdebug.remote_connect_back=0" >> ${ini}
echo "xdebug.remote_enable=1" >> ${ini}
echo "xdebug.remote_autostart=1" >> ${ini}
echo "xdebug.remote_host=$PHPSTORM_HOST_IP" >> ${ini}

export PHP_IDE_CONFIG="serverName=skeleton"

php-fpm -R