#!/usr/bin/env bash
# Script de inicialização final para Laravel no Render com Apache

# O 'set -e' faz com que o script pare imediatamente se um comando falhar.
set -e

echo "Running database migrations..."
# 1. Executa as migrações do banco de dados
php artisan migrate --force

echo "Running seeders..."
# 2. Executa os seeders.
# CUIDADO: Em produção, você talvez queira rodar apenas seeders específicos.
# Se for o caso, mude o comando para: php artisan db:seed --class=SeuSeederDeProducao --force
php artisan db:seed --force

echo "Starting Apache server..."
# 3. Inicia o servidor Apache em primeiro plano (este deve ser o último comando).
exec apache2-foreground
