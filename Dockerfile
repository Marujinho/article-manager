FROM php:8.2-fpm

# Instalar dependências essenciais e netcat-openbsd
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    zip \
    unzip \
    git \
    libzip-dev \
    libonig-dev \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o código do projeto para o contêiner
COPY . .

# Configurar permissões
RUN mkdir -p storage/logs bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    touch storage/logs/laravel.log && \
    chmod -R 775 storage/logs/laravel.log && \
    chown -R www-data:www-data storage bootstrap/cache

# Expor a porta do PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
