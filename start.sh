#!/usr/bin/env bash
# Script de inicialização com otimização de cache

set -e
cd /var/www/html

# Copia o .env.example para .env se o arquivo .env ainda não existir.
if [ ! -f .env ]; then
    cp .env.example .env
fi

echo "Running Composer Install..."
composer install --optimize-autoloader

# --- AQUI ESTÁ A CORREÇÃO ---
# Limpa todos os caches antigos e cria novos caches otimizados para produção.
# Isso força o Laravel a "reencontrar" todos os seus arquivos de view.
echo "Optimizing application..."
php artisan optimize
# --- FIM DA CORREÇÃO ---

echo "Running database migrations..."
# php artisan migrate --force

echo "Running seeders..."
# Lembre-se que este seeder não deve usar Faker em produção
# #php artisan db:seed --force

echo "Starting Apache server..."
exec apache2-foreground
