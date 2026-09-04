#!/usr/bin/env bash
# Dev web server with raised upload limits (see ../php.ini).
# Usage: ./serve.sh   (serves http://127.0.0.1:8001)
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$DIR/public"   # server.php resolves the front controller relative to cwd
export PHP_CLI_SERVER_WORKERS="${PHP_CLI_SERVER_WORKERS:-8}"
exec php -c ../php.ini -S 127.0.0.1:8001 "$DIR/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php"