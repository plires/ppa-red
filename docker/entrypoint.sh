#!/bin/sh
set -e

# Asegurar que existe el archivo SQLite y que www-data puede escribir
# (el directorio llega del volumen de Docker como root)
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

# Cache de configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones automáticas en cada deploy
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
