# Local Development Setup

This guide is for developers working on both the framework and demo application simultaneously.

## Problem

When developing locally, you want changes in `../framework` to be **instantly visible** in the demo app without running `composer update` every time.

## Solution

Use a local composer.json that includes the path repository.

### Setup

1. Copy the local development config:
```bash
cp composer.local.json.example composer.local.json
```

2. Install dependencies using the local config:
```bash
# Remove existing vendor and lock
rm -rf vendor composer.lock

# Use local composer config
mv composer.json composer.json.prod
mv composer.local.json composer.json

# Install with symlink to ../framework
php8.5 /usr/local/bin/composer install

# Restore files
mv composer.json composer.local.json
mv composer.json.prod composer.json
```

Or use this one-liner:
```bash
php8.5 /usr/local/bin/composer install --no-interaction --working-dir=/var/www/projekty/book/demo-app
```

### Verify Symlink

Check that vendor/larafony/core is a symlink:
```bash
ls -la vendor/larafony/core
# Should show: vendor/larafony/core -> ../../../framework
```

### Development Workflow

Now you can edit files in `../framework/src/` and changes are **immediately visible** in demo-app without any `composer update`.

```bash
# Edit framework
vim ../framework/src/Larafony/Database/ORM/Model.php

# Changes are instant - just refresh browser
php -S localhost:8000 -t public
```

### Switching Back to Production Mode

To test the app as end-users would use it (from Packagist):

```bash
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer install
# This will use composer.json (without path repository) and fetch from Packagist
```

## How It Works

- `composer.json` - Production version (committed to git, used by `composer create-project`)
  - Requires `larafony/core` from Packagist
  - No `repositories` section

- `composer.local.json.example` - Local development template
  - Includes `repositories` with `path` type pointing to `../framework`
  - Uses `symlink: true` for instant changes
  - **Not committed to git** (in .gitignore)

- Copy `.example` to `composer.local.json` for your local setup
- Use `composer.local.json` during development
- Use `composer.json` to test production behavior
