# Despliegue con Docker en Render

Este proyecto está configurado para desplegarse usando Docker en Render. Aquí tienes las instrucciones completas.

## 📋 Archivos de Configuración Incluidos

- `Dockerfile` - Configuración principal del contenedor
- `docker-compose.yml` - Para desarrollo local
- `.dockerignore` - Optimización del build
- `docker/nginx.conf` - Configuración del servidor web
- `docker/supervisord.conf` - Gestión de procesos
- `docker/entrypoint.sh` - Script de inicialización

## 🚀 Despliegue en Render

### 1. Preparar el Repositorio

1. Asegúrate de que todos los archivos estén en tu repositorio Git
2. Haz commit y push de los cambios:

```bash
git add .
git commit -m "Add Docker configuration for Render deployment"
git push origin main
```

### 2. Crear Servicio en Render

1. Ve a [render.com](https://render.com) y inicia sesión
2. Haz clic en "New +" → "Web Service"
3. Conecta tu repositorio de GitHub/GitLab
4. Configura el servicio:

   **Configuración Básica:**
   - **Name**: `ctrlfood-app`
   - **Environment**: `Docker`
   - **Region**: Elige la más cercana a tus usuarios
   - **Branch**: `main` (o tu rama principal)

   **Build & Deploy:**
   - **Dockerfile Path**: `./Dockerfile` (por defecto)
   - **Docker Context**: `.` (directorio raíz)

### 3. Crear Base de Datos PostgreSQL

1. En Render, crea un nuevo "PostgreSQL Database"
2. Configura:
   - **Name**: `ctrlfood-db`
   - **Database Name**: `ctrlfood`
   - **User**: `ctrlfood_user`
   - **Region**: La misma que tu web service

3. Anota las credenciales que Render te proporcione

### 4. Configurar Variables de Entorno

En la configuración de tu Web Service, añade estas variables:

```env
# Aplicación
APP_NAME=CtrlFood Software
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:[generar-con-php-artisan-key:generate]
APP_URL=https://tu-app.onrender.com

# Base de Datos (usar credenciales de Render)
DB_CONNECTION=pgsql
DB_HOST=[internal-host-de-render]
DB_PORT=5432
DB_DATABASE=ctrlfood
DB_USERNAME=[usuario-de-render]
DB_PASSWORD=[password-de-render]

# Configuración de Docker
RUN_MIGRATIONS=true
RUN_SEEDERS=false

# Logs
LOG_CHANNEL=stderr
LOG_LEVEL=info

# Cache y Sesiones
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue (opcional)
QUEUE_CONNECTION=sync

# Mail (configurar según necesites)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="CtrlFood Software"
```

### 5. Configurar Health Check (Opcional)

En la configuración avanzada de Render:
- **Health Check Path**: `/`
- **Health Check Timeout**: `30` segundos

### 6. Desplegar

1. Haz clic en "Create Web Service"
2. Render automáticamente:
   - Clonará tu repositorio
   - Construirá la imagen Docker
   - Ejecutará las migraciones
   - Iniciará la aplicación

## 🧪 Desarrollo Local con Docker

### Usando Docker Compose

```bash
# Construir y ejecutar
docker-compose up --build

# En segundo plano
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Parar servicios
docker-compose down
```

### Usando Docker directamente

```bash
# Construir imagen
docker build -t ctrlfood-app .

# Ejecutar contenedor
docker run -p 8080:80 \
  -e APP_KEY=base64:tu-app-key \
  -e DB_CONNECTION=sqlite \
  -e DB_DATABASE=/var/www/database/database.sqlite \
  ctrlfood-app
```

## 🔧 Comandos Útiles

### Generar APP_KEY

```bash
# Localmente
php artisan key:generate --show

# En contenedor
docker exec -it container_name php artisan key:generate --show
```

### Ejecutar Migraciones Manualmente

```bash
# En Render (usando el shell)
php artisan migrate --force

# Ejecutar seeders
php artisan db:seed --force
```

### Limpiar Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## 📁 Estructura de Archivos Docker

```
├── Dockerfile                 # Configuración principal
├── docker-compose.yml         # Para desarrollo local
├── .dockerignore             # Archivos a excluir
└── docker/
    ├── nginx.conf            # Configuración Nginx
    ├── supervisord.conf      # Gestión de procesos
    └── entrypoint.sh         # Script de inicialización
```

## 🔍 Troubleshooting

### Error de Permisos

```bash
# Verificar permisos en el contenedor
docker exec -it container_name ls -la /var/www/storage

# Corregir permisos
docker exec -it container_name chown -R www:www /var/www/storage
docker exec -it container_name chmod -R 755 /var/www/storage
```

### Error de Base de Datos

1. Verifica las credenciales en las variables de entorno
2. Asegúrate de que la base de datos esté en la misma región
3. Revisa los logs: `docker logs container_name`

### Error de APP_KEY

```bash
# Generar nueva clave
php artisan key:generate --force

# O establecer manualmente en variables de entorno
APP_KEY=base64:tu-clave-base64-aqui
```

## 🌐 URLs Importantes

Después del despliegue:
- **Aplicación**: `https://tu-app.onrender.com`
- **Panel de Render**: `https://dashboard.render.com`
- **Logs**: Disponibles en el dashboard de Render

## 📝 Notas Importantes

1. **Almacenamiento**: Render tiene almacenamiento efímero. Para archivos persistentes, considera usar AWS S3
2. **Base de Datos**: Usa PostgreSQL en producción (incluido en la configuración)
3. **SSL**: Render proporciona SSL automáticamente
4. **Escalabilidad**: Puedes escalar verticalmente desde el dashboard
5. **Backups**: Configura backups automáticos de la base de datos

¡Tu aplicación Laravel estará lista para producción con esta configuración! 🎉