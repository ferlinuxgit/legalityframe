<?php

/**
 * Definición de rutas de la aplicación
 */

use App\Config\Router;

// Obtener la instancia del router
$router = Router::getInstance();

// Rutas públicas
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/pricing', 'HomeController@pricing');
$router->get('/contact', 'HomeController@contact');
$router->post('/contact', 'HomeController@sendContact');
$router->get('/terms', 'HomeController@terms');
$router->get('/privacy', 'HomeController@privacy');

// Rutas de autenticación
$router->get('/login', 'Auth\\LoginController@showLoginForm');
$router->post('/login', 'Auth\\LoginController@login');
$router->get('/register', 'Auth\\RegisterController@showRegistrationForm');
$router->post('/register', 'Auth\\RegisterController@register');
$router->get('/verify-email/{token}', 'Auth\\VerificationController@verify');
$router->get('/verify-notice', 'Auth\\RegisterController@showVerificationNotice');
$router->post('/resend-verification', 'Auth\\VerificationController@resend');
$router->get('/forgot-password', 'Auth\\PasswordController@showForgotForm');
$router->post('/forgot-password', 'Auth\\PasswordController@sendResetLink');
$router->get('/reset-password/{token}', 'Auth\\PasswordController@showResetForm');
$router->post('/reset-password', 'Auth\\PasswordController@reset');
$router->post('/logout', 'Auth\\LoginController@logout');

// Rutas de escaneo
$router->get('/scan', 'ScanController@index');
$router->post('/scan', 'ScanController@create');
$router->get('/scan/{id}', 'ScanController@show');
$router->get('/scan/{id}/report', 'ReportController@show');
$router->get('/scan/{id}/technical', 'ReportController@technical');
$router->get('/scan/{id}/report/download', 'ReportController@download');

// Rutas para documentos legales
$router->get('/documents', 'DocumentController@index');
$router->get('/documents/generate', 'DocumentController@create');
$router->post('/documents/generate', 'DocumentController@store');
$router->get('/documents/{id}', 'DocumentController@show');
$router->get('/documents/{id}/download', 'DocumentController@download');
$router->get('/documents/{id}/preview', 'DocumentController@preview');

// Rutas para el panel de usuario
$router->get('/dashboard', 'UserController@dashboard');
$router->get('/profile', 'UserController@profile');
$router->post('/profile', 'UserController@updateProfile');
$router->get('/change-password', 'UserController@showChangePasswordForm');
$router->post('/change-password', 'UserController@changePassword');
$router->get('/history', 'UserController@history');
$router->get('/alerts', 'AlertController@index');

// Rutas para API
$router->post('/api/scan', 'API\\ScannerAPIController@scan');
$router->get('/api/scan/{id}', 'API\\ScannerAPIController@getResults');
$router->post('/api/documents/generate', 'API\\DocumentAPIController@generate');
$router->get('/api/websites/{domain}/cookies', 'API\\ScannerAPIController@getCookies');
$router->get('/api/user', 'Api\\UserController@current');

// Rutas para administración
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/scans', 'AdminController@scans');
$router->get('/admin/payments', 'AdminController@payments');

// Ruta para cambio de idioma
$router->get('/language/{locale}', 'LanguageController@changeLanguage'); 