# Tarea 4: Configuración del sistema de rutas

## Descripción

Esta tarea implica la creación del sistema de enrutamiento de la aplicación, permitiendo la gestión de URLs amigables y la conexión entre las solicitudes del usuario y los controladores que las procesan.

## Componentes implementados

1. **Router.php**
   - Clase principal para el registro y despacho de rutas
   - Implementación de métodos para rutas GET, POST, PUT y DELETE
   - Sistema de parámetros dinámicos en rutas (ej: `/scan/{id}`)
   - Manejo de errores 404

2. **Routes.php**
   - Archivo de definición de rutas para todas las funcionalidades
   - Agrupación de rutas por secciones (públicas, autenticación, escaneo, etc.)

3. **Controlador base**
   - Implementación de clase `Controller` como base para todos los controladores
   - Métodos compartidos para renderizar vistas, redireccionar, responder con JSON, etc.

4. **Middleware**
   - Implementación de `MiddlewareInterface` para todos los middleware
   - Middleware de autenticación básico
   - Middleware de protección CSRF

5. **Archivos de configuración y entrada**
   - Configuración del punto de entrada (`public/index.php`)
   - Integración con App.php para inicialización del sistema
   - Configuración de archivos `.htaccess` para redirección al punto de entrada

## Estructura de directorios

```
app/
  Config/
    Router.php
    Routes.php
    App.php
  Controllers/
    Controller.php
    HomeController.php
  Middlewares/
    MiddlewareInterface.php
    AuthMiddleware.php
    CsrfMiddleware.php
public/
  index.php
  .htaccess
```

## Patrones de diseño utilizados

- **Singleton** para la instancia del Router
- **Chain of Responsibility** para el procesamiento de middleware
- **Front Controller** para centralizar el manejo de solicitudes
- **Strategy** para la ejecución de controladores y métodos

## Flujo de solicitud HTTP

1. La solicitud llega al servidor web
2. `.htaccess` redirige la solicitud a `public/index.php`
3. `index.php` inicializa la aplicación y configura el entorno
4. `App.php` configura los middleware globales y carga las rutas
5. `Router.php` analiza la solicitud y encuentra la ruta coincidente
6. Se ejecuta la cadena de middleware configurada
7. Se llama al controlador y método correspondiente
8. El controlador procesa la solicitud y genera la respuesta

## Uso del sistema de rutas

### Definición de rutas
```php
// En Routes.php
$router->get('/about', 'HomeController@about');
$router->post('/login', 'Auth\\LoginController@login');
$router->get('/scan/{id}', 'ScanController@show');
```

### Acceso a parámetros en controladores
```php
// En ScanController.php
public function show($id)
{
    $scan = $this->scanService->find($id);
    // ...
}
```

### Aplicación de middleware
```php
// En App.php
protected function registerMiddleware()
{
    $this->middleware[] = new \App\Middlewares\CsrfMiddleware();
}
```

## Rendimiento y optimización

- Implementación eficiente de expresiones regulares para coincidencia de rutas
- Almacenamiento en caché de rutas compiladas (a implementar en el futuro)
- Procesamiento mínimo para solicitudes a archivos estáticos

## Estado actual

Sistema de rutas completamente implementado y funcional, permitiendo la gestión de URLs amigables y la conexión apropiada entre las solicitudes del usuario y los controladores. Middleware para CSRF y autenticación básica implementados. 