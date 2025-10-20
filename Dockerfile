# Dockerfile final usando a imagem oficial do PHP com Apache

# Etapa 1: Instala as dependências do Composer
FROM composer:2.7 AS vendor

WORKDIR /var/www/html
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Imagem final da aplicação
# Usamos a imagem oficial do PHP 8.2 com o servidor Apache.
FROM php:8.2-apache

# Instala dependências do sistema e extensões do PHP para o PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita o módulo 'rewrite' do Apache para as URLs amigáveis do Laravel
RUN a2enmod rewrite

# Copia o código da aplicação e as dependências
COPY . /var/www/html
COPY --from=vendor /var/www/html/vendor/ /var/www/html/vendor/

# Define as permissões corretas para o usuário do Apache (www-data)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# O comando de inicialização já está embutido na imagem 'php:apache', não precisamos de um start.sh
