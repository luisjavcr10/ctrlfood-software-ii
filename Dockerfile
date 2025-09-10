FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    supervisor \
    nginx

# Limpiar cache de apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario para Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos de dependencias primero (para aprovechar cache de Docker)
COPY composer.json composer.lock ./

# Instalar dependencias PHP
RUN composer install --no-scripts --no-autoloader --no-dev --prefer-dist

# Copiar el resto de archivos del proyecto
COPY . .

# Copiar y hacer ejecutable el entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Completar instalación de Composer
RUN composer dump-autoload --no-dev --optimize

# Nota: Los assets deben compilarse localmente antes del build
# Ejecutar: npm run production antes de hacer docker build

# Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Configurar Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Crear directorios necesarios
RUN mkdir -p /var/log/supervisor
RUN mkdir -p /run/php
RUN mkdir -p /var/www/storage/logs
RUN mkdir -p /var/www/storage/framework/cache
RUN mkdir -p /var/www/storage/framework/sessions
RUN mkdir -p /var/www/storage/framework/views

# Configurar permisos
RUN chown -R www:www /var/www
RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap/cache

# Variables de entorno por defecto
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV RUN_MIGRATIONS=true
ENV RUN_SEEDERS=false

# Exponer puerto
EXPOSE 80

# Establecer entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Comando de inicio
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]