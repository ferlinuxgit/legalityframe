<?php
/**
 * Plantilla para el email de verificación
 * 
 * Variables disponibles:
 * @var string $name Nombre del usuario
 * @var string $verificationUrl URL de verificación
 * @var string $language Idioma del usuario
 */
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language === 'es' ? 'Verificación de cuenta' : 'Account Verification' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a66d8;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #4a66d8;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .link {
            word-break: break-all;
            color: #4a66d8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $language === 'es' ? 'Verificación de cuenta' : 'Account Verification' ?></h1>
        </div>
        <div class="content">
            <h2><?= $language === 'es' ? 'Hola' : 'Hello' ?> <?= htmlspecialchars($name) ?>,</h2>
            
            <?php if ($language === 'es'): ?>
                <p>Gracias por registrarte en <strong>LegalityFrame</strong>. Para completar tu registro, por favor verifica tu dirección de correo electrónico haciendo clic en el siguiente botón:</p>
                <div style="text-align: center;">
                    <a href="<?= $verificationUrl ?>" class="button">Verificar mi correo electrónico</a>
                </div>
                <p>Si el botón no funciona, copia y pega la siguiente URL en tu navegador:</p>
                <p><a href="<?= $verificationUrl ?>" class="link"><?= $verificationUrl ?></a></p>
                <p>Si no has solicitado esta cuenta, puedes ignorar este mensaje.</p>
                <p>Este enlace de verificación expirará en 24 horas.</p>
                <p>Gracias,<br>El equipo de LegalityFrame</p>
            <?php else: ?>
                <p>Thank you for signing up at <strong>LegalityFrame</strong>. To complete your registration, please verify your email address by clicking the button below:</p>
                <div style="text-align: center;">
                    <a href="<?= $verificationUrl ?>" class="button">Verify my email address</a>
                </div>
                <p>If the button doesn't work, copy and paste the following URL into your browser:</p>
                <p><a href="<?= $verificationUrl ?>" class="link"><?= $verificationUrl ?></a></p>
                <p>If you did not request this account, you can ignore this message.</p>
                <p>This verification link will expire in 24 hours.</p>
                <p>Thanks,<br>The LegalityFrame Team</p>
            <?php endif; ?>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> LegalityFrame. <?= $language === 'es' ? 'Todos los derechos reservados.' : 'All rights reserved.' ?></p>
            <p><?= $language === 'es' ? 'Este es un mensaje automático, por favor no respondas a este correo.' : 'This is an automated message, please do not reply to this email.' ?></p>
        </div>
    </div>
</body>
</html> 