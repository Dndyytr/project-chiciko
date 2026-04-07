#!/usr/bin/env bash
set -e

cd /srv/project-chiciko

docker compose up -d --build

docker compose exec -T app composer install --no-dev --optimize-autoloader
docker compose exec -T app npm install
docker compose exec -T app npm run build
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache