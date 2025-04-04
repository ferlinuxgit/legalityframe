<?php
/**
 * Vista para el formulario de registro
 * 
 * @var string $csrfToken Token CSRF para protección del formulario
 */

// Incluir cabecera específica de autenticación
include_once __DIR__ . '/../layouts/auth_header.php'; 

// Acceder a errores y datos antiguos usando Session::getFlash
$errors = App\Core\Session::getFlash('errors', []);
$old = App\Core\Session::getFlash('old', []);
$error = App\Core\Session::getFlash('error', ''); // Para errores generales
// No necesitamos borrar explícitamente los flash, Session::getFlash lo hace (o debería)
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h2><?php echo __('auth.register'); // Asumir helper __() existe ?></h2></div>

                <div class="card-body">
                    <!-- Mostrar errores de validación -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $field => $messages): ?>
                                    <?php // Asegurarse que $messages es array
                                        $messages_array = is_array($messages) ? $messages : [$messages];
                                    ?>
                                    <?php foreach ($messages_array as $message): ?>
                                        <li><?php echo htmlspecialchars($message); ?></li>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Mostrar errores generales (DB, Mail) -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo url('/register'); // Usar url() helper ?>"> 
                        <?php echo csrf_field(); // Usar helper ?>

                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo __('auth.name'); ?></label>
                            <input id="name" type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo htmlspecialchars($old['name'] ?? '', ENT_QUOTES); ?>" required autofocus>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars(is_array($errors['name']) ? $errors['name'][0] : $errors['name']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo __('auth.email'); ?></label>
                            <input id="email" type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" name="email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES); ?>" required>
                             <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars(is_array($errors['email']) ? $errors['email'][0] : $errors['email']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"><?php echo __('auth.password'); ?></label>
                            <input id="password" type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" name="password" required>
                             <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars(is_array($errors['password']) ? $errors['password'][0] : $errors['password']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label"><?php echo __('auth.confirm_password'); ?></label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            <!-- No suele mostrarse error específico para confirmación, ya que el error está en 'password' -->
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <?php echo __('auth.register'); ?>
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <?php echo __('auth.already_registered'); ?> <a href="<?php echo url('/login'); // Usar url() helper ?>"><?php echo __('auth.login'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // Incluir pie de página específico de autenticación
    include_once __DIR__ . '/../layouts/auth_footer.php'; 
?> 