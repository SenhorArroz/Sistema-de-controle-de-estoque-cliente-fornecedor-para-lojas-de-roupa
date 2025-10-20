# --- Dockerfile para Laravel no Render (Versão Corrigida) ---

# Etapa 1: Build - Instala as dependências
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Aplicação Final
FROM dunglas/frankenphp:1-php8.2

COPY --from=vendor /app/vendor/ /app/vendor/
COPY . /app

# AQUI ESTÁ A CORREÇÃO:
# Damos permissão de escrita apenas nas pastas que o Laravel precisa,
# e usamos o nome de usuário correto: 'www-data'.
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Define a porta que o Render usará
EXPOSE 80
