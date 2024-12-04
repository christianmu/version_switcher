#!/bin/bash

WP_PATH="$1"
WP_VERSION="$2"
WP_LOCALE="$3"

if [ -z "$WP_PATH" ] || [ -z "$WP_VERSION" ] || [ -z "$WP_LOCALE" ]; then
    echo "Fehler: Pfad, Version und Sprache müssen angegeben werden."
    exit 1
fi

cd "$WP_PATH" || { echo "Fehler: Verzeichnis $WP_PATH existiert nicht."; exit 1; }

BACKUP_DIR="backup-$(date +%Y%m%d%H%M%S)"
mkdir -p "$BACKUP_DIR"
wp db export "$BACKUP_DIR/backup.sql"
cp -r wp-config.php wp-content "$BACKUP_DIR"
tar -czf "$BACKUP_DIR.tar.gz" "$BACKUP_DIR"
rm -rf "$BACKUP_DIR"

rm -rf wp-admin wp-includes
find . -maxdepth 1 -type f -name "*.php" ! -name "wp-config.php" -delete

wp core download --version="$WP_VERSION" --locale="$WP_LOCALE" --force
wp core update-db
wp core version --extra
