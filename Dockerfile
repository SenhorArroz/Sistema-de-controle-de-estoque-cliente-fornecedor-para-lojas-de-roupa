# --- Dockerfile para Laravel no Render ---

# Etapa 1: Build - Instala as dependências
# Usamos uma imagem oficial do Composer para garantir a melhor compatibilidade.
FROM composer:2 AS vendor

WORKDIR /app
# Copia apenas o composer.json/lock para aproveitar o cache do Docker.
# Se você não mudar suas dependências, o Docker não precisará reinstalar tudo a cada deploy.
COPY database/ database/
COPY composer.json composer.lock ./

# Instala as dependências de produção.
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader

# Etapa 2: Aplicação Final
# Usamos uma imagem otimizada para PHP/Laravel em produção com o servidor FrankenPHP.
FROM dunglas/frankenphp:1-php8.2

# Copia as dependências que instalamos na etapa anterior
COPY --from=vendor /app/vendor/ /app/vendor/
# Copia o resto do código da sua aplicação
COPY . /app

# Define o proprietário dos arquivos para o usuário do servidor web
RUN chown -R frankenphp:frankenphp /app

# Define a porta que o Render usará
EXPOSE 80
