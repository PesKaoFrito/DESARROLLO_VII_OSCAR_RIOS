<?php
/**
 * Base Controller
 * Clase base para todos los controladores del sistema
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Cargar una vista con layout
     */
    protected function view($viewPath, $data = []) {
        // Extraer datos para usar en la vista
        extract($data);
        
        // Iniciar buffer de salida
        ob_start();
        
        // Incluir la vista
        $viewFile = __DIR__ . '/../' . $viewPath;
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Vista no encontrada: $viewPath");
        }
        
        // Obtener contenido del buffer
        $content = ob_get_clean();
        
        // Si se especificó que no se use layout, retornar solo el contenido
        if (isset($data['noLayout']) && $data['noLayout']) {
            echo $content;
            return;
        }
        
        // Cargar layout
        if (isset($data['showNav'])) {
            $showNav = $data['showNav'];
        }
        if (isset($data['pageTitle'])) {
            $pageTitle = $data['pageTitle'];
        }
        
        require __DIR__ . '/../views/layout.php';
    }
    
    /**
     * Retornar JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirigir a una URL
     */
    protected function redirect($url) {
        redirectTo($url);
    }
    
    /**
     * Validar datos del request
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "El campo $field es requerido";
                }
                
                if ($rule === 'email' && !validateEmail($value)) {
                    $errors[$field][] = "El campo $field debe ser un email válido";
                }
                
                if (strpos($rule, 'min:') === 0) {
                    $min = (int)substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "El campo $field debe tener al menos $min caracteres";
                    }
                }
                
                if (strpos($rule, 'max:') === 0) {
                    $max = (int)substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = "El campo $field no puede tener más de $max caracteres";
                    }
                }
            }
        }
        
        return $errors;
    }
}
