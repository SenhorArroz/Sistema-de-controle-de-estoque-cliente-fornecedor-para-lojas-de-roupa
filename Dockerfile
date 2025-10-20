# --- Dockerfile para Laravel no Render (Versão Definitiva) ---

# Etapa 1: Build - Instala as dependências do Composer
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Aplicação Final
FROM dunglas/frankenphp:1-php8.2

# --- AQUI ESTÁ A CORREÇÃO FINAL ---
# Instala tanto a biblioteca de execução (libpq5) quanto a de desenvolvimento (libpq-dev),
# e depois remove apenas a de desenvolvimento.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq5 \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get purge -y --auto-remove libpq-dev \
    && rm -rf /var/lib/apt/lists/*
# --- FIM DA CORREÇÃO ---

# Copia as dependências da etapa de build
COPY --from=vendor /app/vendor/ /app/vendor/
# Copia o resto do código da sua aplicação
COPY . /app

# Define o proprietário dos arquivos para o usuário do servidor web
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Define a porta que o Render usará
EXPOSE 80
