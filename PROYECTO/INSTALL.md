# 🚀 Guía Rápida de Instalación

## Pasos para ejecutar el proyecto

### 1️⃣ Configurar Base de Datos

```sql
CREATE DATABASE utp_proyecto_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2️⃣ Editar archivo .env

```env
BASE_URL=http://localhost/PROYECTO/

DB_HOST=localhost
DB_NAME=utp_proyecto_final
DB_USER=root
DB_PASS=
```

### 3️⃣ Ejecutar Migraciones

Acceder en el navegador a:
```
http://localhost/PROYECTO/run-migrations.php
```

### 4️⃣ Ejecutar Seed (Datos Iniciales)

Ejecutar desde terminal (PowerShell):
```powershell
cd C:\laragon\www\PROYECTO\database
php seed.php
```

O acceder directamente:
```
http://localhost/PROYECTO/database/seed.php
```

### 5️⃣ Acceder al Sistema

```
http://localhost/PROYECTO/
```

**Usuario de Prueba:**
- Email: `admin@sistema.com`
- Password: `admin123`

---

## 📦 Estructura de Datos Iniciales

El seed crea automáticamente:

✅ **Roles:**
- admin (Administrador)
- supervisor (Supervisor)
- analyst (Analista)

✅ **Categorías:**
- Auto, Hogar, Vida, Salud, Robo, Incendio

✅ **Estados:**
- pending, in-review, approved, rejected, closed

✅ **Decisiones:**
- approved, rejected, partial, requires-info

✅ **Usuario Admin:**
- Email: admin@sistema.com
- Password: admin123 (⚠️ Cambiar después)

✅ **Pólizas de Ejemplo:**
- 2 pólizas de prueba para testing

---

## 🔧 Comandos Útiles

### Reiniciar Base de Datos
```sql
DROP DATABASE IF EXISTS utp_proyecto_final;
CREATE DATABASE utp_proyecto_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Luego ejecutar nuevamente migraciones y seed.

### Ver Estructura de Tablas
```sql
USE utp_proyecto_final;
SHOW TABLES;
```

### Verificar Datos
```sql
SELECT * FROM users;
SELECT * FROM roles;
SELECT * FROM policies;
```

---

## 🎯 Funcionalidades Principales

1. **Registro/Login** → `auth/login.php` o `auth/register.php`
2. **Dashboard** → Vista principal con estadísticas
3. **Gestión de Reclamos** → `modules/claims/index.php`
4. **Gestión de Pólizas** → `modules/policies/index.php`
5. **Reportes** → `modules/reports/index.php`

---

## 🐛 Solución de Problemas Comunes

### Error: "Cannot connect to database"
- ✅ Verificar que MySQL esté ejecutándose
- ✅ Comprobar credenciales en `.env`
- ✅ Asegurar que la base de datos existe

### Error: "Class not found"
- ✅ Verificar rutas de includes
- ✅ Comprobar nombres de archivos (case-sensitive en algunos SO)

### Páginas sin estilos
- ✅ Verificar `BASE_URL` en `.env`
- ✅ Comprobar que archivos CSS existan en `public/assets/css/`

---

## 📱 Probar el Sistema

1. Hacer login con usuario admin
2. Crear una nueva póliza en "Pólizas"
3. Crear un reclamo asociado a esa póliza
4. Ver estadísticas en Dashboard
5. Generar reportes en módulo de Reportes

---

**¿Necesitas ayuda?** Revisa el archivo `README.md` completo para más detalles.
