# 🛡️ Sistema de Gestión de Reclamos de Seguros - SecureLife Insurance

Sistema completo para la gestión y seguimiento de reclamos de seguros, desarrollado con arquitectura **MVC en PHP Vanilla** (sin frameworks) siguiendo las mejores prácticas de desarrollo.

---

## 📋 Tabla de Contenidos

- [Características](#-características-principales)
- [Arquitectura MVC](#-arquitectura-mvc)
- [Instalación](#-instalación-rápida)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Módulos Disponibles](#-módulos-disponibles)
- [Funciones Helper](#-funciones-helper)
- [Base de Datos](#-base-de-datos)
- [Guía de Desarrollo](#-guía-de-desarrollo)
- [Credenciales de Acceso](#-credenciales-de-acceso)

---

## ✨ Características Principales

### ✅ Funcionalidades Implementadas

1. **Gestión de Pólizas y Clientes**
   - CRUD completo de pólizas
   - Gestión de datos de asegurados
   - Búsqueda y filtros avanzados
   - Estadísticas de cobertura

2. **Sistema de Reclamos**
   - Registro y seguimiento de reclamos
   - Asociación con pólizas existentes
   - Categorización por tipo de siniestro
   - Validación de datos completa
   - Estados del reclamo (pendiente, en revisión, aprobado, rechazado)

3. **Dashboard y Estadísticas**
   - Visualización en tiempo real
   - Métricas por estado de reclamos
   - Gráficos y reportes
   - Filtros dinámicos

4. **Sistema de Autenticación**
   - Login con roles (Admin, Supervisor, Analista)
   - Protección de rutas
   - Sesiones seguras
   - Gestión de usuarios

5. **Diseño Profesional**
   - Tema de seguros moderno
   - Responsive design
   - Interfaz intuitiva
   - Iconos Font Awesome
   - Animaciones suaves

---

## 🏗️ Arquitectura MVC

El proyecto sigue el patrón **Modelo-Vista-Controlador (MVC)**:

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ▼
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│    Views    │ ◄─── │ Controllers │ ◄─── │   Models    │
│  (UI/HTML)  │      │   (Logic)   │      │  (Data)     │
└─────────────┘      └─────────────┘      └─────────────┘
                            │
                            ▼
                     ┌─────────────┐
                     │  Database   │
                     └─────────────┘
```

### Componentes

- **Models** (`{Nombre}.php`): Representan las entidades de datos
- **Managers** (`{NombreManager}.php`): Lógica de negocio y acceso a datos
- **Controllers** (`{NombreController}.php`): Procesa requests y coordina Models/Views
- **Views** (`views/*.php`): Interfaz de usuario (HTML/PHP)

---

## 🚀 Instalación Rápida

### Prerequisitos

- PHP 8.0 o superior
- MySQL/MariaDB
- Apache/Nginx (o Laragon/XAMPP)
- Extensión PDO MySQL habilitada

### Pasos de Instalación

#### 1️⃣ Crear Base de Datos

```sql
CREATE DATABASE proyecto_reclamos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 2️⃣ Configurar Variables de Entorno

Crear archivo `.env` en la raíz del proyecto:

```env
# Configuración de la Aplicación
APP_NAME="SecureLife Insurance"
APP_ENV=development
BASE_PATH=/PROYECTO
TIMEZONE=America/Panama

# Base de Datos
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=proyecto_reclamos
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

#### 3️⃣ Ejecutar Migraciones

Acceder en el navegador:
```
http://localhost/PROYECTO/run-migrations.php
```

O ejecutar desde terminal:
```bash
php run-migrations.php
```

#### 4️⃣ Ejecutar Seed (Datos Iniciales)

```bash
cd database
php seed.php
```

O acceder:
```
http://localhost/PROYECTO/database/seed.php
```

#### 5️⃣ Acceder al Sistema

```
http://localhost/PROYECTO/
```

---

## 📁 Estructura del Proyecto

```
PROYECTO/
├── auth/                      # Autenticación
│   ├── login.php             # Página de login
│   ├── logout.php            # Cerrar sesión
│   └── register.php          # Registro (opcional)
│
├── database/                  # Base de datos
│   ├── MigrationManager.php  # Gestor de migraciones
│   ├── migrations/           # Archivos SQL de migración
│   └── seed.php              # Datos iniciales
│
├── includes/                  # Utilidades
│   ├── auth.php              # Funciones de autenticación
│   └── helpers.php           # Funciones auxiliares
│
├── public/                    # Recursos públicos
│   └── assets/
│       ├── css/
│       │   └── insurance-theme.css  # Tema principal
│       └── js/
│           └── main.js       # JavaScript principal
│
├── src/                       # Código fuente (MVC)
│   ├── Controller.php        # Controlador base
│   ├── Database.php          # Conexión a BD (Singleton)
│   │
│   └── {ModuleName}/         # Módulos del sistema
│       ├── {ModuleName}.php           # Modelo
│       ├── {ModuleName}Manager.php    # Manager (lógica)
│       ├── {ModuleName}Controller.php # Controlador
│       ├── index.php                  # Entrada del módulo
│       └── views/                     # Vistas del módulo
│           ├── index.php              # Lista
│           ├── create.php             # Crear
│           ├── edit.php               # Editar
│           └── view.php               # Detalle
│
├── views/                     # Templates globales
│   └── layout.php            # Layout principal
│
├── .env                       # Variables de entorno
├── config.php                # Configuración global
├── dashboard.php             # Dashboard principal
├── index.php                 # Landing page
├── run-migrations.php        # Ejecutor de migraciones
└── README.md                 # Esta documentación
```

---

## 📦 Módulos Disponibles

Cada módulo sigue la estructura MVC completa:

### 1. **Claims** (Reclamos)
- **Modelo**: `Claim.php`
- **Manager**: `ClaimManager.php`
- **Controller**: `ClaimsController.php`
- **Vistas**: `index.php`, `create.php`, `edit.php`, `view.php`
- **Funcionalidad**: CRUD completo de reclamos

### 2. **Policies** (Pólizas)
- **Modelo**: `Policy.php`
- **Manager**: `PolicyManager.php`
- **Vistas**: `index.php`, `create.php`, `edit.php`, `view.php`
- **Funcionalidad**: Gestión de pólizas de seguros

### 3. **Users** (Usuarios)
- **Modelo**: `User.php`
- **Manager**: `UserManager.php`
- **Vistas**: `index.php`, `create.php`, `edit.php`, `view.php`
- **Funcionalidad**: Gestión de usuarios y roles

### 4. **Categories** (Categorías)
- **Modelo**: `Category.php`
- **Manager**: `CategoryManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Categorías de reclamos

### 5. **Statuses** (Estados)
- **Modelo**: `Status.php`
- **Manager**: `StatusManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Estados de reclamos

### 6. **Reports** (Reportes)
- **Manager**: `ReportManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Generación de reportes y estadísticas

### 7. **Roles** (Roles de Usuario)
- **Modelo**: `Role.php`
- **Manager**: `RoleManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Roles del sistema

### 8. **Decisions** (Decisiones)
- **Modelo**: `Decision.php`
- **Manager**: `DecisionManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Tipos de decisiones

### 9. **ClaimFiles** (Archivos)
- **Modelo**: `ClaimFile.php`
- **Manager**: `ClaimFileManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Gestión de archivos adjuntos

### 10. **ClaimResults** (Resultados)
- **Modelo**: `ClaimResult.php`
- **Manager**: `ClaimResultManager.php`
- **Vistas**: `index.php`
- **Funcionalidad**: Resultados y resoluciones

---

## 🛠️ Funciones Helper

### Funciones de URL (`includes/helpers.php`)

```php
// Generar URL completa
url($path)                    // url('dashboard.php') → http://localhost/PROYECTO/dashboard.php

// Generar URL para assets
asset($file)                  // asset('assets/css/style.css') → http://localhost/PROYECTO/public/assets/css/style.css

// URL base
base_url()                    // http://localhost/PROYECTO/

// Redirección
redirectTo($path)             // redirectTo('dashboard.php')
```

### Funciones de Validación

```php
sanitize($string)             // Sanitiza entrada de usuario
validateEmail($email)         // Valida formato de email
validateRequired($value)      // Valida campo requerido
validateMinLength($val, $min) // Valida longitud mínima
validateMaxLength($val, $max) // Valida longitud máxima
```

### Funciones de Formato

```php
formatMoney($amount)          // formatMoney(1500.50) → $1,500.50
formatDate($date)             // formatDate('2024-01-01') → 1 de enero de 2024
```

### Funciones de Autenticación (`includes/auth.php`)

```php
requireAuth()                 // Requiere autenticación
requireRole($roles)           // Requiere rol específico
isAuthenticated()            // Verifica si está autenticado
hasRole($role)               // Verifica si tiene un rol
getCurrentUser()             // Obtiene usuario actual
login($user)                 // Inicia sesión
logout()                     // Cierra sesión
```

---

## 🗄️ Base de Datos

### Conexión (Singleton Pattern)

```php
$db = Database::getInstance()->getConnection();
```

### Tablas Principales

- `users` - Usuarios del sistema
- `roles` - Roles (admin, supervisor, analyst)
- `policies` - Pólizas de seguros
- `claims` - Reclamos
- `categories` - Categorías de reclamos
- `statuses` - Estados de reclamos
- `decisions` - Tipos de decisiones
- `claim_files` - Archivos adjuntos
- `claim_results` - Resultados de reclamos
- `audit_logs` - Logs de auditoría
- `notifications` - Notificaciones

### Métodos Comunes en Managers

```php
// CRUD básico
getAllXxx()                   // Obtener todos
getXxxById($id)              // Obtener por ID
createXxx($data)             // Crear nuevo
updateXxx($id, $data)        // Actualizar
deleteXxx($id)               // Eliminar

// Búsqueda y filtros
searchXxx($term)             // Buscar por término
getXxxByStatus($status)      // Filtrar por estado
```

---

## 💻 Guía de Desarrollo

### Crear un Nuevo Módulo

#### 1. Crear Estructura de Carpetas

```
src/NewModule/
├── NewModule.php
├── NewModuleManager.php
├── NewModuleController.php
├── index.php
└── views/
    ├── index.php
    ├── create.php
    ├── edit.php
    └── view.php
```

#### 2. Crear el Modelo

```php
<?php
// src/NewModule/NewModule.php

class NewModule {
    public $id;
    public $name;
    // ... propiedades
    
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
```

#### 3. Crear el Manager

```php
<?php
// src/NewModule/NewModuleManager.php

class NewModuleManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllNewModules() {
        $stmt = $this->db->query("SELECT * FROM new_modules");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ... otros métodos CRUD
}
```

#### 4. Crear el Controlador

```php
<?php
// src/NewModule/NewModuleController.php

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/NewModuleManager.php';

class NewModuleController extends Controller {
    private $manager;
    
    public function __construct() {
        parent::__construct();
        $this->manager = new NewModuleManager();
    }
    
    public function index() {
        requireAuth();
        $items = $this->manager->getAllNewModules();
        
        $this->view('NewModule/views/index.php', [
            'pageTitle' => 'NewModule',
            'showNav' => true,
            'items' => $items
        ]);
    }
    
    // ... otros métodos (create, store, show, edit, update, delete)
}
```

#### 5. Crear el index.php del Módulo

```php
<?php
// src/NewModule/index.php
header('Location: views/index.php');
exit;
```

#### 6. Crear las Vistas

Las vistas deben seguir la estructura estándar con:
- Header con título y botones
- Cards para organizar contenido
- Formularios con validación
- Tablas responsivas para listados

### Convenciones de Código

1. **Nombres de Archivos**: PascalCase para clases, kebab-case para vistas
2. **Clases**: Una clase por archivo
3. **Métodos**: camelCase
4. **Constantes**: UPPER_SNAKE_CASE
5. **Variables**: snake_case o camelCase
6. **Comentarios**: PHPDoc para funciones públicas

### Buenas Prácticas

- ✅ Usar prepared statements para queries SQL
- ✅ Validar y sanitizar todos los inputs
- ✅ Usar funciones helper para URLs y formateo
- ✅ Implementar control de acceso en rutas sensibles
- ✅ Mantener la separación de responsabilidades (MVC)
- ✅ Usar transacciones para operaciones múltiples
- ✅ Manejar errores apropiadamente
- ✅ Documentar código complejo

---

## 🔐 Credenciales de Acceso

### Usuario Administrador

```
Email: admin@sistema.com
Password: admin123
```

⚠️ **Importante**: Cambiar estas credenciales en producción.

### Roles Disponibles

- **Admin**: Acceso total al sistema
- **Supervisor**: Gestión de reclamos y usuarios
- **Analyst**: Procesamiento de reclamos

---

## 📊 Datos Iniciales (Seed)

El seed crea automáticamente:

✅ **3 Roles**: admin, supervisor, analyst  
✅ **6 Categorías**: Auto, Hogar, Vida, Salud, Robo, Incendio  
✅ **5 Estados**: pending, in-review, approved, rejected, closed  
✅ **4 Decisiones**: approved, rejected, partial, requires-info  
✅ **1 Usuario Admin**: admin@sistema.com  
✅ **2 Pólizas de Ejemplo**: Para testing

---

## 🔧 Comandos Útiles

### Reiniciar Base de Datos

```sql
DROP DATABASE IF EXISTS proyecto_reclamos;
CREATE DATABASE proyecto_reclamos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Luego ejecutar migraciones y seed nuevamente.

### Ver Estructura de Tablas

```sql
USE proyecto_reclamos;
SHOW TABLES;
DESCRIBE claims;
```

### Limpiar Sesiones

```php
// En PHP
session_start();
session_destroy();
```

---

## 🎨 Personalización del Tema

El tema se encuentra en `public/assets/css/insurance-theme.css`.

### Variables CSS Principales

```css
:root {
    --primary-color: #0066cc;      /* Azul principal */
    --secondary-color: #004d99;    /* Azul oscuro */
    --accent-color: #00c853;       /* Verde acento */
    --success-color: #28a745;      /* Verde éxito */
    --warning-color: #ffc107;      /* Amarillo advertencia */
    --danger-color: #dc3545;       /* Rojo peligro */
    --info-color: #17a2b8;         /* Azul info */
}
```

Para cambiar el tema, modifica estas variables.

---

## 📚 Recursos Adicionales

- [Documentación PHP](https://www.php.net/docs.php)
- [PDO Tutorial](https://www.php.net/manual/es/book.pdo.php)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [Bootstrap Grid](https://getbootstrap.com/docs/5.3/layout/grid/)

---

## 📝 Notas de Versión

### v2.0.0 - Arquitectura MVC (Actual)
- ✅ Implementación completa de MVC
- ✅ Controladores para todos los módulos
- ✅ Vistas completas (index, create, edit, view)
- ✅ Diseño profesional de seguros
- ✅ Landing page moderna
- ✅ Documentación consolidada

### v1.0.0 - Versión Inicial
- ✅ Estructura básica
- ✅ CRUD de reclamos y pólizas
- ✅ Sistema de autenticación
- ✅ Dashboard con estadísticas

---

## 🤝 Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto es de uso educativo y no tiene licencia específica.

---

## 👨‍💻 Autor

Desarrollado como proyecto final de Desarrollo Web VII - UTP

**SecureLife Insurance System** - Sistema profesional de gestión de reclamos de seguros con arquitectura MVC en PHP Vanilla.

---

## 🌟 Agradecimientos

- Font Awesome por los iconos
- La comunidad de PHP
- Universidad Tecnológica de Panamá

---

*Última actualización: 8 de diciembre de 2025*
