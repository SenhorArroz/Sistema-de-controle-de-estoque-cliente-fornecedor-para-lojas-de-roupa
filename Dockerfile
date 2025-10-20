# --- Dockerfile para Laravel no Render ---

# Etapa 1: Build - Instala as dependências do Composer
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Aplicação Final
FROM dunglas/frankenphp:1-php8.2

# Instala a extensão do PHP para PostgreSQL
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq5 \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get purge -y --auto-remove libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Copia as dependências e o código da aplicação
COPY --from=vendor /app/vendor/ /app/vendor/
COPY . /app

# A LINHA 'chown' FOI REMOVIDA DAQUI

# Define a porta que o Render usará
EXPOSE 80
