## Requisitos del sistema

### Sistema operativo

- Ubuntu 20.04+ (recomendado en WSL)

### Software requerido
- PHP 8.2+
- Composer 2.x
- Node.js 18+
- npm o pnpm
- MySQL / PostgreSQL
- Git
- OpenSSH

## Clonar el repositorio

Se recomienda usar SSH para evitar problemas de credenciales, donde {usuario} es el username de cada developer.

``` zsh
git clone git@github.com:{usuario}/campeonatos.git

cd repositorio
```

### Importante:
Trabaja siempre dentro del filesystem Linux
- Correcto: /home/usuario/proyectos
- Incorrecto: /mnt/c/...

## Configuración inicial del entorno
1. Instalar dependencias PHP
``` zsh
composer install
```

2. Instalar dependencias frontend

``` zsh
npm install && npm run build 
# o
pnpm install && pnpm run build
```

3. Archivo de entorno

``` zsh
cp .env.example .env
```

Configura en .env:

- Base de datos
- Nombre de la app
- Variables de entorno necesarias

Generar clave de aplicación

```zsh
php artisan key:generate
```

Ejecutar migraciones

``` zsh
php artisan migrate
```

Opcional (con seeders):

``` zsh
php artisan migrate --seed
```

Levantar el proyecto en desarrollo

``` zsh
php artisan serve
```