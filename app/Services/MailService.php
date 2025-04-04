<?php

namespace App\Services;

/**
 * Servicio de envío de correos electrónicos
 * 
 * @package App\Services
 */
class MailService
{
    /**
     * Remitente predeterminado
     * 
     * @var string
     */
    private $fromEmail;
    
    /**
     * Nombre del remitente predeterminado
     * 
     * @var string
     */
    private $fromName;
    
    /**
     * Instancia de PHPMailer
     * 
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $mailer;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Cargar configuración de correo
        $mailConfig = $GLOBALS['config']['mail'];
        
        $this->fromEmail = $mailConfig['from']['address'];
        $this->fromName = $mailConfig['from']['name'];
        
        // Inicializar PHPMailer
        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configurar según el driver
        switch ($mailConfig['driver']) {
            case 'smtp':
                $this->setupSmtp($mailConfig);
                break;
            case 'sendmail':
                $this->setupSendmail($mailConfig);
                break;
            default:
                $this->mailer->isMail();
        }
        
        // Configuración común
        $this->mailer->setFrom($this->fromEmail, $this->fromName);
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }
    
    /**
     * Configurar SMTP
     * 
     * @param array $config Configuración de correo
     */
    private function setupSmtp($config)
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['smtp']['host'];
        $this->mailer->Port = $config['smtp']['port'];
        
        if (!empty($config['smtp']['username'])) {
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $config['smtp']['username'];
            $this->mailer->Password = $config['smtp']['password'];
        }
        
        if ($config['smtp']['encryption'] !== null) {
            $this->mailer->SMTPSecure = $config['smtp']['encryption'];
        }
        
        if ($config['smtp']['debug']) {
            $this->mailer->SMTPDebug = 2;
        }
    }
    
    /**
     * Configurar Sendmail
     * 
     * @param array $config Configuración de correo
     */
    private function setupSendmail($config)
    {
        $this->mailer->isSendmail();
        
        if (!empty($config['sendmail_path'])) {
            $this->mailer->Sendmail = $config['sendmail_path'];
        }
    }
    
    /**
     * Enviar un correo electrónico
     * 
     * @param string|array $to Destinatario o array de destinatarios
     * @param string $subject Asunto
     * @param string $body Cuerpo del mensaje
     * @param array $attachments Archivos adjuntos
     * @return bool
     */
    public function send($to, $subject, $body, $attachments = [])
    {
        try {
            // Limpiar destinatarios y adjuntos previos
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();
            
            // Configurar destinatarios
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    if (is_array($recipient) && isset($recipient['email'])) {
                        $name = $recipient['name'] ?? '';
                        $this->mailer->addAddress($recipient['email'], $name);
                    } else {
                        $this->mailer->addAddress($recipient);
                    }
                }
            } else {
                $this->mailer->addAddress($to);
            }
            
            // Configurar asunto y cuerpo
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($body);
            
            // Añadir adjuntos
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (is_array($attachment) && isset($attachment['path'])) {
                        $name = $attachment['name'] ?? basename($attachment['path']);
                        $this->mailer->addAttachment($attachment['path'], $name);
                    } else {
                        $this->mailer->addAttachment($attachment);
                    }
                }
            }
            
            // Enviar correo
            return $this->mailer->send();
        } catch (\Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Enviar correo de verificación de cuenta
     * 
     * @param string $email Email del destinatario
     * @param string $name Nombre del destinatario
     * @param string $verificationUrl URL de verificación
     * @param string $language Idioma del correo
     * @return bool
     */
    public function sendVerificationEmail($email, $name, $verificationUrl, $language = 'es')
    {
        // Cargar vista del correo
        ob_start();
        $viewFile = BASE_PATH . "/app/Views/auth/verification_email.php";
        
        if (file_exists($viewFile)) {
            include $viewFile;
            $content = ob_get_clean();
        } else {
            ob_end_clean();
            // Plantilla alternativa básica si no existe la vista
            $content = $this->getBasicVerificationTemplate($name, $verificationUrl, $language);
        }
        
        // Determinar el asunto según el idioma
        $subject = ($language === 'es') 
            ? 'Verifica tu correo electrónico - LegalityFrame' 
            : 'Verify your email address - LegalityFrame';
        
        // Enviar correo
        return $this->send($email, $subject, $content);
    }
    
    /**
     * Obtener plantilla básica de verificación
     * 
     * @param string $name Nombre del destinatario
     * @param string $verificationUrl URL de verificación
     * @param string $language Idioma del correo
     * @return string
     */
    private function getBasicVerificationTemplate($name, $verificationUrl, $language = 'es')
    {
        if ($language === 'es') {
            return '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h2>Hola ' . htmlspecialchars($name) . ',</h2>
                    <p>Gracias por registrarte en LegalityFrame. Por favor, verifica tu correo electrónico haciendo clic en el siguiente enlace:</p>
                    <p><a href="' . $verificationUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4A90E2; color: white; text-decoration: none; border-radius: 4px;">Verificar mi correo</a></p>
                    <p>O copia y pega la siguiente URL en tu navegador:</p>
                    <p>' . $verificationUrl . '</p>
                    <p>Si no has creado esta cuenta, puedes ignorar este correo.</p>
                    <p>Saludos,<br>El equipo de LegalityFrame</p>
                </div>
            ';
        } else {
            return '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h2>Hello ' . htmlspecialchars($name) . ',</h2>
                    <p>Thank you for signing up at LegalityFrame. Please verify your email address by clicking the link below:</p>
                    <p><a href="' . $verificationUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4A90E2; color: white; text-decoration: none; border-radius: 4px;">Verify my email</a></p>
                    <p>Or copy and paste the following URL into your browser:</p>
                    <p>' . $verificationUrl . '</p>
                    <p>If you did not create this account, you can ignore this email.</p>
                    <p>Regards,<br>The LegalityFrame Team</p>
                </div>
            ';
        }
    }
    
    /**
     * Enviar correo de restablecimiento de contraseña
     * 
     * @param string $email Email del destinatario
     * @param string $name Nombre del destinatario
     * @param string $resetUrl URL de restablecimiento
     * @param string $language Idioma del correo
     * @return bool
     */
    public function sendPasswordResetEmail($email, $name, $resetUrl, $language = 'es')
    {
        // Cargar vista del correo
        ob_start();
        $viewFile = BASE_PATH . "/app/Views/auth/password_reset_email.php";
        
        if (file_exists($viewFile)) {
            include $viewFile;
            $content = ob_get_clean();
        } else {
            ob_end_clean();
            // Plantilla alternativa básica si no existe la vista
            $content = $this->getBasicPasswordResetTemplate($name, $resetUrl, $language);
        }
        
        // Determinar el asunto según el idioma
        $subject = ($language === 'es') 
            ? 'Restablecimiento de contraseña - LegalityFrame' 
            : 'Password Reset - LegalityFrame';
        
        // Enviar correo
        return $this->send($email, $subject, $content);
    }
    
    /**
     * Obtener plantilla básica de restablecimiento de contraseña
     * 
     * @param string $name Nombre del destinatario
     * @param string $resetUrl URL de restablecimiento
     * @param string $language Idioma del correo
     * @return string
     */
    private function getBasicPasswordResetTemplate($name, $resetUrl, $language = 'es')
    {
        if ($language === 'es') {
            return '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h2>Hola ' . htmlspecialchars($name) . ',</h2>
                    <p>Has solicitado restablecer tu contraseña en LegalityFrame. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                    <p><a href="' . $resetUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4A90E2; color: white; text-decoration: none; border-radius: 4px;">Restablecer contraseña</a></p>
                    <p>O copia y pega la siguiente URL en tu navegador:</p>
                    <p>' . $resetUrl . '</p>
                    <p>Si no has solicitado restablecer tu contraseña, puedes ignorar este correo.</p>
                    <p>Saludos,<br>El equipo de LegalityFrame</p>
                </div>
            ';
        } else {
            return '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h2>Hello ' . htmlspecialchars($name) . ',</h2>
                    <p>You have requested to reset your password at LegalityFrame. Click the link below to create a new password:</p>
                    <p><a href="' . $resetUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4A90E2; color: white; text-decoration: none; border-radius: 4px;">Reset password</a></p>
                    <p>Or copy and paste the following URL into your browser:</p>
                    <p>' . $resetUrl . '</p>
                    <p>If you did not request a password reset, you can ignore this email.</p>
                    <p>Regards,<br>The LegalityFrame Team</p>
                </div>
            ';
        }
    }
} 