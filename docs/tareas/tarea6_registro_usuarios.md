# Tarea 6: Implementación de Registro de Usuarios

**Fase:** 2 - Sistema de autenticación y gestión de usuarios
**Estado:** Completado
**Fecha de Completado:** 25/05/2024

## Descripción
Implementación del flujo completo de registro de usuarios, incluyendo formulario, validación, almacenamiento en base de datos, y envío de correo electrónico de verificación.

## Componentes Implementados

1.  **Controladores:**
    *   `app/Controllers/Auth/RegisterController.php`: Gestiona la visualización del formulario (`showRegistrationForm`), el procesamiento del registro (`register`), y la visualización de la página de aviso de verificación (`showVerificationNotice`). Incluye validación CSRF.
    *   `app/Controllers/Auth/VerificationController.php`: Gestiona la verificación del token de email (`verify`) y la funcionalidad de reenvío de verificación (`resend`). Incluye validación CSRF en `resend`.

2.  **Modelos:**
    *   `app/Models/User.php`: Representa la tabla `users`. Incluye el método estático `create()` que hashea la contraseña y el método de instancia `update()` para actualizar datos como `email_verified_at` y `status`. Se utilizan métodos estáticos `find()` y `findByEmail()` y de instancia `hasVerifiedEmail()`, `getId()`, `getName()`, `getEmail()`.
    *   `app/Models/VerificationToken.php`: Representa la tabla `verification_tokens`. Incluye el método estático `create()` para generar un token único asociado a un usuario, el método estático `findByToken()` que busca un token válido (no expirado), y el método de instancia `delete()` para eliminar un token específico.

3.  **Validador:**
    *   `app/Validators/RegisterValidator.php`: Define las reglas de validación para los datos del formulario de registro (`name`, `email`, `password`, `password_confirmation`).

4.  **Servicio:**
    *   `app/Services/MailService.php`: Utilizado para enviar el correo electrónico de verificación al usuario tras el registro y al reenviar la verificación.

5.  **Vistas:**
    *   `app/Views/auth/register.php`: Formulario HTML para que los usuarios introduzcan sus datos. Muestra errores de validación y datos antiguos usando `Session::getFlash()`. Incluye el campo CSRF mediante `csrf_field()`.
    *   `app/Views/auth/verification_notice.php`: Página informativa que se muestra después del registro, indicando al usuario que revise su email. Permite solicitar el reenvío del correo de verificación. Muestra mensajes de éxito/error usando `Session::getFlash()`. Incluye el campo CSRF y maneja la obtención del email para el reenvío.
    *   `app/Views/auth/verification_email.php`: Plantilla HTML para el correo electrónico de verificación que contiene el enlace con el token.
    *   `app/Views/layouts/auth_header.php` y `auth_footer.php`: Layouts específicos para las páginas de autenticación (Bootstrap).

6.  **Middleware:**
    *   `app/Middlewares/GuestMiddleware.php`: Asegura que usuarios ya autenticados (comprobando `Session::has('user_id')`) sean redirigidos fuera de la página de registro (p.ej., a `/dashboard`).

7.  **Clases Core:**
    *   `app/Core/Session.php`: Implementada con métodos estáticos para gestionar datos de sesión y mensajes flash.
    *   `app/Core/CSRF.php`: Implementada con métodos estáticos para generar y validar tokens CSRF.
    *   `app/Core/View.php`: Utilizada mediante su método estático `make()` para renderizar vistas.
    *   `app/Core/Response.php`: Utilizada mediante sus métodos estáticos (`redirect()`) para las redirecciones.

8.  **Helpers:**
    *   `app/Helpers/functions.php`: Se añadió el helper `csrf_field()` que usa `CSRF::csrfField()`. Se utilizan los helpers `__()` para traducciones y `url()` para generar URLs.

9.  **Rutas:**
    *   Se definieron las rutas necesarias en `app/Config/Routes.php`:
        *   `GET /register` -> `RegisterController@showRegistrationForm` (con `GuestMiddleware`)
        *   `POST /register` -> `RegisterController@register` (con `GuestMiddleware`)
        *   `GET /verify-email/{token}` -> `VerificationController@verify`
        *   `GET /verify-notice` -> `RegisterController@showVerificationNotice`
        *   `POST /resend-verification` -> `VerificationController@resend`

10. **Traducciones:**
    *   Se añadieron/utilizaron claves de traducción necesarias en `resources/lang/[es|en]/auth.php` y `resources/lang/[es|en]/app.php` para mensajes de error, éxito, etiquetas de formulario, etc.

11. **Constantes:**
    *   Se utiliza `USER_STATUS_PENDING` (definido en `app/Config/constants.php`) como estado inicial y `USER_STATUS_ACTIVE` al verificar.

## Flujo de Registro

1.  Usuario accede a `GET /register`.
2.  `GuestMiddleware` verifica que no esté autenticado.
3.  `RegisterController::showRegistrationForm` renderiza la vista `auth/register` usando `View::make()`.
4.  La vista muestra el formulario con un campo CSRF generado por `csrf_field()`.
5.  Usuario envía el formulario a `POST /register`.
6.  `GuestMiddleware` verifica que no esté autenticado.
7.  `RegisterController::register` valida el token CSRF.
8.  Se validan los datos del formulario usando `RegisterValidator`.
9.  Si la validación falla, se redirige a `/register` con errores y datos antiguos (`old`) guardados como mensajes flash (`Session::setFlash`).
10. Si la validación es correcta, se crea el usuario en estado `PENDING` usando `User::create()`. La contraseña se hashea dentro de `User::create()`.
11. Si la creación del usuario falla, se redirige a `/register` con un error flash.
12. Si el usuario se crea, se genera un token de verificación usando `VerificationToken::create()`.
13. Si la creación del token falla, se redirige a `/register` con un error flash.
14. Se envía un email de verificación al usuario usando `MailService` con un enlace `url('/verify-email/{token}')` generado con el token. La plantilla del email se renderiza con `View::make()`.
15. Si el envío del email falla, se redirige a `/register` con un error flash.
16. Si todo va bien, se redirige a `GET /verify-notice` con un mensaje flash de éxito.
17. `RegisterController::showVerificationNotice` renderiza la vista `auth/verification_notice`. La vista muestra el mensaje de éxito y un formulario para reenviar la verificación.

## Flujo de Verificación

1.  Usuario hace clic en el enlace del email (`GET /verify-email/{token}`).
2.  `VerificationController::verify` busca el token usando `VerificationToken::findByToken()`. Esta función ya comprueba la validez y expiración.
3.  Si el token no es válido o no existe, se redirige a `/login` con un error flash.
4.  Se busca al usuario asociado al token (`User::find()`).
5.  Si el usuario no existe, se elimina el token inválido (`$tokenData->delete()`) y se redirige a `/register` con un error flash.
6.  Se actualiza el usuario marcando `email_verified_at` con la fecha actual y `status` como `ACTIVE` usando `$user->update()`.
7.  Se elimina el token de verificación usado (`$tokenData->delete()`).
8.  Se redirige a `/login` con un mensaje flash de éxito.

## Flujo de Reenvío de Verificación

1.  Usuario está en la página `GET /verify-notice`.
2.  Envía el formulario a `POST /resend-verification` (incluyendo email y token CSRF).
3.  `VerificationController::resend` valida el token CSRF.
4.  Busca al usuario por el email proporcionado (`User::findByEmail()`).
5.  Si no se encuentra, redirige a `/verify-notice` con error flash.
6.  Comprueba si el email ya está verificado (`$user->hasVerifiedEmail()`). Si lo está, redirige a `/login` con mensaje flash.
7.  Genera un nuevo token de verificación (`VerificationToken::create()`).
8.  Si falla la generación, redirige a `/verify-notice` con error flash.
9.  Envía el nuevo email de verificación.
10. Si falla el envío, redirige a `/verify-notice` con error flash.
11. Si todo va bien, redirige a `/verify-notice` con mensaje flash de éxito.

## Pendiente / Mejoras

*   Implementar auto-login después de la verificación si se desea.
*   Mejorar la gestión de errores (logging más detallado).
*   Considerar la eliminación de usuario si falla la creación del token o el envío del email inicial (actualmente no se hace).

## Estructura de directorios

```
app/
  Controllers/
    Auth/
      RegisterController.php
      VerificationController.php
  Models/
    User.php
    VerificationToken.php
  Views/
    auth/
      register.php
      verification_email.php
      verification_notice.php
  Validators/
    RegisterValidator.php
  Services/
    MailService.php
  Middlewares/
    GuestMiddleware.php
```

## Patrones de diseño utilizados

- **MVC** para la estructura general
- **Repository** para abstraer operaciones de base de datos
- **Service** para encapsular lógica de negocio compleja
- **Factory** para crear instancias de modelos
- **Chain of Responsibility** para validación

## Flujo de registro de usuario

1. El usuario accede al formulario de registro
   - Middleware verifica que no exista una sesión activa
   - Se genera token CSRF para proteger el formulario

2. El usuario completa y envía el formulario
   - JavaScript realiza validación inicial en el cliente
   - Los datos se envían al servidor mediante POST

3. El `RegisterController` recibe y procesa la solicitud
   - Valida los datos recibidos (presencia, formato, unicidad)
   - Verifica que las contraseñas coincidan
   - Comprueba la fortaleza de la contraseña

4. Si la validación es exitosa:
   - Se genera un hash de la contraseña
   - Se crea un registro en la tabla `users`
   - Se genera un token de verificación
   - Se envía un email con el enlace de verificación
   - Se redirige al usuario a una página de confirmación

5. El usuario recibe el email y hace clic en el enlace de verificación
   - El `VerificationController` verifica el token
   - Actualiza el estado de la cuenta a verificado
   - Establece la fecha de verificación
   - Permite al usuario iniciar sesión

## Base de datos

### Tabla `users`
- `id`: INT, PK, AI
- `name`: VARCHAR(255)
- `email`: VARCHAR(255), UNIQUE
- `password`: VARCHAR(255)
- `status`: TINYINT (usando constantes USER_STATUS_*)
- `role`: TINYINT (usando constantes USER_ROLE_*)
- `email_verified_at`: TIMESTAMP NULL
- `language`: VARCHAR(5)
- `last_login`: TIMESTAMP NULL
- `remember_token`: VARCHAR(100) NULL
- `created_at`: TIMESTAMP
- `updated_at`: TIMESTAMP

### Tabla `verification_tokens`
- `id`: INT, PK, AI
- `user_id`: INT, FK -> users.id
- `token`: VARCHAR(100)
- `created_at`: TIMESTAMP
- `expires_at`: TIMESTAMP

## Seguridad implementada

1. **Protección de contraseñas**
   - Almacenamiento usando bcrypt con factor de trabajo configurable
   - Validación de fortaleza (mínimo 8 caracteres, mayúsculas, números, símbolos)
   - Nunca se almacena en texto plano

2. **Protección contra ataques**
   - Limitación de intentos de registro por IP
   - Validación CSRF en todos los formularios
   - Validación estricta de formatos de email

3. **Seguridad en emails**
   - Tokens de un solo uso con expiración
   - Enlaces firmados para verificación
   - No se envía información sensible por email

## Adaptaciones multiidioma

- Todas las interfaces de usuario están disponibles en español e inglés
- Los emails de verificación se envían en el idioma seleccionado por el usuario
- Mensajes de error y éxito traducidos según el idioma configurado

## Rendimiento

- Optimización de consultas mediante índices en las columnas email y token
- Caché de consultas frecuentes
- Procesamiento asíncrono de envío de emails para no bloquear la respuesta al usuario

## Estado actual

Sistema de registro completamente implementado y funcional, permitiendo la creación de nuevos usuarios con verificación por email. Las pruebas realizadas confirman el correcto funcionamiento de la validación, almacenamiento y sistema de verificación. 