#!/bin/bash
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf
php artisan migrate --force
apache2-foreground