# --- Dockerfile para Laravel no Render (Versão Final com Driver PGSQL para Debian) ---

# Etapa 1: Build - Instala as dependências do Composer (inalterada)
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Aplicação Final
FROM dunglas/frankenphp:1-php8.2

# --- AQUI ESTÁ A CORREÇÃO ---
# Instala a extensão do PHP para PostgreSQL (pdo_pgsql) usando apt-get para Debian
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false libpq-dev \
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
