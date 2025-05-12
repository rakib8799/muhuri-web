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
    chown "$USER":"www-data" .env
    chmod 664 .env
    echo "🔑 Generating app key..."
    sudo -u "$USER" $PHP artisan key:generate
fi

# === STEP 6: File Permissions ===
echo "🔧 Setting Laravel folder permissions..."
chown -R "$USER":"www-data" storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# === STEP 7: Node Modules and Vite Build ===
echo "🧹 Cleaning up node_modules and old build..."
rm -rf node_modules/
rm -rf package-lock.json
rm -rf public/build/

echo "📦 Installing npm packages..."
sudo -u "$USER" npm install --legacy-peer-deps

echo "🔧 Fixing build directory permissions..."
mkdir -p public/build
chown -R "$USER":"www-data" public/build
chmod -R 775 public/build

echo "🏗️ Running Vite build..."
sudo -u "$USER" npm run build || {
    echo "❌ Vite build failed"
    exit 1
}

# === STEP 8: Laravel Commands ===
echo "📊 Running Laravel optimizations..."
sudo -u "$USER" $PHP artisan config:cache
sudo -u "$USER" $PHP artisan route:cache
sudo -u "$USER" $PHP artisan view:cache

echo "✅ Deployment complete!"
