<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../UserManager.php';

requireAuth();
requireRole('admin');

$userId = $_GET['id'] ?? 0;
$userManager = new UserManager();
$user = $userManager->getUserById($userId);

if (!$user) {
    $_SESSION['error_message'] = 'Usuario no encontrado';
    redirectTo('users');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Validaciones
    if (!validateRequired($name)) {
        $errors[] = 'El nombre es requerido';
    }
    
    if (!validateEmail($email)) {
        $errors[] = 'Email inválido';
    }
    
    if (!in_array($role, ['admin', 'supervisor', 'analyst'])) {
        $errors[] = 'Rol inválido';
    }
    
    // Si se proporciona contraseña, validarla
    if (!empty($password) && !validateMinLength($password, 6)) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    if (empty($errors)) {
        try {
            $data = [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];
            
            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($password)) {
                $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            if ($userManager->updateUser($userId, $data)) {
                $_SESSION['success_message'] = 'Usuario actualizado exitosamente';
                redirectTo('users/view?id=' . $userId);
            } else {
                $errors[] = 'Error al actualizar el usuario';
            }
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Editar Usuario - ' . $user['name'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
        <p class="subtitle">Modifica la información del usuario</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= url('users/view?id=' . $user['id']) ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Ver Detalle
        </a>
        <a href="<?= url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
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

<form method="POST" action="" class="form-container">
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
                           value="<?= htmlspecialchars($user['name']) ?>" 
                           required>
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           value="<?= htmlspecialchars($user['email']) ?>" 
                           required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="role">Rol <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        <option value="supervisor" <?= $user['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                        <option value="analyst" <?= $user['role'] === 'analyst' ? 'selected' : '' ?>>Analista</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Nueva Contraseña <small>(dejar en blanco para no cambiar)</small></label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Mínimo 6 caracteres">
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>Si no desea cambiar la contraseña, deje el campo en blanco.</span>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Actualizar Usuario
        </button>
        <a href="<?= url('users/view?id=' . $user['id']) ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
