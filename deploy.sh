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

# === STEP 2: Git Pull ===
if [ ! -d ".git" ]; then
    echo "❌ No Git repository found."
    exit 1
fi

echo "📥 Pulling latest code..."
git config --global --add safe.directory "$APP_DIR"
git reset --hard
git pull origin main --ff-only

# === STEP 3: Permissions before Composer ===
echo "🔧 Fixing permissions before Composer..."
chown -R "$USER":"$USER" vendor/ storage/ bootstrap/cache/

# === STEP 4: Composer Dependencies ===
echo "📦 Installing Composer dependencies..."

# Clear Composer cache before install to avoid old dependencies or corrupt cache
echo "🧹 Clearing Composer cache..."
sudo -u "$USER" composer clear-cache

# Install dependencies
sudo -u "$USER" composer install --no-interaction --prefer-dist --optimize-autoloader || {
    echo "❌ Composer install failed"
    exit 1
}

# Fix permissions in vendor directory
echo "🔧 Fixing permissions for vendor directory..."
chown -R "$USER":"$USER" vendor/
chmod -R 755 vendor/

# Additional step to re-run composer if needed (for fallback)
echo "❌ Running composer install again if initial attempt failed"
sudo -u "$USER" composer install --no-interaction --prefer-dist --optimize-autoloader

# === STEP 5: Laravel Environment ===
echo "🔐 Setting up Laravel..."

if [ ! -f ".env" ]; then
    echo "📄 .env not found, copying from .env.example"
    cp .env.example .env
fi

# Permissions for .env and cache dirs
echo "🔧 Fixing file permissions..."
chown "$USER":"www-data" .env
chmod 664 .env
chown -R "$USER":"www-data" storage/ bootstrap/cache/
chmod -R 775 storage/ bootstrap/cache/

# Generate app key only if not set
if ! grep -q '^APP_KEY=' .env; then
    echo "🔑 Generating app key..."
    sudo -u "$USER" $PHP artisan key:generate
else
    echo "🔑 APP_KEY already exists, skipping key generation."
fi

# === STEP 6: Node Frontend Setup ===
echo "🧹 Cleaning old node_modules..."
rm -rf node_modules package-lock.json

echo "📦 Installing Node dependencies..."
sudo -u "$USER" npm install

# Clear Vite build dir to prevent EACCES
echo "🧹 Cleaning Vite build cache..."
rm -rf public/build/assets || true
mkdir -p public/build/assets
chown -R "$USER":"$USER" public/build

echo "⚙️ Building frontend with Vite..."
sudo -u "$USER" npm run build || {
    echo "❌ Vite build failed"
    exit 1
}

# === STEP 7: Laravel Finalization ===
echo "🧼 Running Laravel cleanup..."
sudo -u "$USER" $PHP artisan config:cache
sudo -u "$USER" $PHP artisan route:cache
sudo -u "$USER" $PHP artisan view:cache

echo "✅ Deployment completed successfully!"
