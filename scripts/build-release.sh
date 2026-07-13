#!/usr/bin/env bash
#
# Builds a self-contained release zip of the Tahbisan app for shared hosting.
# The result already has vendor/ and public/build/ inside it, so on the
# hosting account the only step left is to extract it (npm/composer are
# NOT required there).
#
# Run this from a clean, committed working tree — it packages `git HEAD`,
# not your uncommitted changes.
#
# Usage: scripts/build-release.sh [admin-password]

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ADMIN_PASSWORD="${1:-admin123}"
BUILD_DIR="$(mktemp -d)"
DIST_ZIP="$ROOT_DIR/tahbisan-shared-hosting.zip"

echo "==> Exporting tracked files from git HEAD"
cd "$ROOT_DIR"
git archive HEAD | (mkdir -p "$BUILD_DIR" && tar -x -C "$BUILD_DIR")
rm -rf "$BUILD_DIR/tests" "$BUILD_DIR/.github" "$BUILD_DIR/phpunit.xml"

cd "$BUILD_DIR"

echo "==> Installing PHP dependencies (production only)"
composer install --no-dev --optimize-autoloader --no-interaction --quiet

echo "==> Trimming non-runtime files from vendor/ (git-source installs pull full repos)"
find vendor -depth -type d \( \
    -name '.git' -o -iname 'tests' -o -iname 'test' -o -iname 'Tests' \
    -o -iname 'docs' -o -iname '.github' -o -iname 'examples' -o -iname 'tools' \
    -o -iname 'benchmarks' -o -iname '.phpstan' -o -iname '.phpunit.cache' \
\) -prune -exec rm -rf {} +
composer dump-autoload --optimize --no-dev --no-interaction --quiet

echo "==> Installing and building front-end assets"
npm install --no-audit --no-fund --silent
npm run build --silent
rm -rf node_modules

echo "==> Preparing .env"
cp .env.example .env
php artisan key:generate --force --no-interaction >/dev/null

echo "==> Creating and migrating the SQLite database"
mkdir -p database
touch database/database.sqlite
php artisan migrate --force --no-interaction
TAHBISAN_ADMIN_PASSWORD="$ADMIN_PASSWORD" php artisan db:seed --class=AdminSeeder --force --no-interaction

echo "==> Setting storage/bootstrap-cache/database/uploads write permissions"
chmod -R 775 storage bootstrap/cache
chmod 775 database
chmod 664 database/database.sqlite
chmod -R 775 public/uploads

echo "==> Zipping release"
rm -f "$DIST_ZIP"
zip -r -q "$DIST_ZIP" .

echo "==> Done: $DIST_ZIP"
echo "    Default admin login password: $ADMIN_PASSWORD (change it after first login — see README)"

rm -rf "$BUILD_DIR"
