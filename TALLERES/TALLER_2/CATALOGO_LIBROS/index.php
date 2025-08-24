<?php
    include 'includes/header.php';
    include 'includes/funciones.php';
    ?>
    <main class="catalog-grid">
<?php
    if(!isset($_POST['btnBuscar']) && empty($_POST['search'])) {
        $libros = obtenerLibros();
        foreach ($libros as $libro) {
            mostrarDetallesLibro($libro);
        }
    }
    else {
        $termino = trim($_POST['search'] ?? '');
        if($termino === '') {
            echo "<p class='no-results'>Por favor, ingresa un término de búsqueda.</p><br/>";
            $libros = obtenerLibros();
            foreach ($libros as $libro) {
            mostrarDetallesLibro($libro);
            }
        } else {
            $resultados = buscarLibros($termino);
            if(empty($resultados)) {
                echo "<p class='no-results'>No se encontraron resultados para '$termino'.</p>";
            } else {
                foreach ($resultados as $libro) {
                    mostrarDetallesLibro($libro);
                }
            }
        }
    }
?>
    

    <?php include 'includes/footer.php'; ?>