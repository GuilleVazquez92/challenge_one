# Proyecto Challenge One API

## Requisitos
Para ejecutar este proyecto necesitas:
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Laravel** 12
- **Docker** y **Docker Compose**
- **MySQL** 8 (se usa dentro del contenedor Docker)
- **Postman** (para probar la API con la colección incluida)

## Instalación
Este proyecto está configurado para ejecutarse con Docker y Laravel. Sigue estos pasos:

### 1. Clonar el repositorio
```sh
git clone git@github.com:GuilleVazquez92/challenge_one.git
cd challenge_one
```

### 2. Dar permisos de ejecución al script de instalación
```sh
chmod +x install.sh
```

### 3. Ejecutar el script de instalación
```sh
./install.sh
```
Este script realizará las siguientes acciones:
- Copiará el archivo `.env.example` a `.env`.
- Levantará los contenedores de Docker (backend y base de datos).
- Instalará las dependencias de Laravel con Composer.
- Generará la clave de aplicación de Laravel.
- Ejecutará las migraciones y seeders.
- Iniciará el servidor de desarrollo de Laravel en `http://127.0.0.1:8000`.

## Importación de la colección de Postman
Para probar la API con Postman:
1. Abre Postman.
2. Haz clic en **Importar**.
3. Selecciona el archivo `postman_collection.json` incluido en este repositorio.
4. Configura la variable `base_url` con `http://127.0.0.1:8000`.
5. Usa la colección para realizar peticiones a la API.

## Herramientas y versiones utilizadas
- **PHP:** 8.2
- **Laravel:** 12
- **MySQL:** 8 (contenedor Docker)
- **Docker:** Última versión estable
- **Postman:** Última versión estable

## Notas adicionales


