#!/bin/bash

set -e  # Exit on error
set -o pipefail

echo "🚀 Starting Laravel, Inertia & Vue.js deployment..."

# === CONFIGURATION ===
USER="muhuri"
SUB_DOMAIN="muhuri-web"
DOMAIN="mkrdev.xyz"
APP_DIR="/home/$USER/web/$SUB_DOMAIN.$DOMAIN/public_html"
PHP="php8.3"

# === STEP 1: Navigate to App Directory ===
echo "📂 Navigating to app directory..."
cd "$APP_DIR" || {
    echo "❌ Failed to access $APP_DIR"
    exit 1
}

# === STEP 2: Ensure Git is ready ===
if [ ! -d ".git" ]; then
    echo "❌ No Git repository found."
    exit 1
fi

echo "📥 Pulling latest code from Git..."
git config --global --add safe.directory "$APP_DIR"
git reset --hard
git pull origin main --ff-only

# === STEP 3: Fix Ownership Before Composer ===
echo "🔧 Fixing permissions before Composer operations..."
chown -R "$USER":"$USER" vendor/ storage/ bootstrap/cache/

# === STEP 4: Composer Dependencies ===
echo "📦 Installing Composer dependencies..."
sudo -u "$USER" composer install --no-interaction --prefer-dist --optimize-autoloader || {
    echo "❌ Composer install failed"
    exit 1
}

# === STEP 5: Laravel Setup ===
echo "🔐 Setting up Laravel app..."

if [ ! -f ".env" ]; then
    echo "📄 .env not found, copying from .env.example"
    cp .env.example .env
fi

echo "🔑 Generating app key..."
sudo -u "$USER" $PHP artisan key:generate --force

# === STEP 6: Cache & Migrate ===
echo "🧪 Running migrations and caching config/routes..."
sudo -u "$USER" $PHP artisan migrate --force
sudo -u "$USER" $PHP artisan config:cache
sudo -u "$USER" $PHP artisan route:cache
sudo -u "$USER" $PHP artisan view:cache

# === STEP 7: Permissions Fix ===
echo "🧼 Final permission fixes..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
chown www-data:www-data storage/logs/laravel.log

echo "✅ Deployment completed successfully!"
