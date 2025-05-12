#!/bin/bash

set -e
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

# === STEP 2: Ensure Git is Ready ===
if [ ! -d ".git" ]; then
    echo "❌ No Git repository found."
    exit 1
fi

echo "📥 Pulling latest code from Git..."
git config --global --add safe.directory "$APP_DIR"
git reset --hard
git pull origin main --ff-only

# === STEP 3: Fix Permissions Before Composer ===
echo "🔧 Fixing permissions before Composer..."
chown -R "$USER":"$USER" vendor/ storage/ bootstrap/cache/

# === STEP 4: Install Composer Dependencies ===
echo "📦 Installing Composer dependencies..."
sudo -u "$USER" composer install --no-interaction --prefer-dist --optimize-autoloader || {
    echo "❌ Composer install failed"
    exit 1
}

# === STEP 5: Laravel Environment Setup ===
echo "🔐 Setting up Laravel..."

if [ ! -f ".env" ]; then
    echo "📄 .env not found, copying from .env.example"
    cp .env.example .env
fi

echo "🔧 Setting permissions for .env and storage..."
chown "$USER":"www-data" .env
chmod 664 .env

chown -R "$USER":"www-data" storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
chown "$USER":"www-data" storage/logs/laravel.log

# === STEP 6: Laravel Artisan Commands ===
echo "🔑 Generating app key..."
sudo -u "$USER" $PHP artisan key:generate --force

echo "🧪 Running migrations..."
sudo -u "$USER" $PHP artisan migrate --force

echo "📦 Caching configs, routes, and views..."
sudo -u "$USER" $PHP artisan config:cache
sudo -u "$USER" $PHP artisan route:cache
sudo -u "$USER" $PHP artisan view:cache

# === STEP 7: Build Frontend ===
echo "🧱 Building Vue.js frontend..."
sudo -u "$USER" npm install
sudo -u "$USER" npm run build

# === FINAL PERMISSIONS FIX (optional but recommended) ===
echo "🔐 Resetting final file permissions..."
chown -R "$USER":"www-data" .
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

echo "✅ Deployment complete!"
