#!/usr/bin/env bash
set -e

cd /srv/project-chiciko

docker compose down
docker compose up -d --build

echo "Waiting MySQL container..."
until docker ps | grep chiciko_mysql | grep Up; do
  sleep 2
done

echo "Waiting MySQL ready..."
until docker exec chiciko_mysql mysqladmin ping -h "localhost" --silent; do
  echo "Still waiting MySQL..."
  sleep 2
done

echo "MySQL is ready 🚀"

docker exec project_chiciko composer install --no-dev --optimize-autoloader
docker exec project_chiciko php artisan config:clear
docker exec project_chiciko php artisan migrate --force
docker exec project_chiciko php artisan optimize