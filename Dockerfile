# Dockerfile final e autocontido, sem necessidade de arquivos externos

# Etapa 1: Instala as dependências do Composer
FROM composer:2.7 AS vendor

WORKDIR /var/www/html
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader


# Etapa 2: Imagem final da aplicação
FROM php:8.2-apache

# Instala dependências e extensões do PHP para o PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- AQUI ESTÁ A NOVA ABORDAGEM ---
# Em vez de copiar um arquivo, editamos a configuração do Apache diretamente
RUN a2enmod rewrite \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
# --- FIM DA NOVA ABORDAGEM ---

# Copia o código da aplicação e as dependências
COPY . /var/www/html
COPY --from=vendor /var/www/html/vendor/ /var/www/html/vendor/

# Define as permissões corretas para o usuário do Apache (www-data)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
