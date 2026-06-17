#!/bin/sh
set -e

# Fix permissions on mounted volume
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Install PHP dependencies if vendor is missing (first run or cache miss)
if [ ! -f "vendor/autoload.php" ]; then
  composer install --no-interaction
fi

# Run post-install scripts (package:discover, etc.) — skipped during build
composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# Run migrations
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
