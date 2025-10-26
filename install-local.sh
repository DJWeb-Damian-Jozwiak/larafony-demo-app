#!/bin/bash
cd /var/www/projekty/book/demo-app
echo "Installing with local framework symlink..."
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer config repositories.local '{"type": "path", "url": "../framework", "options": {"symlink": true}}'
php8.5 /usr/local/bin/composer install
ls -la vendor/larafony/core
echo "Done!"
