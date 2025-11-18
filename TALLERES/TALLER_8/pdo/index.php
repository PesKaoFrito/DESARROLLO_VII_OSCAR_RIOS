<?php
session_start();
require_once 'config.php';
require_once 'libros.php';
require_once 'usuarios.php';
require_once 'prestamos.php';

$libros = new Libros();
$usuarios = new Usuarios();
$prestamos = new Prestamos();

// Obtener parámetros de paginación
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
$porPagina = 10;

try {
    // Obtener datos para las diferentes secciones
    $listaLibros = $libros->listar($pagina, $porPagina);
    $listaPrestamos = $prestamos->listarPrestamosActivos($pagina, $porPagina);
} catch (PDOException $e) {
    $error = "Error al cargar los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca - PDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Biblioteca - PDO</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#libros">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestamos">Préstamos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#usuarios">Usuarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
       <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
    ?>
    <?php endif; ?>

        <!-- Sección de Libros -->
        <section id="libros" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Libros</h2>
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#agregarLibroModal">
                        <i class="bi bi-plus-lg"></i> Agregar Libro
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Disponibles</a></li>
                            <li><a class="dropdown-item" href="#">Más prestados</a></li>
                            <li><a class="dropdown-item" href="#">Recientes</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Disponibles</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaLibros['libros'] as $libro): ?>
                        <tr>
                            <td><?= htmlspecialchars($libro['id']) ?></td>
                            <td><?= htmlspecialchars($libro['titulo']) ?></td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><?= htmlspecialchars($libro['isbn']) ?></td>
                            <td>
                                <span class="badge bg-<?= $libro['cantidad_disponible'] > 0 ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars($libro['cantidad_disponible']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning" 
                                            onclick="editarLibro(<?= $libro['id'] ?>, 
                                                               '<?= htmlspecialchars($libro['titulo'], ENT_QUOTES) ?>', 
                                                               '<?= htmlspecialchars($libro['autor'], ENT_QUOTES) ?>', 
                                                               '<?= htmlspecialchars($libro['isbn'], ENT_QUOTES) ?>', 
                                                               <?= $libro['cantidad_disponible'] ?>)">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="confirmarEliminar(<?= $libro['id'] ?>, 
                                                                 '<?= htmlspecialchars($libro['titulo'], ENT_QUOTES) ?>')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($listaLibros['paginas'] > 1): ?>
            <nav aria-label="Paginación de libros">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $listaLibros['paginas']; $i++): ?>
                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $pagina >= $listaLibros['paginas'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </section>

        <!-- Sección de Préstamos -->
        <section id="prestamos" class="mb-5">
            <h2 class="mb-3">Préstamos Activos</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaPrestamos as $prestamo): ?>
                        <tr>
                            <td><?= htmlspecialchars($prestamo['id']) ?></td>
                            <td><?= htmlspecialchars($prestamo['usuario_nombre']) ?></td>
                            <td><?= htmlspecialchars($prestamo['libro_titulo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                            <td>
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars($prestamo['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success" title="Registrar devolución">
                                    <i class="bi bi-check-lg"></i> Devolver
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal Agregar Libro -->
    <div class="modal fade" id="agregarLibroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="procesar_libro.php" method="POST" class="needs-validation" novalidate>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                            <div class="invalid-feedback">El título es requerido</div>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                            <div class="invalid-feedback">El autor es requerido</div>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   pattern="\d{13}" title="13 dígitos" required>
                            <div class="invalid-feedback">ISBN debe tener 13 dígitos</div>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad Disponible</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" 
                                   min="0" required>
                            <div class="invalid-feedback">La cantidad debe ser mayor o igual a 0</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de formularios Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        function editarLibro(id, titulo, autor, isbn, cantidad) {
            // Llenar el formulario de edición con los datos del libro
            document.getElementById('titulo').value = titulo;
            document.getElementById('autor').value = autor;
            document.getElementById('isbn').value = isbn;
            document.getElementById('cantidad').value = cantidad;

            // Cambiar el título del modal
            var modalTitle = document.querySelector('#agregarLibroModal .modal-title');
            modalTitle.textContent = 'Editar Libro';

            // Mostrar el modal
            var modal = new bootstrap.Modal(document.getElementById('agregarLibroModal'));
            modal.show();
        }

        function confirmarEliminar(id, titulo) {
            if (confirm('¿Estás seguro de eliminar el libro: ' + titulo + '?')) {
                // Redirigir a la página de eliminación
                window.location.href = 'eliminar_libro.php?id=' + id;
            }
        }
    </script>
</body>
</html>