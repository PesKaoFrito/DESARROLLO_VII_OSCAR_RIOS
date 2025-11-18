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
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10;

// Obtener datos para las diferentes secciones
$listaLibros = $libros->listar($pagina, $porPagina);
$listaPrestamos = $prestamos->listarPrestamosActivos($pagina, $porPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca - MySQLi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Biblioteca - MySQLi</a>
        </div>
    </nav>

    <div class="container my-4">
        <!-- Sección de Libros -->
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
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Libros</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarLibroModal">
                    Agregar Libro
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
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
                            <td><?= htmlspecialchars($libro['cantidad_disponible']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning" 
                                            onclick="editarLibro(<?= $libro['id'] ?>, '<?= htmlspecialchars($libro['titulo']) ?>', 
                                                     '<?= htmlspecialchars($libro['autor']) ?>', '<?= htmlspecialchars($libro['isbn']) ?>', 
                                                     <?= $libro['cantidad_disponible'] ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="confirmarEliminar(<?= $libro['id'] ?>, '<?= htmlspecialchars($libro['titulo']) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación de Libros -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $listaLibros['paginas']; $i++): ?>
                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </section>

        <!-- Sección de Préstamos Activos -->
        <section class="mb-5">
            <h2 class="mb-3">Préstamos Activos</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaPrestamos as $prestamo): ?>
                        <tr>
                            <td><?= htmlspecialchars($prestamo['id']) ?></td>
                            <td><?= htmlspecialchars($prestamo['usuario_nombre']) ?></td>
                            <td><?= htmlspecialchars($prestamo['libro_titulo']) ?></td>
                            <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-success">Devolver</button>
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
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad Disponible</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
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

    <!-- Modal Editar Libro -->
    <div class="modal fade" id="editarLibroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="editar_libro.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="edit_autor" name="autor" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="edit_isbn" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cantidad" class="form-label">Cantidad Disponible</label>
                            <input type="number" class="form-control" id="edit_cantidad" name="cantidad" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
function editarLibro(id, titulo, autor, isbn, cantidad) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_titulo').value = titulo;
    document.getElementById('edit_autor').value = autor;
    document.getElementById('edit_isbn').value = isbn;
    document.getElementById('edit_cantidad').value = cantidad;
    
    new bootstrap.Modal(document.getElementById('editarLibroModal')).show();
}

function confirmarEliminar(id, titulo) {
    if (confirm(`¿Está seguro que desea eliminar el libro "${titulo}"?`)) {
        window.location.href = `eliminar_libro.php?id=${id}`;
    }
}
</script>
</body>
</html>