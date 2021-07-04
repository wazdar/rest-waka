#!/bin/bash

cd /var/www && composer install

exec php -S 0.0.0.0:8080 -t public