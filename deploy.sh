#!/usr/bin/env bash
#
# Deploy helper for the InMotion (cPanel) server.
#
# Usage (run from the project folder on the server):
#     bash deploy.sh
#
# Always clears the compiled-view / route / config / app caches so a freshly
# pulled build never shows stale markup or old asset hashes.
#
set -e
cd "$(dirname "$0")"

echo "==> [1/3] git pull"
git pull origin main

echo "==> [2/3] clearing all Laravel caches"
php artisan optimize:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan event:clear || true

echo "==> [3/3] removing any compiled/packed bootstrap caches"
rm -f bootstrap/cache/*.php

echo ""
echo "==> Done. Hard-refresh the browser (Cmd/Ctrl+Shift+R)."