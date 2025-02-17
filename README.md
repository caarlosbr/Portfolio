# Portfolio Manager

## Descripción

Este proyecto es una versión extendida de un sistema de "Portfolio" que utiliza bases de datos y sigue el patrón Modelo-Vista-Controlador (MVC). La aplicación permite la gestión de perfiles en el ámbito de nuevas tecnologías, con funcionalidades para invitados y usuarios registrados.

## Tecnologías Utilizadas

- **PHP**
- **Symfony (Mailer)**
- **MySQL**
- **Variables de entorno con vlucas/phpdotenv**

## Esquema de Base de Datos

Implementa un esquema relacional con claves foráneas, índices y restricciones semánticas.

Los datos como logros, tecnologías y habilidades se almacenan como listas separadas por comas.

## Funcionalidades

### Invitado

- Acceso a la parte pública del sistema.
- Buscador de perfiles en el ámbito de nuevas tecnologías.
- Registro de usuario con formulario de datos personales.
- Generación de un token de seguridad para la activación de la cuenta (válido por 24 horas).
- Envío de correo electrónico con enlace de activación.
- Acceso al formulario de login.

### Usuario Registrado

- Acceso al sistema solo si la cuenta está activada.
- Creación, edición y eliminación de su porfolio.
- Activación y desactivación de la visualización de elementos del porfolio.

## Configuración

1. Clonar el repositorio.
2. Instalar dependencias mediante Composer:

   ```sh
   composer install
   ```

3. Configurar variables de entorno en un archivo `.env`.
4. Ejecutar migraciones de la base de datos:

   ```sh
   php bin/console doctrine:migrations:migrate
   ```

5. Iniciar el servidor:

   ```sh
   php -S localhost:8000 -t public
   ```

## Seguridad

- Uso de `random_bytes(32)` para generar tokens seguros.
- Codificación de tokens con `base64_encode()` y `uniqid()`.
- Restricciones para acceso solo a usuarios autenticados.

## Dependencias

- **vlucas/phpdotenv** - Para gestionar variables de entorno.
- **symfony/mailer** - Para el envío de correos electrónicos.
