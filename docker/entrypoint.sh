#!/bin/bash
set -e

echo "Waiting for Postgres at $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 1
done
echo "Postgres is up, continuing..."

# Setup .env, keys, migrations, seeding, caches
cp .env.example .env 2>/dev/null || true
php artisan key:generate --force
php artisan jwt:secret --force
php artisan migrate --seed --force
php artisan config:clear
php artisan route:clear
php artisan cache:clear

exec "$@"
