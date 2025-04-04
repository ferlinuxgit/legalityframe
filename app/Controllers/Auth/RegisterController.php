<?php

namespace App\Controllers\Auth;

// use App\Controllers\BaseController; // Incorrecto?
use App\Core\Controller as BaseController; // Probable ubicación correcta
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Validators\RegisterValidator;
use App\Services\MailService;
use App\Models\User;
use App\Models\VerificationToken;
use App\Core\Session; // Estático
use App\Core\CSRF;    // Estático
// Eliminar helpers no usados si no se implementan fábricas
// use App\Helpers\Validation\ValidatorFactory; 
// use App\Helpers\Mail\MailFactory;

/**
 * Controlador para el registro de usuarios
 * 
 * @package App\Controllers\Auth
 */
class RegisterController extends BaseController
{
    // Eliminar constructor e instancias si usamos llamadas estáticas
    /* 
    private View $view;
    private Response $response;
    private Session $session;

    public function __construct()
    {
        $this->view = new View();
        $this->response = new Response();
        $this->session = new Session();
    }
    */

    /**
     * Mostrar el formulario de registro
     * 
     * @return mixed La respuesta HTML
     */
    public function showRegistrationForm()
    {
        // Usar View::make() para renderizar
        // Asumir que BaseController o el Router manejan el echo/output
        return View::make('auth/register'); 
    }
    
    /**
     * Procesar la solicitud de registro
     * 
     * @param Request $request Datos de la solicitud
     * @return mixed La redirección
     */
    public function register(Request $request)
    {
        // 1. Validar CSRF token
        $token = $request->post(CSRF::getTokenName()); // Obtener token del POST
        if (!CSRF::validateToken($token)) {
            Session::setFlash('error', __('auth.invalid_csrf_token')); // Usar setFlash
            return Response::redirect('/register');
        }

        // 2. Obtener y validar datos
        $data = [
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'password_confirmation' => $request->post('password_confirmation'),
        ];
        
        $validator = new RegisterValidator($data); 

        if (!$validator->validate()) { 
            Session::setFlash('errors', $validator->getErrors()); // Usar setFlash
            Session::setFlash('old', $data); // Usar setFlash
            return Response::redirect('/register');
        }

        // 3. Crear usuario
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // El hash se hace en el modelo User::create
        ];
        $user = User::create($userData); // Devuelve User object o false

        if (!$user) {
             Session::setFlash('error', __('auth.registration_failed')); // Usar setFlash
             Session::setFlash('old', $data);
             return Response::redirect('/register');
        }

        // 4. Generar token de verificación
        // Usar VerificationToken::create($userId) que devuelve objeto o false
        $tokenData = VerificationToken::create($user->getId()); 

        if (!$tokenData) {
             Session::setFlash('error', __('auth.token_generation_failed')); // Usar setFlash
             // No eliminar usuario, permitir reintentar verificación?
             Session::setFlash('old', $data); 
             return Response::redirect('/register');
        }

        // 5. Enviar email de verificación
        $mailService = new MailService();
        $verificationLink = url('/verify-email/' . $tokenData->getToken()); // Usar helper url()
        $subject = __('auth.verify_email_subject');
        
        // Usar View::make()
        $body = View::make('auth/verification_email', [ 
            'name' => $user->getName(), // Usar getter del objeto User
            'verificationLink' => $verificationLink
        ]); 
        // No necesitamos el tercer argumento false con View::make

        if (!$mailService->send($user->getEmail(), $subject, $body)) { // Usar getter
             Session::setFlash('error', __('auth.verification_mail_failed')); // Usar setFlash
             // Mantener usuario y token, notificar para reintento?
             return Response::redirect('/register');
        }

        // 6. Redirigir con mensaje de éxito
        Session::setFlash('success', __('auth.registration_success_verify_email')); // Usar setFlash
        return Response::redirect('/verify-notice');
    }
    
    /**
     * Mostrar página de notificación de verificación de email
     * 
     * @return mixed La respuesta HTML
     */
    public function showVerificationNotice()
    {
        // Usar View::make()
        return View::make('auth/verification_notice');
    }
} 