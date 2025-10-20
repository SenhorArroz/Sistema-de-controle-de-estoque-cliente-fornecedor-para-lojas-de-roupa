#!/usr/bin/env bash
# Script de inicialização final para Laravel no Render com Apache

set -e

# Navega para o diretório da aplicação
cd /var/www/html

# --- AQUI ESTÁ A CORREÇÃO ---
# Copia o .env.example para .env se o arquivo .env ainda não existir.
# Isso garante que os comandos artisan subsequentes encontrem o arquivo.
if [ ! -f .env ]; then
    cp .env.example .env
fi
# --- FIM DA CORREÇÃO ---

echo "Running Composer Install..."
# O comando key:generate que falhava será executado aqui pelo composer
composer install --optimize-autoloader

echo "Running database migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Starting Apache server..."
exec apache2-foreground
