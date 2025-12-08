<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../UserManager.php';

requireAuth();
requireRole('admin');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // Validaciones
    if (!validateRequired($name)) {
        $errors[] = 'El nombre es requerido';
    }
    
    if (!validateEmail($email)) {
        $errors[] = 'Email inválido';
    }
    
    if (!validateRequired($password)) {
        $errors[] = 'La contraseña es requerida';
    } elseif (!validateMinLength($password, 6)) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    if (!in_array($role, ['admin', 'supervisor', 'analyst'])) {
        $errors[] = 'Rol inválido';
    }
    
    if (empty($errors)) {
        try {
            $userManager = new UserManager();
            
            $data = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ];
            
            if ($userManager->createUser($data)) {
                $_SESSION['success_message'] = 'Usuario creado exitosamente';
                redirectTo('users');
            } else {
                $errors[] = 'Error al crear el usuario';
            }
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Crear Nuevo Usuario';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</h1>
        <p class="subtitle">Complete el formulario para registrar un nuevo usuario</p>
    </div>
    <a href="<?= url('users') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Errores encontrados:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<form method="POST" action="" class="form-container" style="margin-top: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Información del Usuario</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nombre Completo <span class="required">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control" 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                           placeholder="Ej: Juan Pérez García"
                           required>
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           placeholder="ejemplo@correo.com"
                           required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Contraseña <span class="required">*</span></label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Mínimo 6 caracteres"
                           required>
                    <small class="form-text">La contraseña debe tener al menos 6 caracteres.</small>
                </div>
                <div class="form-group">
                    <label for="role">Rol <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">Seleccione un rol</option>
                        <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Administrador</option>
                        <option value="supervisor" <?= (isset($_POST['role']) && $_POST['role'] === 'supervisor') ? 'selected' : '' ?>>Supervisor</option>
                        <option value="analyst" <?= (isset($_POST['role']) && $_POST['role'] === 'analyst') ? 'selected' : '' ?>>Analista</option>
                    </select>
                    <small class="form-text">Define los permisos del usuario en el sistema.</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Usuario
        </button>
        <a href="<?= url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
