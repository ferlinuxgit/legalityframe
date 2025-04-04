<?php

namespace App\Controllers\Auth;

use App\Core\Controller as BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\User;
use App\Models\VerificationToken;
use App\Core\Session;
use App\Services\MailService;
use App\Config\Constants;
use App\Core\CSRF;

/**
 * Controlador para la verificación de email
 * 
 * @package App\Controllers\Auth
 */
class VerificationController extends BaseController
{
    /**
     * Verificar el token de email
     * 
     * @param string $token Token de verificación
     * @return mixed Redirección
     */
    public function verify(string $token)
    {
        $tokenModel = new VerificationToken();
        $tokenData = VerificationToken::findByToken($token);

        if (!$tokenData) {
            Session::setFlash('error', __('auth.invalid_or_expired_token'));
            return Response::redirect('/login');
        }

        $user = User::find($tokenData->getUserId());

        if (!$user) {
            Session::setFlash('error', __('auth.user_not_found'));
            $tokenData->delete();
            return Response::redirect('/register');
        }

        $user->update([
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status' => USER_STATUS_ACTIVE
        ]);

        $tokenData->delete();

        Session::setFlash('success', __('auth.email_verified_successfully'));
        return Response::redirect('/login');
    }
    
    /**
     * Reenviar el email de verificación
     * 
     * @param Request $request
     * @return mixed Redirección
     */
    public function resend(Request $request)
    {
        $token = $request->post(CSRF::getTokenName());
        if (!CSRF::validateToken($token)) {
            Session::setFlash('error', __('auth.invalid_csrf_token'));
            return Response::redirect('/verify-notice');
        }

        $email = $request->post('email');
        if (!$email) {
            Session::setFlash('error', __('auth.email_required_for_resend'));
            return Response::redirect('/verify-notice');
        }

        $user = User::findByEmail($email);

        if (!$user) {
            Session::setFlash('error', __('auth.user_not_found'));
            return Response::redirect('/verify-notice');
        }

        if ($user->hasVerifiedEmail()) {
            Session::setFlash('success', __('auth.email_already_verified'));
            return Response::redirect('/login');
        }

        $newTokenData = VerificationToken::create($user->getId());

        if (!$newTokenData) {
             Session::setFlash('error', __('auth.token_generation_failed'));
             return Response::redirect('/verify-notice');
        }
        
        $mailService = new MailService();
        $verificationLink = url('/verify-email/' . $newTokenData->getToken());
        $subject = __('auth.verify_email_subject');
        $body = View::make('auth/verification_email', [
            'name' => $user->getName(),
            'verificationLink' => $verificationLink
        ]);

        if (!$mailService->send($user->getEmail(), $subject, $body)) {
             Session::setFlash('error', __('auth.verification_mail_failed'));
             return Response::redirect('/verify-notice');
        }

        Session::setFlash('success', __('auth.verification_link_sent'));
        return Response::redirect('/verify-notice');
    }
} 