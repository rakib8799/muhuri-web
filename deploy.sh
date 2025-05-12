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

# === STEP 5: Laravel Environment Setup ===
echo "🔐 Setting up Laravel..."

if [ ! -f ".env" ]; then
    echo "📄 .env not found, copying from .env.example..."
    cp .env.example .env
fi

# Fix .env Permissions
echo "🔧 Fixing .env permissions..."
chown "$USER":"www-data" .env
chmod 664 .env

# Generate key only if missing
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
    echo "🔑 Generating app key..."
    sudo -u "$USER" $PHP artisan key:generate
else
    echo "✅ APP_KEY already exists. Skipping key generation."
fi

# === STEP 6: Laravel Cache & Migrations ===
echo "🧹 Clearing and caching config..."
sudo -u "$USER" $PHP artisan config:clear
sudo -u "$USER" $PHP artisan config:cache
sudo -u "$USER" $PHP artisan route:cache
sudo -u "$USER" $PHP artisan view:cache

echo "🗃️ Running migrations..."
sudo -u "$USER" $PHP artisan migrate --force

# === STEP 7: Frontend (Optional: if using npm/vue) ===
echo "🎨 Building frontend assets (optional)..."
# Uncomment if needed
sudo -u "$USER" npm install
sudo -u "$USER" npm run build

# === STEP 8: Final Permissions ===
echo "🔐 Final permission fixes..."
chown -R "$USER":"www-data" storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

echo "✅ Deployment completed successfully!"
