#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Build assets
npm install
npm run build

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage directories
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
chmod -R 775 storage bootstrap/cache 