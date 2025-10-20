# --- Dockerfile para Laravel no Render (Versão Final com Driver PGSQL) ---

# Etapa 1: Build - Instala as dependências do Composer
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Aplicação Final
FROM dunglas/frankenphp:1-php8.2

# --- AQUI ESTÁ A CORREÇÃO ---
# Instala a extensão do PHP para PostgreSQL (pdo_pgsql)
# A imagem é baseada em Alpine Linux, então usamos o gerenciador de pacotes 'apk'.
# Precisamos das bibliotecas de desenvolvimento do postgresql para compilar a extensão.
RUN apk add --no-cache $PHPIZE_DEPS postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apk del $PHPIZE_DEPS postgresql-dev
# --- FIM DA CORREÇÃO ---

# Copia as dependências da etapa de build
COPY --from=vendor /app/vendor/ /app/vendor/
# Copia o resto do código da sua aplicação
COPY . /app

# Define o proprietário dos arquivos para o usuário do servidor web
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Define a porta que o Render usará
EXPOSE 80
