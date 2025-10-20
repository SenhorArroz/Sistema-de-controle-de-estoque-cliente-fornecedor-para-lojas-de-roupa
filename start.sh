#!/usr/bin/env bash

set -e

# Navega para o diretório da aplicação
cd /var/www/html

echo "Running Composer Install..."
# Roda o composer install, INCLUINDO as dependências de dev
composer install --optimize-autoloader

echo "Running database migrations..."
php artisan migrate --force

echo "Running seeders..."
# Agora o seeder pode rodar, pois o Faker foi instalado
php artisan db:seed --force

echo "Starting Apache server..."
exec apache2-foreground
