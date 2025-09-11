#!/bin/bash

# Script para configurar nginx con el puerto dinámico de Render
# Este script reemplaza la variable PORT en la configuración de nginx

# Puerto por defecto si no se especifica PORT
PORT=${PORT:-80}

echo "Configurando nginx para usar puerto: $PORT"

# Crear configuración principal de nginx
cat > /etc/nginx/nginx.conf << EOF
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
    worker_connections 768;
}

http {
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;

    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;
    gzip_disable "MSIE [1-6]";

    server {
        listen $PORT;
        server_name localhost;
        root /var/www/public;
        index index.php index.html index.htm;

        # Security headers
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-XSS-Protection "1; mode=block" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header Referrer-Policy "no-referrer-when-downgrade" always;
        add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

        # Handle Laravel routes
        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        # Handle PHP files
        location ~ \.php\$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
            include fastcgi_params;
            fastcgi_hide_header X-Powered-By;
        }

        # Deny access to hidden files
        location ~ /\. {
            deny all;
        }

        # Cache static assets
        location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)\$ {
            expires 1y;
            add_header Cache-Control "public, max-age=31536000, immutable";
        }

        # Deny access to sensitive files
        location ~ /\.(env|git|svn) {
            deny all;
            return 404;
        }

        # Deny access to composer files
        location ~ /(composer\.(json|lock)|package\.json|yarn\.lock) {
            deny all;
            return 404;
        }

        # Client max body size (for file uploads)
        client_max_body_size 100M;

        # Timeouts
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }
}
EOF

echo "Configuración de nginx actualizada para puerto $PORT"