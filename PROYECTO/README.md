# 🛡️ Sistema de Gestión de Reclamos de Seguros

Sistema completo para la gestión y seguimiento de reclamos de seguros, desarrollado en **PHP Vanilla** (sin frameworks).

## 📋 Características Principales

### ✅ Funcionalidades Implementadas

1. **Registro de Pólizas y Clientes**
   - CRUD completo de pólizas
   - Gestión de datos de asegurados
   - Búsqueda y filtros avanzados
   - Estadísticas de cobertura

2. **Formulario de Reclamos**
   - Registro de nuevos reclamos
   - Asociación con pólizas existentes
   - Categorización por tipo de siniestro
   - Validación de datos completa

3. **Seguimiento de Casos**
   - Dashboard con estadísticas en tiempo real
   - Filtros por estado (pendiente, aprobado, rechazado)
   - Búsqueda por múltiples criterios
   - Asignación de analistas y supervisores

4. **Documentación de Evidencias**
   - Sistema de carga de archivos
   - Gestión de documentos por reclamo
   - Validación de tipos y tamaños

5. **Reportes de Siniestralidad**
   - Estadísticas generales del sistema
   - Análisis por categoría
   - Tendencias por mes
   - Métricas de pólizas
   - Exportación e impresión

## 🏗️ Arquitectura del Proyecto

```
PROYECTO/
├── auth/                    # Sistema de autenticación
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── database/
│   ├── MigrationManager.php
│   └── migrations/         # Migraciones SQL
│       ├── 001_create_roles_table.sql
│       ├── 002_create_categories_table.sql
│       ├── 003_create_decisions_table.sql
│       ├── 004_create_statuses_table.sql
│       ├── 005_create_claims_table.sql
│       ├── 006_create_users_table.sql
│       ├── 007_create_claimsresults_table.sql
│       ├── 008_create_claimfiles_table.sql
│       ├── 009_create_policies_table.sql
│       └── 010_update_claims_table.sql
├── includes/
│   ├── auth.php           # Funciones de autenticación
│   └── helpers.php        # Funciones auxiliares
├── modules/
│   ├── claims/           # Módulo de reclamos
│   │   ├── index.php
│   │   └── create.php
│   ├── policies/         # Módulo de pólizas
│   │   └── index.php
│   ├── reports/          # Módulo de reportes
│   │   └── index.php
│   └── users/            # Gestión de usuarios
├── public/
│   └── assets/
│       ├── css/
│       │   ├── style.css
│       │   └── app.css
│       └── js/
│           └── main.js
├── src/                  # Clases del modelo
│   ├── Database.php
│   ├── Categories/
│   │   ├── Category.php
│   │   └── CategoryManager.php
│   ├── Claims/
│   │   ├── Claim.php
│   │   └── ClaimManager.php
│   ├── Policies/
│   │   ├── Policy.php
│   │   └── PolicyManager.php
│   ├── Users/
│   │   ├── User.php
│   │   └── UserManager.php
│   └── ... (otros modelos)
├── views/
│   └── layout.php        # Template principal
├── .env                  # Configuración de entorno
├── config.php           # Configuración global
├── dashboard.php        # Dashboard principal
├── index.php            # Punto de entrada
└── run-migrations.php   # Ejecutor de migraciones

```

## 🚀 Instalación y Configuración

### Requisitos Previos

- PHP >= 7.4
- MySQL >= 5.7 o MariaDB
- Servidor web (Apache/Nginx) con mod_rewrite habilitado
- Laragon, XAMPP, WAMP o similar (recomendado)

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   cd C:\laragon\www
   git clone <repository-url> PROYECTO
   ```

2. **Configurar la base de datos**
   
   Editar el archivo `.env`:
   ```env
   BASE_URL=http://localhost/PROYECTO/
   
   DB_HOST=localhost
   DB_NAME=utp_proyecto_final
   DB_USER=root
   DB_PASS=
   ```

3. **Crear la base de datos**
   ```sql
   CREATE DATABASE utp_proyecto_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. **Ejecutar las migraciones**
   
   Acceder a través del navegador:
   ```
   http://localhost/PROYECTO/run-migrations.php
   ```
   
   O ejecutar desde línea de comandos:
   ```bash
   php run-migrations.php
   ```

5. **Datos iniciales (Seed)**
   
   Insertar roles básicos:
   ```sql
   INSERT INTO roles (name, description) VALUES
   ('admin', 'Administrador del sistema'),
   ('supervisor', 'Supervisor de reclamos'),
   ('analyst', 'Analista de reclamos');
   ```
   
   Insertar categorías:
   ```sql
   INSERT INTO categories (name, description) VALUES
   ('Auto', 'Reclamos de vehículos'),
   ('Hogar', 'Reclamos de propiedad'),
   ('Vida', 'Reclamos de seguros de vida'),
   ('Salud', 'Reclamos médicos');
   ```
   
   Insertar estados:
   ```sql
   INSERT INTO statuses (name, description) VALUES
   ('pending', 'Pendiente de revisión'),
   ('in-review', 'En revisión'),
   ('approved', 'Aprobado'),
   ('rejected', 'Rechazado');
   ```
   
   Insertar decisiones:
   ```sql
   INSERT INTO decisions (name, description) VALUES
   ('approved', 'Reclamo aprobado'),
   ('rejected', 'Reclamo rechazado'),
   ('partial', 'Aprobación parcial');
   ```

6. **Acceder al sistema**
   ```
   http://localhost/PROYECTO/
   ```
   
   La primera vez redirigirá al registro. Crear una cuenta con email y contraseña.

## 👥 Roles y Permisos

| Rol | Permisos |
|-----|----------|
| **Admin** | Acceso completo al sistema, gestión de usuarios |
| **Supervisor** | Gestión de reclamos, asignación de analistas, reportes |
| **Analyst** | Creación y seguimiento de reclamos, documentación |

## 📊 Módulos del Sistema

### Dashboard
- Estadísticas generales
- Reclamos recientes
- Accesos rápidos
- Métricas en tiempo real

### Gestión de Reclamos
- Listado con filtros
- Creación de nuevos reclamos
- Edición y seguimiento
- Cambio de estados
- Carga de documentos

### Gestión de Pólizas
- Registro de pólizas
- Datos de asegurados
- Vigencias y coberturas
- Búsqueda avanzada

### Reportes
- Estadísticas por categoría
- Tendencias temporales
- Métricas de pólizas
- Exportación de datos

## 🔒 Seguridad

- **Autenticación basada en sesiones PHP**
- **Contraseñas hasheadas con password_hash()**
- **Validación de entrada con funciones sanitize()**
- **Protección contra SQL Injection (PDO + Prepared Statements)**
- **Control de acceso basado en roles**
- **Tokens CSRF (implementar para producción)**

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+ (Vanilla, sin frameworks)
- **Base de Datos:** MySQL/MariaDB con PDO
- **Frontend:** HTML5, CSS3 (Grid/Flexbox), JavaScript Vanilla
- **Patrón de Diseño:** MVC simplificado
- **Arquitectura:** Separación de responsabilidades (Models, Views, Controllers)

## 📝 Notas de Desarrollo

### Estructura de Clases

Cada entidad del sistema sigue el patrón:
- **Clase Modelo**: Representa la entidad (ej. `Claim.php`)
- **Clase Manager**: Gestiona operaciones CRUD (ej. `ClaimManager.php`)
- **Vistas**: Archivos PHP que renderizan HTML
- **Layout**: Template principal con navbar y estructura

### Conexión a Base de Datos

Se utiliza el patrón Singleton para la clase `Database`:
```php
$db = Database::getInstance()->getConnection();
```

### Sistema de Rutas

El proyecto usa rutas basadas en archivos:
- `/modules/<modulo>/index.php` - Listado
- `/modules/<modulo>/create.php` - Formulario de creación
- `/modules/<modulo>/view.php?id=X` - Vista detallada
- `/modules/<modulo>/edit.php?id=X` - Edición

## 🐛 Resolución de Problemas

### Error: "Cannot connect to database"
- Verificar credenciales en `.env`
- Asegurar que MySQL esté ejecutándose
- Comprobar que la base de datos existe

### Error: "Class not found"
- Verificar includes en los archivos PHP
- Comprobar rutas relativas correctas

### Estilos no se cargan
- Verificar `BASE_URL` y `PUBLIC_URL` en `config.php`
- Comprobar que mod_rewrite esté habilitado

## 📚 Próximas Mejoras

- [ ] Implementar sistema de notificaciones por email
- [ ] Agregar carga masiva de archivos
- [ ] Exportación a Excel/PDF de reportes
- [ ] API REST para integraciones
- [ ] Sistema de auditoría de cambios
- [ ] Dashboard con gráficos interactivos (Chart.js)
- [ ] Implementar tokens CSRF
- [ ] Sistema de permisos granulares

## 👨‍💻 Autor

**Oscar Ríos**  
Desarrollo VII - Universidad Tecnológica de Panamá

## 📄 Licencia

Este proyecto es parte de un trabajo académico para el curso de Desarrollo VII.

---

**Fecha de Entrega:** Noviembre 2025  
**Versión:** 1.0.0
