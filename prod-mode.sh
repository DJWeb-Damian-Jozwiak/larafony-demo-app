#!/bin/bash
cd /var/www/projekty/book/demo-app
echo "🔧 Setting up production mode..."
php8.5 /usr/local/bin/composer config --unset repositories.local 2>/dev/null || true
echo "📦 Reinstalling from Packagist..."
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer install 2>&1 | grep -E "Installing" || true
echo "✅ Prod mode enabled: using Packagist"
echo "⚠️  NOTE: composer.json changed - DO NOT commit this state!"
