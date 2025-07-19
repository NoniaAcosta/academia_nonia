# API - Gestión Académica 
## Descripción de la API

Esta Api Contiene los endpoints para la Gestión Académica que incluye básicamente:
-Gestión de Cursos.
-Gestón de Alumnos.
-Gestión de Inscripciones

## Resumen Tecnologías utilizadas

    1. php: ^8.1
        Versión mínima de PHP requerida. PHP 8.1 
    2. Laravel/framework: ^10.10
        El núcleo del framework Laravel. Laravel 10 
    3. Laravel/sanctum: ^3.3
        Provee autenticación basada en tokens


## Documentación

Los endpoints están documentados usando Scribe:
En la siguiente ruta: http://127.0.0.1:8000/docs


## Pasos para la instalación del proyecto

    1. Clona el repositorio
            git clone https://github.com/NoniaAcosta/academia_nonia.git

    2. Instalar dependencias PHP (Laravel)
            composer install

    3. Copiar el archivo de entorno
            cp .env.example .env

    4. Crear BD en mysql
            CREATE DATABASE gestion_academia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    5. Generar clave de la app
            php artisan key:generate
    
    6. Configura la base de datos en el archivo .env

    7. Ejecuta migraciones y seeders 
            php artisan migrate --seed

    8. Levanta el servidor local
            php artisan serve


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
