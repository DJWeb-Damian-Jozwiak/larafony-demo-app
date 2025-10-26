# Local Development Setup

This guide is for developers working on both the framework and demo application simultaneously.

## Problem

When developing locally, you want changes in `../framework` to be **instantly visible** in the demo app without running `composer update` every time.

## Solution

Use helper scripts to switch between development mode (local symlink) and production mode (Packagist).

### Quick Setup

**Development Mode** (instant framework changes):
```bash
./dev-mode.sh
```

This will:
1. Add `repositories.local` to composer.json (path to `../framework`)
2. Reinstall with symlink
3. Verify symlink is created

**Production Mode** (test Packagist behavior):
```bash
./prod-mode.sh
```

This will:
1. Remove `repositories.local` from composer.json
2. Reinstall from Packagist
3. Warn you to restore dev mode before committing

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

## How It Works

**Repository state (committed to git):**
- `composer.json` does **NOT** have `repositories` section
- Works for `composer create-project` (users install from Packagist)
- Clean, production-ready

**Development mode (`./dev-mode.sh`):**
- Adds `repositories.local` pointing to `../framework`
- Installs with `symlink: true`
- Changes in `../framework/src/` are instant
- **composer.json is modified** (git will show changes)

**Production mode (`./prod-mode.sh`):**
- Removes `repositories.local`
- Installs from Packagist (as users would)
- Restores composer.json to repo state

### Important Notes

⚠️ **Git Warning**: When in dev mode, `git status` will show composer.json as modified. This is expected.

✅ **Before committing**: Always run `./prod-mode.sh` OR manually verify composer.json has no `repositories` section.

✅ **After committing**: Run `./dev-mode.sh` to restore local development setup.

### Manual Mode

If you prefer manual control:

**Enable dev mode:**
```bash
php8.5 /usr/local/bin/composer config repositories.local '{"type": "path", "url": "../framework", "options": {"symlink": true}}'
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer install
```

**Disable dev mode:**
```bash
php8.5 /usr/local/bin/composer config --unset repositories.local
rm -rf vendor composer.lock
php8.5 /usr/local/bin/composer install
```
