#!/bin/bash
set -e

# Función para esperar a que la base de datos esté lista
wait_for_db() {
    echo "Esperando a que la base de datos esté lista..."
    # Usar timeout para evitar esperas infinitas
    timeout 60 bash -c 'until nc -z "$DB_HOST" "$DB_PORT"; do sleep 1; done' || {
        echo "Advertencia: No se pudo conectar a la base de datos después de 60 segundos"
        echo "Continuando con el inicio del servicio..."
    }
    echo "Verificación de base de datos completada!"
}

# Función para ejecutar comandos de Laravel
setup_laravel() {
    echo "Configurando Laravel..."
    
    # Generar clave de aplicación si no existe
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:your-app-key-here" ]; then
        echo "Generando clave de aplicación..."
        php artisan key:generate --force
    fi
    
    # Limpiar caches
    echo "Limpiando caches..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    
    # Crear enlace simbólico para storage
    echo "Creando enlace simbólico para storage..."
    php artisan storage:link || true
    
    # Ejecutar migraciones
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Ejecutando migraciones..."
        php artisan migrate --force
    fi
    
    # Ejecutar seeders
    if [ "$RUN_SEEDERS" = "true" ]; then
        echo "Ejecutando seeders..."
        php artisan db:seed --force
    fi
    
    # Optimizar para producción
    if [ "$APP_ENV" = "production" ]; then
        echo "Optimizando para producción..."
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    fi
    
    echo "Laravel configurado correctamente!"
}

# Función principal
main() {
    echo "Iniciando entrypoint..."
    
    # Configurar permisos de storage y bootstrap
    echo "Configurando permisos..."
    chown -R www:www /var/www/storage /var/www/bootstrap/cache
    chmod -R 777 /var/www/storage /var/www/bootstrap/cache
    
    # Configurar nginx con el puerto correcto
    echo "Configurando nginx para puerto: ${PORT:-80}"
    /usr/local/bin/configure-nginx.sh
    
    # Esperar a la base de datos si está configurada
    if [ -n "$DB_HOST" ] && [ -n "$DB_PORT" ]; then
        wait_for_db
    fi
    
    # Configurar Laravel
    setup_laravel
    
    # Ejecutar el comando pasado como argumento
    echo "Ejecutando comando: $@"
    exec "$@"
}

# Ejecutar función principal
main "$@"