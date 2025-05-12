#!/bin/bash

echo "🚀 Starting Laravel, Inertia & Vue.js deployment..."

# === CONFIGURATION ===
USER="muhuri"
SUB_DOMAIN="muhuri-web"
DOMAIN="mkrdev.xyz"
APP_DIR="/home/$USER/web/$SUB_DOMAIN.$DOMAIN/public_html"
PHP="php8.3"
COMPOSER="/usr/local/bin/composer"  # Update this if composer is installed elsewhere

# === STEP 1: Navigate to App Directory ===
cd "$APP_DIR" || {
    echo "❌ Failed to access $APP_DIR"
    exit 1
}

# === STEP 2: Check for Git Repository ===
if [ ! -d ".git" ]; then
    echo "❌ No Git repository found in $APP_DIR"
    exit 1
fi

# === STEP 3: Pull Latest Code ===
echo "📥 Pulling latest changes from Git..."

git config --global --add safe.directory "$APP_DIR"

git reset --hard
git pull origin main --ff

# === STEP 4: PHP Dependencies ===
echo "📦 Installing Composer dependencies..."
# Optional clean slate
# rm -rf vendor

sudo -u "$USER" $PHP $COMPOSER install --no-interaction --prefer-dist --optimize-autoloader || {
    echo "❌ Composer install failed"
    exit 1
}

# === STEP 5: Laravel Environment Setup ===
echo "🔐 Setting up Laravel application..."

if [ ! -f ".env" ]; then
    echo "📄 .env not found. Copying from example..."
    cp .env.example .env
fi

echo "📂 Fixing permissions..."
chmod -R ug+rwx storage bootstrap/cache
chown -R "$USER":www-data storage bootstrap/cache

# === Fix vendor folder ownership ===
echo "🔧 Fixing vendor folder ownership..."
chown -R "$USER":www-data vendor

touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
chown "$USER":www-data storage/logs/laravel.log

# === STEP 6: Laravel Artisan Commands ===
echo "🔑 Generating application key..."
$PHP artisan key:generate --force || {
    echo "❌ Artisan key generation failed"
    exit 1
}

echo "🧪 Running migrations & caching..."
$PHP artisan migrate --force
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

echo "✅ Deployment completed successfully!"
