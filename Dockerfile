# Usa uma imagem oficial do PHP com o servidor web Caddy (moderno e simples)
FROM dunglas/frankenphp:1-php8.3.16

# Define o diretório de trabalho dentro do contêiner
WORKDIR /app

# Copia os arquivos de dependência primeiro para aproveitar o cache do Docker
COPY composer.json composer.lock ./

# Instala o Composer e as dependências do PHP
RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress;

# Copia o resto do código da sua aplicação
COPY . .

# Roda os scripts do Composer que otimizam a aplicação
RUN set -eux; \
	composer dump-autoload --classmap-authoritative --no-dev; \
    # Limpa caches para produção
    php artisan optimize; \
    # Corrige permissões da pasta de armazenamento
    chown -R frankenphp:frankenphp storage

# Expõe a porta que a aplicação vai rodar
EXPOSE 80
