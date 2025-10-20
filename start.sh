#!/usr/bin/env bash
# Script para iniciar a aplicação no Render

# Sai imediatamente se um comando falhar
set -o errexit

echo "Running database migrations..."
# Roda as migrações do Laravel
php artisan migrate --force

echo "Starting FrankenPHP server..."
# Inicia o servidor web. O 'exec' é importante para que o servidor
# receba os sinais de parada corretamente do Render.
# Este é o comando padrão da imagem Docker que sugeri.
exec /usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
