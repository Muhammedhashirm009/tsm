#!/bin/bash

# Laravel Hostinger Deployment and Permissions Script

# -----------------------------------------------------
# CONFIGURATION
# -----------------------------------------------------
# Hostinger frequently places newer PHP binaries in /opt/alt/php84/usr/bin/php
# We'll try to find the correct one dynamically.

if [ -f /opt/alt/php84/usr/bin/php ]; then
    PHP_BIN="/opt/alt/php84/usr/bin/php"
elif [ -f /usr/local/lsws/lsphp84/bin/php ]; then
    PHP_BIN="/usr/local/lsws/lsphp84/bin/php"
elif command -v php84 &> /dev/null; then
    PHP_BIN="php84"
else
    # Fallback, but warn the user
    echo "\e[33mWarning: Could not find explicit PHP 8.4 binary. Falling back to default 'php'.\e[0m"
    PHP_BIN="php"
fi

COMPOSER_BIN="$PHP_BIN /opt/composer/composer.phar"
if [ ! -f /opt/composer/composer.phar ]; then
    if [ -f /usr/local/bin/composer ]; then
        COMPOSER_BIN="$PHP_BIN /usr/local/bin/composer"
    elif [ -f /usr/bin/composer ]; then
        COMPOSER_BIN="$PHP_BIN /usr/bin/composer"
    else
        # Fallback to just the command if it's in the PATH
        COMPOSER_BIN="$PHP_BIN $(which composer 2>/dev/null || echo 'composer')"
    fi
fi
# -----------------------------------------------------

echo "==============================================="
echo " Starting Laravel Deployment on Hostinger...   "
echo "==============================================="

echo "-> 1/7 Setting file and directory permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

echo "-> 2/7 Setting write permissions for storage and bootstrap/cache..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "-> 3/7 Ensuring database file exists (for SQLite)..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    echo "Created database/database.sqlite"
fi

echo "-> 4/7 Installing Composer dependencies..."
$COMPOSER_BIN install --optimize-autoloader --no-dev


echo "-> 5/8 Generating application encryption key if missing..."
$PHP_BIN artisan key:generate --force

echo "-> 6/8 Optimizing application caches..."
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "-> 7/8 Running database migrations and seeders..."
$PHP_BIN artisan migrate --force --seed

echo "-> 8/8 Creating storage link..."
$PHP_BIN artisan storage:link

echo ""
echo "==============================================="
echo " Deployment and permission setup complete! ✔️  "
echo "==============================================="
