#!/bin/bash

chown -R ubuntu:ubuntu /var/www/booking-api
chmod -R 777 /var/www/booking-api/app/logs
chmod -R 777 /var/www/booking-api/app/cache

export COMPOSER_HOME=/var/www/booking-api

cd /var/www/booking-api
composer install
