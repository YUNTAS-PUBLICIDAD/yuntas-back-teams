FROM php:8.2-cli-alpine

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias del sistema
RUN apk add --no-cache \
    git \
    npm \
    mysql-client \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql

WORKDIR /app

# Copiar toda la aplicación primero
COPY . .

# Instalar dependencias con Composer
RUN composer install --optimize-autoloader --no-dev

# Configurar permisos
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /app

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]