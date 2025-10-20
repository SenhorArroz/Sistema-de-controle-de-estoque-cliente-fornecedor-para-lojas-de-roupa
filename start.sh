#!/usr/bin/env bash
# Script de inicialização para a aplicação Laravel no Render

set -e

# CORREÇÃO FINAL: Usando o usuário padrão de servidor web 'www-data'
chown -R www-data:www-data /app/storage /app/bootstrap/cache

echo "Running database migrations..."
php artisan migrate --force

echo "Starting FrankenPHP server..."
exec /usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
