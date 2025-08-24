<?php
    function obtenerLibros() {
        return [
            [
                'titulo' => 'Don Quijote de la Mancha',
                'autor' => 'Miguel de Cervantes',
                'anio_publicacion' => 1605,
                'genero' => 'Novela',
                'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0142437239.01._SX450_SY635_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'Cien años de soledad',
                'autor' => 'Gabriel García Márquez',
                'anio_publicacion' => 1967,
                'genero' => 'Realismo mágico',
                'descripcion' => 'La saga de la familia Buendía en el mítico Macondo.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0060883286.01._SX450_SY635_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'La sombra del viento',
                'autor' => 'Carlos Ruiz Zafón',
                'anio_publicacion' => 2001,
                'genero' => 'Misterio',
                'descripcion' => 'Un joven descubre un libro que cambia su vida en la Barcelona de posguerra.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0143034901.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'El amor en los tiempos del cólera',
                'autor' => 'Gabriel García Márquez',
                'anio_publicacion' => 1985,
                'genero' => 'Novela romántica',
                'descripcion' => 'Una historia de amor que perdura a lo largo de décadas.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0307389731.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'Ficciones',
                'autor' => 'Jorge Luis Borges',
                'anio_publicacion' => 1944,
                'genero' => 'Relatos',
                'descripcion' => 'Colección de cuentos breves y filosóficos que juegan con la realidad y la ficción.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0802130305.01._SX180_SCLZZZZZZZ_.jpg'  
            ],
            [
                'titulo' => 'La casa de los espíritus',
                'autor' => 'Isabel Allende',
                'anio_publicacion' => 1982,
                'genero' => 'Novela',
                'descripcion' => 'Sigue la saga familiar de los Trueba, entre lo político y lo sobrenatural.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0553383809.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'Rayuela',
                'autor' => 'Julio Cortázar',
                'anio_publicacion' => 1963,
                'genero' => 'Experimental',
                'descripcion' => 'Novela innovadora que invita a ser leída en distintos órdenes.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0394752848.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'Pedro Páramo',
                'autor' => 'Juan Rulfo',
                'anio_publicacion' => 1955,
                'genero' => 'Novela',
                'descripcion' => 'Relato corto y atmosférico sobre un pueblo habitado por voces y recuerdos.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/0802133908.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'El túnel',
                'autor' => 'Ernesto Sábato',
                'anio_publicacion' => 1948,
                'genero' => 'Thriller psicológico',
                'descripcion' => 'Monólogo de un hombre obsesionado que narra su crimen.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/8437600898.01._SX180_SCLZZZZZZZ_.jpg'
            ],
            [
                'titulo' => 'La ciudad y los perros',
                'autor' => 'Mario Vargas Llosa',
                'anio_publicacion' => 1963,
                'genero' => 'Novela',
                'descripcion' => 'Crítica a la violencia y la disciplina en una academia militar.',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/P/8466309152.01._SX180_SCLZZZZZZZ_.jpg'
            ],
        ];
    }

    
    function obtenerEnlaceWikipedia($valor) {
        return "https://es.wikipedia.org/wiki/" . str_replace(' ', '_', $valor);
    }


    function mostrarDetallesLibro($libro){
        echo "<div class='book-card'>";
            echo "<div class='book-cover'><img src='" . htmlspecialchars($libro['portada'], ENT_QUOTES) . "' alt='Portada de " . htmlspecialchars($libro['titulo'], ENT_QUOTES) . "' /></div>";
            echo "<div class='book-info'>";
                // Título como enlace visible y en azul
                echo "<h2 class='book-title'><a href='" . obtenerEnlaceWikipedia($libro['titulo']) . "' class='link-title'>" . htmlspecialchars($libro['titulo'], ENT_QUOTES) . "</a></h2>";
                // Línea del autor como enlace
                echo "<p class='book-author'>por <a href='" . obtenerEnlaceWikipedia($libro['autor']) . "' class='link-author'>" . htmlspecialchars($libro['autor'], ENT_QUOTES) . "</a></p>";
                // Meta en tamaño pequeño
                echo "<p class='book-meta'><span class='pill'>" . htmlspecialchars($libro['anio_publicacion'], ENT_QUOTES) . "</span><span class='pill'>" . htmlspecialchars($libro['genero'], ENT_QUOTES) . "</span></p>";
                // Descripción breve
                echo "<p class='book-description'>" . htmlspecialchars($libro['descripcion'], ENT_QUOTES) . "</p>";
            echo "</div>";
        echo "</div>";
    }    
    function buscarLibros($termino) {
        $libros = obtenerLibros();
        return array_filter($libros, function($libro) use ($termino) {
            return stripos(haystack: $libro['titulo'], needle: $termino) !== false || stripos($libro['autor'], $termino) !== false;
        });
    }
?>