#!/bin/sh
set -e

# Cache de configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones automáticas en cada deploy
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
