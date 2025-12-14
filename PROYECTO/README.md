# 🛡️ Sistema de Gestión de Reclamos de Seguros - SecureLife Insurance

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-Educational-green?style=flat)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-success?style=flat)](https://github.com)

Sistema completo para la gestión y seguimiento de reclamos de seguros, desarrollado con arquitectura **MVC en PHP Vanilla** (sin frameworks) siguiendo las mejores prácticas de desarrollo.

### 🎯 Propósito
Proyecto final de **Desarrollo Web VII** - Universidad Tecnológica de Panamá. Sistema profesional que demuestra dominio de PHP, MySQL, arquitectura MVC y mejores prácticas de desarrollo web.

### ⭐ Características Destacadas
```
✅ 10 módulos completamente funcionales    ✅ Sistema de autenticación robusto
✅ Arquitectura MVC simplificada           ✅ Control de acceso por roles (RBAC)
✅ Base de datos normalizada               ✅ Interfaz responsive y moderna
✅ Sistema de migraciones                  ✅ Reportes y estadísticas
✅ Gestión de archivos adjuntos            ✅ Búsqueda y filtros avanzados
```

---

## 📋 Tabla de Contenidos

### 🚀 Inicio Rápido
- [Tecnologías Utilizadas](#️-tecnologías-utilizadas)
- [Características Principales](#-características-principales)
- [Instalación Rápida](#-instalación-rápida)
- [Credenciales de Acceso](#-credenciales-de-acceso)

### 📐 Arquitectura
- [Arquitectura MVC](#️-arquitectura-mvc)
- [Características Técnicas](#-características-técnicas-implementadas)
- [Estructura del Proyecto](#-estructura-del-proyecto)

### 📦 Módulos y Base de Datos
- [Módulos Disponibles](#-módulos-disponibles)
- [Base de Datos](#️-base-de-datos)
- [Datos Iniciales (Seed)](#-datos-iniciales-seed)

### 💻 Desarrollo
- [Funciones Helper](#️-funciones-helper)
- [Guía de Desarrollo](#-guía-de-desarrollo)
- [Personalización del Tema](#-personalización-del-tema)

### 🔧 Utilidades
- [Comandos Útiles](#-comandos-útiles)
- [Troubleshooting](#-troubleshooting-solución-de-problemas)
- [Preguntas Frecuentes](#-preguntas-frecuentes-faq)

### 📚 Recursos
- [Recursos Adicionales](#-recursos-adicionales)
- [Notas de Versión](#-notas-de-versión)
- [Extensión Recomendada](#-extensión-recomendada-para-visualización)

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.0+** - Lenguaje de programación principal
- **MySQL/MariaDB** - Sistema de gestión de base de datos
- **PDO** - Capa de abstracción de base de datos
- **Sessions** - Gestión de sesiones y autenticación

### Frontend
- **HTML5** - Estructura semántica
- **CSS3** - Estilos personalizados y variables CSS
- **JavaScript (Vanilla)** - Interactividad del cliente
- **Font Awesome 6** - Biblioteca de iconos
- **Responsive Design** - Compatible con móviles y tablets

### Arquitectura & Patrones
- **MVC (Model-View-Controller)** - Patrón arquitectónico
- **Singleton** - Para conexión a base de datos
- **Repository Pattern** - Managers para acceso a datos
- **Environment Variables** - Configuración con `.env`

### Herramientas de Desarrollo
- **Git** - Control de versiones
- **Composer** (opcional) - Gestión de dependencias PHP
- **Laragon/XAMPP** - Entorno de desarrollo local
- **VS Code** - Editor recomendado

### Seguridad
- **Password Hashing** - `password_hash()` / `password_verify()`
- **Prepared Statements** - Prevención de SQL Injection
- **Input Sanitization** - Validación de datos
- **Session Management** - Control de acceso seguro
- **RBAC** - Control basado en roles

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

## 📸 Pantallas del Sistema

### 🏠 Landing Page
- Página de bienvenida moderna con hero section
- Características destacadas del sistema
- Call-to-action para iniciar sesión

### 📊 Dashboard
- **Vista Admin**: Estadísticas globales de todos los reclamos y pólizas
- **Vista Supervisor**: Métricas de su equipo y reclamos asignados
- **Vista Analyst**: Resumen de reclamos personales pendientes
- Gráficos de estado de reclamos
- Acceso rápido a acciones comunes
- Reclamos recientes

### 🎯 Gestión de Reclamos
- **Lista**: Tabla con filtros por estado, búsqueda y ordenamiento
- **Crear**: Formulario con validación y selección de póliza
- **Editar**: Actualización de datos con control de permisos
- **Ver**: Vista detallada con toda la información del reclamo
- **Archivos**: Gestión de documentos adjuntos

### 📋 Gestión de Pólizas
- Listado completo de pólizas activas/vencidas
- Creación de nuevas pólizas con validación
- Edición de datos de cobertura
- Vista detallada con reclamos asociados

### 👥 Gestión de Usuarios
- Administración de usuarios del sistema (solo Admin)
- Asignación de roles y permisos
- Activación/desactivación de cuentas
- Búsqueda y filtros

### 📈 Reportes
- Estadísticas por categoría de reclamo
- Análisis de tiempos de procesamiento
- Reportes de aprobación/rechazo
- Métricas por analista/supervisor
- Filtros por fecha y estado

### 🏷️ Configuración
- Gestión de categorías de reclamos
- Administración de estados
- Tipos de decisiones
- Roles del sistema

---

## 🏗️ Arquitectura MVC

El proyecto sigue el patrón **Modelo-Vista-Controlador (MVC)** simplificado:

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ▼
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│    Views    │ ◄─── │  index.php  │ ◄─── │   Models    │
│  (UI/HTML)  │      │  (Router +  │      │  (Data)     │
│             │      │   Logic)    │      │             │
└─────────────┘      └──────┬──────┘      └─────────────┘
                            │                     │
                            ▼                     ▼
                     ┌─────────────┐      ┌─────────────┐
                     │  Managers   │ ◄─── │  Database   │
                     │  (Business  │      │  (Singleton)│
                     │   Logic)    │      └─────────────┘
                     └─────────────┘
```

### Componentes

- **Models** (`{Nombre}.php`): Representan las entidades de datos con sus propiedades
- **Managers** (`{NombreManager}.php`): Lógica de negocio y acceso a datos (CRUD)
- **Entry Point** (`index.php`): Procesa requests, ruteo y coordina Models/Views
- **Views** (`views/*.php`): Interfaz de usuario (HTML/PHP)
- **Database** (`Database.php`): Conexión singleton a la base de datos

### Flujo de una Petición

1. Usuario accede a `/PROYECTO/src/Claims/index.php?action=create`
2. `index.php` valida autenticación y procesa la acción
3. Se instancia el `Manager` correspondiente
4. El Manager consulta/modifica datos usando PDO
5. Los datos se pasan a la vista correspondiente
6. La vista se renderiza usando el layout principal

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

## 🔥 Características Técnicas Implementadas

### Seguridad
- ✅ Validación y sanitización de datos de entrada
- ✅ Prepared Statements (PDO) para prevenir SQL Injection
- ✅ Control de sesiones seguras
- ✅ Hash de contraseñas con `password_hash()`
- ✅ Protección CSRF (en proceso)
- ✅ Control de acceso basado en roles (RBAC)

### Base de Datos
- ✅ Sistema de migraciones automatizado
- ✅ Seeders para datos iniciales
- ✅ Conexión Singleton para optimización
- ✅ Transacciones para operaciones críticas
- ✅ Índices optimizados para búsquedas

### Arquitectura
- ✅ Patrón MVC simplificado
- ✅ Separación de responsabilidades
- ✅ Código reutilizable y modular
- ✅ Sistema de helpers y utilidades
- ✅ Configuración centralizada con `.env`

### UX/UI
- ✅ Diseño responsive (mobile-first)
- ✅ Tema profesional de seguros
- ✅ Iconos Font Awesome
- ✅ Feedback visual en operaciones
- ✅ Mensajes de error y éxito
- ✅ Tablas ordenables y filtros dinámicos

### Funcionalidades
- ✅ Dashboard con estadísticas en tiempo real
- ✅ Sistema de búsqueda global
- ✅ Filtros avanzados por múltiples criterios
- ✅ Gestión de archivos adjuntos
- ✅ Generación de reportes
- ✅ Historial de cambios (audit logs)
- ✅ Sistema de notificaciones

---

## 📁 Estructura del Proyecto

```
PROYECTO/
├── auth/                      # Autenticación
│   ├── login.php             # Página de login
│   ├── logout.php            # Cerrar sesión
│   └── register.php          # Registro de usuarios
│
├── database/                  # Base de datos
│   ├── MigrationManager.php  # Gestor de migraciones
│   ├── drop-all-tables.php   # Limpieza de BD
│   ├── seed.php              # Datos iniciales
│   └── migrations/           # Migraciones SQL
│       ├── 001_create_roles_table.sql
│       ├── 002_create_categories_table.sql
│       ├── 003_create_decisions_table.sql
│       ├── 004_create_statuses_table.sql
│       ├── 005_create_policies_table.sql
│       ├── 006_create_users_table.sql
│       ├── 007_create_claims_table.sql
│       ├── 008_create_claimsresults_table.sql
│       └── 009_create_claimfiles_table.sql
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
│   ├── Controller.php        # Controlador base (legacy)
│   ├── Database.php          # Conexión a BD (Singleton)
│   │
│   ├── Claims/               # Módulo de Reclamos
│   │   ├── Claim.php         # Modelo
│   │   ├── ClaimManager.php  # Lógica de negocio
│   │   ├── index.php         # Entry point y router
│   │   └── views/
│   │       ├── index.php     # Lista
│   │       ├── create.php    # Crear
│   │       ├── edit.php      # Editar
│   │       └── view.php      # Detalle
│   │
│   ├── Policies/             # Módulo de Pólizas
│   ├── Users/                # Módulo de Usuarios
│   ├── Categories/           # Módulo de Categorías
│   ├── Statuses/             # Módulo de Estados
│   ├── Roles/                # Módulo de Roles
│   ├── Decisions/            # Módulo de Decisiones
│   ├── ClaimFiles/           # Módulo de Archivos
│   ├── ClaimResults/         # Módulo de Resultados
│   └── Reports/              # Módulo de Reportes
│
├── views/                     # Templates globales
│   └── layout.php            # Layout principal
│
├── .env                       # Variables de entorno (no incluido en repo)
├── .env.example              # Ejemplo de configuración
├── config.php                # Configuración global
├── dashboard.php             # Dashboard principal
├── index.php                 # Landing page
├── router.php                # Sistema de ruteo
├── run-migrations.php        # Ejecutor de migraciones
├── test-config.php           # Verificar configuración
├── test-connection.php       # Verificar conexión BD
├── check-data.php            # Verificar datos
└── README.md                 # Esta documentación
```

---

## 📦 Módulos Disponibles

Cada módulo sigue la estructura MVC simplificada (sin controladores separados):

### 1. **Claims** (Reclamos) 🎯
- **Modelo**: `Claim.php`
- **Manager**: `ClaimManager.php`
- **Entry Point**: `index.php` - Procesa lógica y ruteo
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`, `views/view.php`
- **Funcionalidad**: 
  - CRUD completo de reclamos
  - Filtrado por estado y búsqueda
  - Asignación a analistas y supervisores
  - Gestión de archivos adjuntos
  - Historial de cambios

### 2. **Policies** (Pólizas) 📋
- **Modelo**: `Policy.php`
- **Manager**: `PolicyManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`, `views/view.php`
- **Funcionalidad**: 
  - Gestión de pólizas de seguros
  - Tipos de cobertura
  - Fechas de vigencia
  - Montos asegurados

### 3. **Users** (Usuarios) 👥
- **Modelo**: `User.php`
- **Manager**: `UserManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`, `views/view.php`
- **Funcionalidad**: 
  - Gestión de usuarios y roles
  - Asignación de permisos
  - Activación/desactivación de cuentas

### 4. **Categories** (Categorías) 🏷️
- **Modelo**: `Category.php`
- **Manager**: `CategoryManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`
- **Funcionalidad**: Categorías de reclamos (Auto, Hogar, Vida, Salud, etc.)

### 5. **Statuses** (Estados) 📊
- **Modelo**: `Status.php`
- **Manager**: `StatusManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`
- **Funcionalidad**: Estados de reclamos (Pendiente, En Revisión, Aprobado, Rechazado, Cerrado)

### 6. **Reports** (Reportes) 📈
- **Manager**: `ReportManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`
- **Funcionalidad**: 
  - Generación de reportes estadísticos
  - Gráficos de reclamos por estado
  - Análisis por categoría y período
  - Exportación de datos

### 7. **Roles** (Roles de Usuario) 🔐
- **Modelo**: `Role.php`
- **Manager**: `RoleManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`
- **Funcionalidad**: Gestión de roles (Admin, Supervisor, Analyst)

### 8. **Decisions** (Decisiones) ⚖️
- **Modelo**: `Decision.php`
- **Manager**: `DecisionManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/edit.php`
- **Funcionalidad**: Tipos de decisiones (Aprobado, Rechazado, Parcial, Requiere Información)

### 9. **ClaimFiles** (Archivos Adjuntos) 📎
- **Modelo**: `ClaimFile.php`
- **Manager**: `ClaimFileManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/upload.php`
- **Funcionalidad**: 
  - Gestión de archivos adjuntos
  - Evidencias de reclamos
  - Control de tipos de archivo
  - Seguridad en uploads

### 10. **ClaimResults** (Resultados) ✅
- **Modelo**: `ClaimResult.php`
- **Manager**: `ClaimResultManager.php`
- **Entry Point**: `index.php`
- **Vistas**: `views/index.php`, `views/create.php`, `views/view.php`
- **Funcionalidad**: 
  - Resultados y resoluciones de reclamos
  - Montos aprobados
  - Notas de decisión
  - Historial de resultados

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

#### 1. **roles** - Roles del Sistema
```sql
- id (PK)
- name (varchar 50) - Nombre del rol
- description (text) - Descripción
- created_at - Fecha de creación
```

#### 2. **users** - Usuarios
```sql
- id (PK)
- name (varchar 100) - Nombre completo
- email (varchar 100, unique) - Email de acceso
- password (varchar 255) - Hash de contraseña
- role_id (FK -> roles) - Rol asignado
- is_active (boolean) - Estado activo/inactivo
- created_at - Fecha de registro
```

#### 3. **policies** - Pólizas de Seguros
```sql
- id (PK)
- policy_number (varchar 50, unique) - Número de póliza
- insured_name (varchar 200) - Nombre del asegurado
- policy_type (varchar 100) - Tipo de póliza
- coverage_amount (decimal 15,2) - Monto de cobertura
- start_date - Fecha de inicio
- end_date - Fecha de vencimiento
- status (enum: active, expired, cancelled) - Estado
- created_at - Fecha de creación
```

#### 4. **categories** - Categorías de Reclamos
```sql
- id (PK)
- name (varchar 100) - Nombre (Auto, Hogar, Vida, etc.)
- description (text) - Descripción
- created_at - Fecha de creación
```

#### 5. **statuses** - Estados de Reclamos
```sql
- id (PK)
- name (varchar 50) - Nombre (pending, in-review, approved, etc.)
- display_name (varchar 100) - Nombre para mostrar
- description (text) - Descripción
- color (varchar 20) - Color para UI
- created_at - Fecha de creación
```

#### 6. **decisions** - Tipos de Decisiones
```sql
- id (PK)
- name (varchar 50) - Nombre (approved, rejected, partial, etc.)
- display_name (varchar 100) - Nombre para mostrar
- description (text) - Descripción
- created_at - Fecha de creación
```

#### 7. **claims** - Reclamos
```sql
- id (PK)
- claim_number (varchar 50, unique) - Número de reclamo
- policy_id (FK -> policies) - Póliza asociada
- category_id (FK -> categories) - Categoría
- status_id (FK -> statuses) - Estado actual
- incident_date - Fecha del siniestro
- claim_amount (decimal 15,2) - Monto reclamado
- description (text) - Descripción del incidente
- analyst_id (FK -> users) - Analista asignado
- supervisor_id (FK -> users) - Supervisor asignado
- notes (text) - Notas internas
- created_at - Fecha de creación
- updated_at - Fecha de actualización
```

#### 8. **claim_results** - Resultados de Reclamos
```sql
- id (PK)
- claim_id (FK -> claims) - Reclamo asociado
- decision_id (FK -> decisions) - Decisión tomada
- approved_amount (decimal 15,2) - Monto aprobado
- decision_notes (text) - Notas de la decisión
- decided_by (FK -> users) - Usuario que decidió
- decided_at - Fecha de decisión
- created_at - Fecha de creación
```

#### 9. **claim_files** - Archivos Adjuntos
```sql
- id (PK)
- claim_id (FK -> claims) - Reclamo asociado
- file_name (varchar 255) - Nombre del archivo
- file_path (varchar 500) - Ruta del archivo
- file_type (varchar 50) - Tipo MIME
- file_size (int) - Tamaño en bytes
- uploaded_by (FK -> users) - Usuario que subió
- uploaded_at - Fecha de subida
```

### Relaciones Clave

- Un **usuario** tiene un **rol**
- Una **póliza** puede tener múltiples **reclamos**
- Un **reclamo** pertenece a una **póliza**, **categoría** y **estado**
- Un **reclamo** puede tener múltiples **archivos adjuntos**
- Un **reclamo** puede tener un **resultado** (decisión)
- Un **reclamo** es gestionado por un **analista** y supervisado por un **supervisor**

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

#### 4. Crear el Entry Point (index.php)

```php
<?php
// src/NewModule/index.php

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/NewModuleManager.php';

requireAuth(); // Requiere autenticación

$currentUser = getCurrentUser();
$manager = new NewModuleManager();

// Procesar acciones
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        // Mostrar formulario de creación
        $pageTitle = 'Crear NewModule';
        require __DIR__ . '/views/create.php';
        break;
        
    case 'store':
        // Procesar creación
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                // ... otros campos
            ];
            
            if ($manager->createNewModule($data)) {
                redirectTo('src/NewModule/index.php');
            }
        }
        break;
        
    case 'edit':
        // Mostrar formulario de edición
        $id = $_GET['id'] ?? null;
        $item = $manager->getNewModuleById($id);
        $pageTitle = 'Editar NewModule';
        require __DIR__ . '/views/edit.php';
        break;
        
    case 'update':
        // Procesar actualización
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'name' => sanitize($_POST['name'] ?? ''),
                // ... otros campos
            ];
            
            if ($manager->updateNewModule($id, $data)) {
                redirectTo('src/NewModule/index.php');
            }
        }
        break;
        
    case 'delete':
        // Eliminar
        $id = $_GET['id'] ?? null;
        if ($manager->deleteNewModule($id)) {
            redirectTo('src/NewModule/index.php');
        }
        break;
        
    default: // index
        // Listar todos
        $items = $manager->getAllNewModules();
        $pageTitle = 'NewModule - Lista';
        require __DIR__ . '/views/index.php';
        break;
}
```

#### 5. Crear las Vistas

Las vistas deben seguir la estructura estándar. Ejemplo de `views/index.php`:

```php
<?php
$showNav = true;
ob_start();
?>

<div class="page-header">
    <h1><i class="fas fa-icon"></i> NewModule</h1>
    <a href="?action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Crear Nuevo
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lista de Elementos</h2>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>
                        <a href="?action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?action=delete&id=<?= $item['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../../views/layout.php';
?>
```

Estructura de vistas:
- **index.php**: Listado con tabla y filtros
- **create.php**: Formulario de creación
- **edit.php**: Formulario de edición
- **view.php**: Vista de detalle (opcional)

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

### Usuarios de Prueba

| Rol | Email | Password | Permisos |
|-----|-------|----------|----------|
| **Administrador** | `admin@sistema.com` | `admin123` | ✅ Acceso total<br>✅ Gestión de usuarios<br>✅ Configuración del sistema<br>✅ Todos los reportes |
| **Supervisor** | `supervisor@sistema.com` | `supervisor123` | ✅ Ver todos los reclamos asignados<br>✅ Asignar analistas<br>✅ Aprobar/rechazar reclamos<br>✅ Reportes de equipo |
| **Analista** | `analista@sistema.com` | `analista123` | ✅ Ver reclamos asignados<br>✅ Actualizar estado<br>✅ Subir documentos<br>✅ Crear resultados |

### 🔒 Matriz de Permisos

| Función | Admin | Supervisor | Analyst |
|---------|-------|------------|---------|
| Ver todos los reclamos | ✅ | ❌ | ❌ |
| Ver reclamos asignados | ✅ | ✅ | ✅ |
| Crear reclamos | ✅ | ✅ | ✅ |
| Editar reclamos | ✅ | ✅ | ⚠️ Solo asignados |
| Eliminar reclamos | ✅ | ⚠️ Solo propios | ❌ |
| Gestionar usuarios | ✅ | ❌ | ❌ |
| Gestionar pólizas | ✅ | ✅ | ❌ |
| Ver reportes completos | ✅ | ⚠️ De su equipo | ⚠️ Propios |
| Configurar sistema | ✅ | ❌ | ❌ |

⚠️ **Importante**: Cambiar estas credenciales antes de ir a producción.

---

## 📊 Datos Iniciales (Seed)

El seed (`database/seed.php`) crea automáticamente:

### ✅ **3 Roles**
- **Admin** - Acceso total al sistema
- **Supervisor** - Gestión de reclamos y equipos
- **Analyst** - Procesamiento de reclamos

### ✅ **6 Categorías de Reclamos**
- Auto - Accidentes vehiculares
- Hogar - Daños en propiedad residencial
- Vida - Seguros de vida
- Salud - Gastos médicos
- Robo - Pérdidas por robo
- Incendio - Daños por fuego

### ✅ **5 Estados de Reclamos**
- **Pending** (Pendiente) - Recién creado
- **In Review** (En Revisión) - Siendo evaluado
- **Approved** (Aprobado) - Aprobado para pago
- **Rejected** (Rechazado) - No procede
- **Closed** (Cerrado) - Finalizado

### ✅ **4 Tipos de Decisiones**
- **Approved** (Aprobado) - Reclamo aprobado completamente
- **Rejected** (Rechazado) - Reclamo denegado
- **Partial** (Parcial) - Aprobado parcialmente
- **Requires Info** (Requiere Información) - Necesita más datos

### ✅ **3 Usuarios de Prueba**
```
Admin:
- Email: admin@sistema.com
- Password: admin123
- Rol: Administrador

Supervisor:
- Email: supervisor@sistema.com
- Password: supervisor123
- Rol: Supervisor

Analista:
- Email: analista@sistema.com
- Password: analista123
- Rol: Analista
```

### ✅ **2 Pólizas de Ejemplo**
- Póliza de Auto (#POL-2024-001)
- Póliza de Hogar (#POL-2024-002)

### Ejecutar el Seed

```bash
php database/seed.php
```

O desde el navegador:
```
http://localhost/PROYECTO/database/seed.php
```

---

## 🔧 Comandos Útiles

### Reiniciar Base de Datos Completa

```sql
DROP DATABASE IF EXISTS proyecto_reclamos;
CREATE DATABASE proyecto_reclamos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Luego ejecutar:
```
http://localhost/PROYECTO/run-migrations.php
http://localhost/PROYECTO/database/seed.php
```

### Limpiar Solo Tablas (Mantener Estructura)

```
http://localhost/PROYECTO/database/drop-all-tables.php
```

Luego ejecutar migraciones y seed.

### Ver Estructura de Tablas

```sql
USE proyecto_reclamos;
SHOW TABLES;
DESCRIBE claims;
DESCRIBE users;
```

### Verificar Configuración

```
http://localhost/PROYECTO/test-config.php
http://localhost/PROYECTO/test-connection.php
http://localhost/PROYECTO/check-data.php
```

### Limpiar Sesiones

```php
// En navegador
http://localhost/PROYECTO/auth/logout.php

// O en terminal PHP
session_start();
session_destroy();
```

---

## 🐛 Troubleshooting (Solución de Problemas)

### Error: "Connection refused"

**Problema**: No se puede conectar a la base de datos.

**Solución**:
1. Verificar que MySQL/MariaDB esté corriendo
2. Revisar credenciales en `.env`
3. Verificar que la base de datos exista:
   ```sql
   SHOW DATABASES LIKE 'proyecto_reclamos';
   ```

### Error: "Access denied for user"

**Problema**: Credenciales incorrectas de base de datos.

**Solución**:
1. Verificar `.env`:
   ```env
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Probar conexión con phpMyAdmin o similar

### Error: "Table doesn't exist"

**Problema**: Migraciones no ejecutadas.

**Solución**:
```
http://localhost/PROYECTO/run-migrations.php
```

### Error: "Session already started"

**Problema**: Sesión iniciada múltiples veces.

**Solución**:
- Asegurarse de que `session_start()` solo se llame una vez
- Revisar que esté en `config.php` o al inicio del archivo

### Error: "Headers already sent"

**Problema**: Salida antes de header/redirect.

**Solución**:
- Verificar que no haya espacios antes de `<?php`
- Usar `ob_start()` al inicio del archivo
- Verificar que no haya `echo` antes de `header()`

### No se muestran estilos CSS

**Problema**: Ruta incorrecta a archivos CSS.

**Solución**:
1. Verificar `BASE_PATH` en `.env`
2. Usar función `asset()`:
   ```php
   <link rel="stylesheet" href="<?= asset('css/insurance-theme.css') ?>">
   ```

### Error 404 en módulos

**Problema**: Ruta incorrecta en URLs.

**Solución**:
- Usar función `url()` para todas las URLs:
  ```php
  <a href="<?= url('src/Claims/index.php') ?>">Reclamos</a>
  ```

### No aparecen datos del seed

**Problema**: Seed no ejecutado o falló.

**Solución**:
1. Ejecutar directamente:
   ```bash
   php database/seed.php
   ```
2. Revisar errores en la salida
3. Verificar que las migraciones estén completas

### Usuario no puede iniciar sesión

**Problema**: Hash de contraseña incorrecto o usuario inactivo.

**Solución**:
1. Verificar que el usuario esté activo:
   ```sql
   SELECT * FROM users WHERE email = 'admin@sistema.com';
   ```
2. Regenerar contraseña:
   ```php
   $hash = password_hash('admin123', PASSWORD_DEFAULT);
   ```

### Error: "Undefined function url()"

**Problema**: `helpers.php` no incluido.

**Solución**:
```php
require_once __DIR__ . '/../../includes/helpers.php';
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

### v2.1.0 - Sistema Completo (Actual - Diciembre 2024)
- ✅ 10 módulos completamente funcionales
- ✅ Sistema de gestión de archivos adjuntos
- ✅ Reportes y estadísticas avanzadas
- ✅ Control de acceso por roles (Admin, Supervisor, Analyst)
- ✅ Búsqueda y filtros dinámicos
- ✅ Dashboard personalizado por rol
- ✅ Sistema de migraciones y seeds
- ✅ Diseño responsive profesional
- ✅ Documentación completa

### v2.0.0 - Arquitectura MVC
- ✅ Implementación de patrón MVC simplificado
- ✅ Separación de responsabilidades (Models, Managers, Views)
- ✅ Vistas completas (index, create, edit, view)
- ✅ Diseño profesional de seguros
- ✅ Landing page moderna
- ✅ Sistema de helpers y utilidades

### v1.0.0 - Versión Inicial
- ✅ Estructura básica del proyecto
- ✅ CRUD de reclamos y pólizas
- ✅ Sistema de autenticación
- ✅ Dashboard con estadísticas básicas
- ✅ Base de datos inicial

---

## ❓ Preguntas Frecuentes (FAQ)

### ¿Puedo usar este proyecto para mi empresa?
Sí, es un proyecto educativo que puede ser adaptado para uso comercial. Se recomienda revisar y fortalecer aspectos de seguridad antes de producción.

### ¿Cómo agrego un nuevo módulo?
Sigue la guía en la sección [Crear un Nuevo Módulo](#crear-un-nuevo-módulo). La estructura es consistente en todos los módulos.

### ¿Necesito instalar Composer?
No es estrictamente necesario. El proyecto está diseñado para funcionar con PHP vanilla sin dependencias externas.

### ¿Puedo usar otro motor de base de datos?
El código usa PDO, por lo que con modificaciones mínimas podrías usar PostgreSQL, SQLite u otros motores compatibles.

### ¿Cómo cambio el diseño/tema?
Modifica el archivo `public/assets/css/insurance-theme.css`. Las variables CSS están en la parte superior para facilitar cambios de color.

### ¿Dónde están los logs de errores?
Actualmente los errores se muestran en pantalla (modo desarrollo). Para producción, configura el manejo de errores de PHP:
```php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

### ¿Cómo implemento envío de emails?
Puedes usar PHPMailer o la función `mail()` de PHP. Crea un `EmailManager` en `src/` para centralizar la lógica.

### ¿El sistema soporta múltiples idiomas?
Actualmente está en español. Para i18n, considera implementar un sistema de traducciones o usar gettext.

### ¿Hay documentación de la API?
Este es un sistema web tradicional (no API REST). Si necesitas una API, considera crear endpoints en formato JSON.

### ¿Cómo hago backup de la base de datos?
```bash
mysqldump -u root -p proyecto_reclamos > backup.sql

# Restaurar
mysql -u root -p proyecto_reclamos < backup.sql
```

---

## 🤝 Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guía de Estilo

- Usa PSR-12 para estilo de código PHP
- Comenta funciones complejas
- Mantén la consistencia con el código existente
- Escribe commits descriptivos en español
- Actualiza la documentación si es necesario

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

---

## 🎨 Extensión Recomendada para Visualización

Para visualizar este README de forma más atractiva en VS Code, instala:

**[Markdown Preview Enhanced](https://marketplace.visualstudio.com/items?itemName=shd101wyy.markdown-preview-enhanced)**

### Características:
- ✅ Vista previa en tiempo real
- ✅ Soporte para emojis y tablas
- ✅ Resaltado de sintaxis mejorado
- ✅ Exportación a PDF/HTML
- ✅ Gráficos y diagramas
- ✅ Scroll sincronizado

### Instalación:
1. Abre VS Code
2. Ve a Extensiones (`Ctrl+Shift+X`)
3. Busca "Markdown Preview Enhanced"
4. Instala la extensión de `shd101wyy`
5. Abre el README y presiona `Ctrl+K V` para vista previa lado a lado

### Alternativa:
**[Markdown All in One](https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one)** - Para edición y navegación mejorada

---

*Última actualización: 13 de diciembre de 2024*
