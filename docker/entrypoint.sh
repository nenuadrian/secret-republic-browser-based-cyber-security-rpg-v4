#!/bin/sh
# Secret Republic container entrypoint.
#
# When DB_HOST is provided (e.g. by compose.yaml) the database connection is
# taken from the environment, so the setup wizard only needs to ask for the
# admin account. Without DB_HOST the full web setup wizard runs as usual.
set -e

APP_DIR=/var/www/html
DB_CONFIG="$APP_DIR/fuel/app/config/db.php"
CRYPT_CONFIG="$APP_DIR/fuel/app/config/crypt.php"

if [ -n "${DB_HOST:-}" ] && [ ! -f "$DB_CONFIG" ]; then
	cp "$APP_DIR/docker/db.php" "$DB_CONFIG"
	chown www-data:www-data "$DB_CONFIG"
fi

# Stable session encryption key (64 hex chars, e.g. `openssl rand -hex 32`).
if [ -n "${SR_CRYPT_KEY:-}" ] && [ ! -f "$CRYPT_CONFIG" ]; then
	cp "$APP_DIR/docker/crypt.php" "$CRYPT_CONFIG"
	chown www-data:www-data "$CRYPT_CONFIG"
fi

exec docker-php-entrypoint "$@"
