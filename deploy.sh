#!/usr/bin/env bash
set -e

cd /srv/project-chiciko

docker compose up -d --build

# Tunggu MySQL siap
until docker compose exec mysql mysqladmin ping -h "localhost" --silent; do
  echo "Waiting for MySQL..."
  sleep 2
done

docker compose exec -T app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache