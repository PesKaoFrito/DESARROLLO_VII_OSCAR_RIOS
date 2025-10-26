# Sistema de Gestión de Biblioteca

## Estructura de la Base de Datos

```sql
CREATE DATABASE biblioteca_db;
USE biblioteca_db;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE libros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    isbn VARCHAR(13) UNIQUE,
    anio_publicacion INT,
    cantidad_disponible INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE prestamos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    libro_id INT,
    fecha_prestamo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion TIMESTAMP NULL,
    estado ENUM('activo', 'devuelto', 'vencido') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);
```

## Configuración

1. Crear la base de datos usando el script SQL anterior
2. Configurar los archivos config.php en las carpetas mysqli y pdo con los datos de conexión
3. Asegurarse de que PHP tenga las extensiones mysqli y pdo_mysql habilitadas

## Estructura del Proyecto

```
TALLER_8/
├── mysqli/                 # Implementación usando MySQLi
│   ├── config.php         # Configuración de la conexión
│   ├── libros.php         # Gestión de libros
│   ├── usuarios.php       # Gestión de usuarios
│   ├── prestamos.php      # Gestión de préstamos
│   └── index.php          # Página principal
├── pdo/                   # Implementación usando PDO
│   ├── config.php         # Configuración de la conexión
│   ├── libros.php         # Gestión de libros
│   ├── usuarios.php       # Gestión de usuarios
│   ├── prestamos.php      # Gestión de préstamos
│   └── index.php          # Página principal
└── README.md              # Este archivo
```

## Consideraciones

- Todas las operaciones de base de datos usan consultas preparadas
- Se implementa paginación en las listas
- Se utiliza manejo de errores y logging
- Las operaciones críticas usan transacciones

## Comparación MySQLi vs PDO

### MySQLi
- Ventajas:
  - Específico para MySQL
  - Sintaxis más directa
  - Soporte para procedimientos almacenados
- Desventajas:
  - Solo funciona con MySQL
  - API menos consistente

### PDO
- Ventajas:
  - Soporta múltiples bases de datos
  - API más consistente
  - Manejo de errores más robusto
- Desventajas:
  - Curva de aprendizaje inicial más pronunciada
  - Configuración adicional para algunas características