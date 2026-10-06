# Nombre del proyecto

Descripción breve del proyecto y de su objetivo.

## 📋 Requisitos

Antes de instalar el proyecto, asegúrate de tener instalado:

* PHP 8.x o superior
* Composer
* MySQL 8.x o superior
* Node.js y npm
* Git
* Laravel (opcional, si se utilizará globalmente)

Puedes comprobar las versiones con:

```bash
php -v
composer -V
mysql --version
node -v
npm -v
git --version
```

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/KevinMarquezG/AplicacionesWebInteractivas_MGK.git
```

Entrar al directorio:

```bash
cd AplicacionesWebInteractivas_MGK.git
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar el archivo `.env`

Crear una copia del archivo `.env.example`:

```bash
cp .env.example .env
```

En Windows también puedes utilizar:

```bash
copy .env.example .env
```

Después, configurar las credenciales de la base de datos en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mis_recetas_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 6. Crear la base de datos

Crear en MySQL una base de datos con el mismo nombre especificado en `.env`.

Por ejemplo:

```sql
CREATE DATABASE mis_recetas_db;
```

### 7. Ejecutar las migraciones

```bash
php artisan migrate
```

Si el proyecto incluye datos iniciales:

```bash
php artisan migrate --seed
```

### 8. Compilar los recursos

```bash
npm run build
```

Durante el desarrollo también puedes utilizar:

```bash
npm run dev
```

### 9. Iniciar el servidor

```bash
php artisan serve
```

El proyecto estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

## 🗄️ Base de datos

El proyecto utiliza:

* **Sistema gestor:** MySQL
* **Puerto:** 3306
* **Base de datos:** nombre_base_datos

Si el proyecto requiere un script SQL adicional, se puede encontrar en:

```text
/database
```

o en la ubicación correspondiente dentro del repositorio.

## 📁 Estructura del proyecto

```text
AplicacionesWebInteractivas/Parcial2/Tarea2-Recetas
├── app/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## ▶️ Ejecución en desarrollo

Para iniciar el proyecto:

```bash
php artisan serve
```

En otra terminal:

```bash
npm run dev
```

## 🧪 Pruebas

Para ejecutar las pruebas del proyecto:

```bash
php artisan test
```

## 👥 Equipo

* Nombre del integrante 1
* Nombre del integrante 2
* Nombre del integrante 3

## 📄 Licencia

Este proyecto fue desarrollado con fines académicos.
