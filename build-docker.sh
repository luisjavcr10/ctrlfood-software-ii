#!/bin/bash

# Script para construir la imagen Docker de CtrlFood
# Uso: ./build-docker.sh [nombre-imagen]

set -e

IMAGE_NAME=${1:-ctrlfood-app}

echo "🚀 Construyendo imagen Docker para CtrlFood..."
echo "📦 Nombre de imagen: $IMAGE_NAME"
echo ""

# Verificar que Docker esté ejecutándose
if ! docker info > /dev/null 2>&1; then
    echo "❌ Error: Docker no está ejecutándose"
    echo "   Por favor, inicia Docker Desktop y vuelve a intentar"
    exit 1
fi

# Generar dependencias si no existen
echo "📋 Verificando dependencias..."
if [ ! -f "composer.lock" ]; then
    echo "   Generando composer.lock..."
    composer install --no-dev
fi

if [ ! -f "package-lock.json" ]; then
    echo "   Generando package-lock.json..."
    npm install
fi

# Construir imagen
echo "🔨 Construyendo imagen Docker..."
docker build -t "$IMAGE_NAME" .

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ ¡Imagen construida exitosamente!"
    echo "📋 Información de la imagen:"
    docker images "$IMAGE_NAME" --format "table {{.Repository}}\t{{.Tag}}\t{{.Size}}\t{{.CreatedAt}}"
    echo ""
    echo "🚀 Para ejecutar la imagen:"
    echo "   docker run -p 8080:80 -e APP_KEY=\"base64:tu-app-key\" $IMAGE_NAME"
    echo ""
    echo "📖 Para más información, consulta DOCKER_DEPLOY.md"
else
    echo "❌ Error al construir la imagen"
    exit 1
fi