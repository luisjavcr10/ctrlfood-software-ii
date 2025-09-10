#!/bin/bash

# Script para construir la imagen Docker de CtrlFood
# Este script automatiza la preparación y construcción de la imagen
# Versión actualizada con correcciones para Render

set -e

echo "🐳 Iniciando construcción de imagen Docker para CtrlFood..."

# Verificar que Docker esté ejecutándose
if ! docker info > /dev/null 2>&1; then
    echo "❌ Error: Docker no está ejecutándose. Por favor, inicia Docker Desktop."
    exit 1
fi

echo "✅ Docker está ejecutándose"

# Generar composer.lock si no existe
if [ ! -f "composer.lock" ]; then
    echo "📦 Generando composer.lock..."
    composer install --no-dev
else
    echo "✅ composer.lock ya existe"
fi

# Generar package-lock.json si no existe
if [ ! -f "package-lock.json" ]; then
    echo "📦 Generando package-lock.json..."
    npm install
else
    echo "✅ package-lock.json ya existe"
fi

# Construir la imagen Docker
echo "🔨 Construyendo imagen Docker..."
docker build -t ctrlfood-app .

echo "🎉 ¡Imagen Docker construida exitosamente!"
echo ""
echo "✨ Correcciones aplicadas:"
echo "  ✅ Instalado netcat para verificación de base de datos"
echo "  ✅ Configuración dinámica de puerto para Render"
echo "  ✅ Timeout en conexión a base de datos"
echo ""
echo "Para ejecutar el contenedor localmente:"
echo "  docker run -p 8080:80 --env-file .env ctrlfood-app"
echo ""
echo "Para desplegar en Render:"
echo "  1. Haz push de los cambios a tu repositorio Git"
echo "  2. Crea un nuevo Web Service en Render"
echo "  3. Conecta tu repositorio"
echo "  4. Configura las variables de entorno necesarias"
echo "  5. Render detectará automáticamente el puerto correcto"