# Dockerfile simplificado - a instalação do Composer foi movida para o start.sh

# Usamos a imagem oficial do PHP 8.2 com o servidor Apache.
FROM php:8.2-apache

# Instala o Composer globalmente na imagem
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala dependências do sistema (git é necessário para o Composer) e a extensão do PHP para PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        git \
        zip \
        unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita módulos e configura o Apache para a pasta /public do Laravel
RUN a2enmod rewrite \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copia o código da aplicação
COPY . /var/www/html

# Copia o script de inicialização e dá permissão
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Define as permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Define o script de inicialização como o comando padrão
CMD ["/usr/local/bin/start.sh"]
