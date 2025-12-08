<?php
require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/ClaimManager.php';
require_once __DIR__ . '/../Policies/PolicyManager.php';
require_once __DIR__ . '/../Categories/CategoryManager.php';
require_once __DIR__ . '/../Users/UserManager.php';
require_once __DIR__ . '/../Statuses/StatusManager.php';

/**
 * Claims Controller
 * Maneja todas las operaciones CRUD de reclamos
 */
class ClaimsController extends Controller {
    private $claimManager;
    private $policyManager;
    private $categoryManager;
    private $userManager;
    private $statusManager;
    
    public function __construct() {
        parent::__construct();
        $this->claimManager = new ClaimManager();
        $this->policyManager = new PolicyManager();
        $this->categoryManager = new CategoryManager();
        $this->userManager = new UserManager();
        $this->statusManager = new StatusManager();
    }
    
    /**
     * Listar todos los reclamos
     */
    public function index() {
        requireAuth();
        
        $searchTerm = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        
        // Obtener reclamos
        if ($searchTerm) {
            $claims = $this->claimManager->searchClaims($searchTerm);
        } else {
            $claims = $this->claimManager->getAllClaims();
        }
        
        // Filtrar por estado
        if ($statusFilter) {
            $claims = array_filter($claims, fn($c) => $c['status'] === $statusFilter);
        }
        
        $statuses = $this->statusManager->getAllStatuses();
        
        $this->view('Claims/views/index.php', [
            'pageTitle' => 'Reclamos - Sistema de Gestión',
            'showNav' => true,
            'claims' => $claims,
            'statuses' => $statuses,
            'searchTerm' => $searchTerm,
            'statusFilter' => $statusFilter
        ]);
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        requireAuth();
        
        $policies = $this->policyManager->getAllPolicies();
        $categories = $this->categoryManager->getAllCategories();
        $analysts = $this->userManager->getUsersByRole('analyst');
        
        $this->view('Claims/views/create.php', [
            'pageTitle' => 'Nuevo Reclamo - Sistema de Gestión',
            'showNav' => true,
            'policies' => $policies,
            'categories' => $categories,
            'analysts' => $analysts
        ]);
    }
    
    /**
     * Procesar creación de reclamo
     */
    public function store() {
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('src/Claims/');
        }
        
        $errors = $this->validate($_POST, [
            'policy_id' => ['required'],
            'category_id' => ['required'],
            'insured_name' => ['required', 'min:3'],
            'amount' => ['required'],
            'description' => ['required', 'min:10']
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('src/Claims/views/create.php');
        }
        
        $data = [
            'policy_id' => (int)$_POST['policy_id'],
            'category_id' => (int)$_POST['category_id'],
            'insured_name' => sanitize($_POST['insured_name']),
            'insured_phone' => sanitize($_POST['insured_phone'] ?? ''),
            'insured_email' => sanitize($_POST['insured_email'] ?? ''),
            'amount' => (float)$_POST['amount'],
            'description' => sanitize($_POST['description']),
            'analyst_id' => !empty($_POST['analyst_id']) ? (int)$_POST['analyst_id'] : getCurrentUser()['id']
        ];
        
        $claimId = $this->claimManager->createClaim($data);
        
        if ($claimId) {
            $_SESSION['success_message'] = 'Reclamo creado exitosamente';
            $this->redirect('src/Claims/views/view.php?id=' . $claimId);
        } else {
            $_SESSION['error_message'] = 'Error al crear el reclamo';
            $this->redirect('src/Claims/views/create.php');
        }
    }
    
    /**
     * Mostrar detalle de un reclamo
     */
    public function show($id) {
        requireAuth();
        
        $claim = $this->claimManager->getClaimById($id);
        
        if (!$claim) {
            $_SESSION['error_message'] = 'Reclamo no encontrado';
            $this->redirect('src/Claims/');
        }
        
        $this->view('Claims/views/view.php', [
            'pageTitle' => 'Detalle del Reclamo - Sistema de Gestión',
            'showNav' => true,
            'claim' => $claim
        ]);
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        requireAuth();
        
        $claim = $this->claimManager->getClaimById($id);
        
        if (!$claim) {
            $_SESSION['error_message'] = 'Reclamo no encontrado';
            $this->redirect('src/Claims/');
        }
        
        $policies = $this->policyManager->getAllPolicies();
        $categories = $this->categoryManager->getAllCategories();
        $statuses = $this->statusManager->getAllStatuses();
        $analysts = $this->userManager->getUsersByRole('analyst');
        
        $this->view('Claims/views/edit.php', [
            'pageTitle' => 'Editar Reclamo - Sistema de Gestión',
            'showNav' => true,
            'claim' => $claim,
            'policies' => $policies,
            'categories' => $categories,
            'statuses' => $statuses,
            'analysts' => $analysts
        ]);
    }
    
    /**
     * Procesar actualización de reclamo
     */
    public function update($id) {
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('src/Claims/views/edit.php?id=' . $id);
        }
        
        $errors = $this->validate($_POST, [
            'policy_id' => ['required'],
            'category_id' => ['required'],
            'insured_name' => ['required', 'min:3'],
            'amount' => ['required'],
            'description' => ['required', 'min:10']
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('src/Claims/views/edit.php?id=' . $id);
        }
        
        $data = [
            'policy_id' => (int)$_POST['policy_id'],
            'category_id' => (int)$_POST['category_id'],
            'status_id' => (int)$_POST['status_id'],
            'insured_name' => sanitize($_POST['insured_name']),
            'insured_phone' => sanitize($_POST['insured_phone'] ?? ''),
            'insured_email' => sanitize($_POST['insured_email'] ?? ''),
            'amount' => (float)$_POST['amount'],
            'description' => sanitize($_POST['description']),
            'analyst_id' => (int)$_POST['analyst_id']
        ];
        
        $result = $this->claimManager->updateClaim($id, $data);
        
        if ($result) {
            $_SESSION['success_message'] = 'Reclamo actualizado exitosamente';
            $this->redirect('src/Claims/views/view.php?id=' . $id);
        } else {
            $_SESSION['error_message'] = 'Error al actualizar el reclamo';
            $this->redirect('src/Claims/views/edit.php?id=' . $id);
        }
    }
    
    /**
     * Eliminar un reclamo
     */
    public function delete($id) {
        requireAuth();
        requireRole(['admin', 'supervisor']);
        
        $result = $this->claimManager->deleteClaim($id);
        
        if ($result) {
            $_SESSION['success_message'] = 'Reclamo eliminado exitosamente';
        } else {
            $_SESSION['error_message'] = 'Error al eliminar el reclamo';
        }
        
        $this->redirect('src/Claims/');
    }
}
