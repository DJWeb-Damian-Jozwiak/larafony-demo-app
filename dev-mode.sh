#!/bin/bash
cd /var/www/projekty/book/demo-app
echo "🔧 Setting up development mode..."
php8.5 /usr/local/bin/composer config repositories.local '{"type": "path", "url": "../framework", "options": {"symlink": true}}'
echo "📦 Reinstalling with symlink..."
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer install 2>&1 | grep -E "(Installing|Symlinking)" || true
if [ -L "vendor/larafony/core" ]; then
    echo "✅ Dev mode enabled: framework symlinked from ../framework"
else
    echo "⚠️  Warning: Not a symlink!"
fi
