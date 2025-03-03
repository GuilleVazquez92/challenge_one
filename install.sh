#!/bin/bash

# Asegúrate de que este archivo tenga permisos de ejecución:
# chmod +x install.sh

# 1. Copiar el archivo .env.example a .env si no existe
if [ ! -f .env ]; then
    echo "El archivo .env no existe, copiando .env.example a .env..."
    cp .env.example .env
else
    echo ".env ya existe."
fi

# 2. Levantar los contenedores de Docker
echo "Levantando contenedores de Docker..."
docker-compose up -d

# 3. Esperar hasta que la base de datos esté lista
echo "Esperando a que la base de datos esté lista..."
until docker-compose exec db mysqladmin --user=root --password=root --host=db --silent --wait=30 ping; do
  echo "Esperando por la base de datos..."  
  sleep 2
done

echo "La base de datos está lista."

# 4. Clonar las dependencias de Composer
echo "Instalando dependencias de Composer..."
composer install

# 5. Crear la clave de Laravel
echo "Generando la clave de Laravel..."
php artisan key:generate

# 6. Ejecutar las migraciones
echo "Ejecutando las migraciones..."
php artisan migrate --force

# 7. Ejecutar los seeders (opcional, si lo deseas)
echo "Ejecutando los seeders..."
php artisan db:seed

# 8. Levantar el servidor de desarrollo de Laravel
echo "Levantando el servidor de desarrollo de Laravel..."
php artisan serve 

