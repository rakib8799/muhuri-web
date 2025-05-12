#!/bin/bash

echo "🚀 Starting Laravel, Inertia & Vue.js deployment..."

# === CONFIGURATION ===
USER="muhuri"
SUB_DOMAIN="muhuri-central-admin"
DOMAIN="mkrdev.xyz"
APP_DIR="/home/$USER/web/$SUB_DOMAIN.$DOMAIN/public_html"
PHP="php8.3"

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
git reset --hard
git pull origin main --ff-only

# === STEP 4: PHP Dependencies ===
echo "📦 Installing PHP dependencies..."
if [ -d "vendor" ]; then
    echo "🧹 Removing old vendor directory..."
    rm -rf vendor/
fi

composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# === STEP 5: Laravel Environment Setup ===
echo "🔐 Setting up Laravel application..."
if [ ! -f ".env" ]; then
    echo "📄 .env not found. Copying from example..."
    cp .env.example .env
fi

echo "📂 Fixing permissions..."
chmod -R ug+rwx storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
chown www-data:www-data storage/logs/laravel.log

# === STEP 6: Laravel Artisan Commands ===
echo "🔑 Generating application key..."
$PHP artisan key:generate --force

echo "🧪 Running migrations & caching configs..."
$PHP artisan migrate --force
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

# === STEP 7: Node/Vue Build ===
echo "🧱 Installing Node dependencies & building frontend..."
if [ -d "node_modules" ]; then
    echo "🧹 Cleaning old node_modules..."
    rm -rf node_modules/
fi

npm ci || npm install
npm run build

echo "✅ Deployment finished successfully!"
