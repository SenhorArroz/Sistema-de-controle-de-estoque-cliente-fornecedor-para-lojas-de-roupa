#!/usr/bin/env bash
# Script de inicialização para a aplicação Laravel no Render

set -e

# AQUI ESTÁ A CORREÇÃO:
# Define o proprietário correto para as pastas de storage e cache no momento da execução.
chown -R frankenphp:frankenphp /app/storage /app/bootstrap/cache

echo "Running database migrations..."
# Roda as migrações do Laravel.
php artisan migrate --force

echo "Starting FrankenPHP server..."
# Inicia o servidor web.
exec /usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
