<?php
/**
 * Vista para la página de notificación de verificación de email
 */

// Incluir cabecera específica de autenticación
include_once __DIR__ . '/../layouts/auth_header.php';

// Acceder a mensajes flash usando Session::getFlash
$success_message = App\Core\Session::getFlash('success', null);
$error_message = App\Core\Session::getFlash('error', null);
// No necesitamos borrar explícitamente los flash
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2><?php echo __('auth.verify_email_address'); ?></h2></div>

                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo __('auth.verification_notice_message'); ?>

                    <p class="mt-3">
                        <?php echo __('auth.didnt_receive_email'); ?>
                        <form class="d-inline" method="POST" action="<?php echo url('/resend-verification'); // Usar url() helper ?>">
                             <?php echo csrf_field(); // Usar helper ?>
                            
                            <!-- Añadir campo oculto para el email si es necesario -->
                            <?php 
                                // Intentar obtener el email del usuario recién registrado (si está en old data flash)
                                $old_data = App\Core\Session::getFlash('old', []); 
                                $email_for_resend = $old_data['email'] ?? null;
                                // O si el controlador lo pasa explícitamente:
                                // $email_for_resend = $email_for_resend ?? ($passed_email ?? null); 
                            ?>
                            <?php if ($email_for_resend): ?>
                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email_for_resend); ?>">
                            <?php else: ?>
                                <!-- Si no tenemos el email, necesitamos un campo visible -->
                                <span class="ms-2"> <!-- Añadir margen -->
                                    <input type="email" name="email" placeholder="<?php echo __('auth.email'); ?>" required>
                                </span>
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline"><?php echo __('auth.resend_verification_link'); ?></button>.
                        </form>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // Incluir pie de página específico de autenticación
    include_once __DIR__ . '/../layouts/auth_footer.php'; 
?> 