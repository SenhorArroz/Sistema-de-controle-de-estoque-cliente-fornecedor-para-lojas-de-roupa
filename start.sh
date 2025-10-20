#!/usr/bin/env bash
# Script de inicialização para a aplicação Laravel no Render

# O 'set -e' faz com que o script pare imediatamente se algum comando falhar.
# Se a migração falhar, o servidor não será iniciado, evitando que o site quebre.
set -e

echo "Running database migrations..."
# Roda as migrações do Laravel.
php artisan migrate --force

echo "Starting FrankenPHP server..."
# Inicia o servidor web. O 'exec' é importante para que o servidor
# receba os sinais de parada corretamente do Render.
exec /usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
