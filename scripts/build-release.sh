#!/usr/bin/env bash
# Package a standalone release from the current checkout.
#
#   composer install --no-dev --optimize-autoloader
#   scripts/build-release.sh v2026.11
#
# Produces dist/secretrepublic-<version>.{tar.gz,zip} and dist/SHA256SUMS.txt.
# The archives contain FuelPHP and all PHP libraries, so they run as-is.
set -euo pipefail

VERSION="${1:?usage: scripts/build-release.sh <version>}"
NAME="secretrepublic-$VERSION"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/dist"
STAGE="$(mktemp -d)"
trap 'rm -rf "$STAGE"' EXIT

cd "$ROOT"
[ -f fuel/core/classes/autoloader.php ] || { echo "fuel/core missing - run composer install --no-dev first" >&2; exit 1; }
[ ! -d fuel/vendor/phpunit ] || { echo "dev dependencies present - run composer install --no-dev first" >&2; exit 1; }

mkdir -p "$STAGE/$NAME"
tar cf - \
	--exclude=./.git --exclude=./.github --exclude=./dist --exclude=./docs --exclude=./images \
	--exclude=./tests --exclude=./scripts --exclude=./node_modules --exclude=./compose.yaml \
	--exclude=./.env --exclude=./.env.example --exclude=./phpunit.xml.dist --exclude=./.phpunit.cache \
	--exclude=./fuel/app/config/db.php --exclude=./fuel/app/config/crypt.php \
	--exclude='./fuel/app/cache/*' --exclude='./fuel/app/logs/*' --exclude='./fuel/app/tmp/*' \
	--exclude='.DS_Store' \
	. | tar xf - -C "$STAGE/$NAME"

# Writable runtime folders must exist in the package
mkdir -p "$STAGE/$NAME/fuel/app/"{cache,logs,tmp}

mkdir -p "$OUT"
rm -f "$OUT/$NAME.tar.gz" "$OUT/$NAME.zip"
(cd "$STAGE" && tar czf "$OUT/$NAME.tar.gz" "$NAME" && zip -qr "$OUT/$NAME.zip" "$NAME")
(cd "$OUT" && { command -v sha256sum >/dev/null && sha256sum "$NAME.tar.gz" "$NAME.zip" || shasum -a 256 "$NAME.tar.gz" "$NAME.zip"; } > SHA256SUMS.txt)

ls -lh "$OUT"
