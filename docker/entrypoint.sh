#!/bin/sh
set -e

# Asegurar que existe el archivo SQLite (el directorio lo monta Dokploy como volumen)
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite

# Cache de configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones automáticas en cada deploy
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
