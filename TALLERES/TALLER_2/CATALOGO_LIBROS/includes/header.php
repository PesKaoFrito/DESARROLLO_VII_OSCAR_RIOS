<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros</title>
</head>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
/* Estilo moderno para catálogo de libros 
Arregla la indentación, porque el autor queda muy separado del titulo y las etiquetas también */
:root{
    --bg:#f5f7fb;
    --card:#ffffff;
    --muted:#6b7280;
    --accent:#2563eb;
    --glass: rgba(255,255,255,0.6);
    --shadow: 0 6px 18px rgba(15,23,42,0.08);
    --radius:12px;
}

*{box-sizing:border-box}
html,body{height:100%}
body{
    margin:0;
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    background: linear-gradient(180deg, var(--bg) 0%, #eef2ff 100%);
    color:#0f172a;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
}

/* Encabezado */
h1{
    margin:28px auto 8px;
    text-align:center;
    font-weight:700;
    font-size:1.75rem;
    color:#0b1220;
    letter-spacing:-0.02em;
}

/* Subtexto bajo el título */
p{
    margin:0 auto 24px;
    text-align:center;
    color:var(--muted);
    max-width:760px;
    padding:0 16px;
}

/* Contenedor principal */
main{
    max-width:1100px;
    margin:0 auto 64px;
    padding:0 16px;
}

/* Barra de búsqueda y filtros */
.search-row{
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:center;
    flex-wrap:wrap;
    margin-bottom:20px;
}

/* El formulario ahora es flex: input dentro de .search y el botón es hermano */
.search-row form{
    display:flex;
    gap:12px;
    align-items:center;
    width:100%;
    max-width:820px;
    justify-content:center;
}

/* Caja redondeada que contiene solo el input */
.search-row .search{
    flex:1 1 420px;
    max-width:700px;
    display:flex;
    background:var(--card);
    border-radius:999px;
    padding:8px 12px;
    box-shadow:var(--shadow);
    align-items:center;
}

/* Input dentro de la caja */
.search-row input[type="search"]{
    border:0;
    outline:0;
    padding:10px 12px;
    font-size:0.95rem;
    background:transparent;
    width:100%;
}

/* Botón colocado fuera del campo y al lado */
.btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:var(--accent);
    color:#fff;
    border:0;
    padding:10px 14px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    box-shadow:0 6px 12px rgba(37,99,235,0.12);
}

/* Rejilla de libros */
.catalog-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 420px));
    gap:36px;                   /* separación entre tarjetas */
    justify-content:center;
    align-items:start;
    padding:24px;
    width:100%;
    max-width:1100px;
    margin:0 auto;
}

/* Tarjeta de libro: más ancha y mayor padding interno */
.book-card{
    width:100%;
    max-width:420px;            /* tarjetas más anchas */
    background:linear-gradient(180deg, rgba(255,255,255,0.95), var(--card));
    border-radius:var(--radius);
    padding:18px;               /* más padding interno */
    box-shadow:var(--shadow);
    transition:transform .18s ease, box-shadow .18s ease;
    display:flex;
    gap:10px;
    align-items:flex-start;
    margin:0 auto;
}

/* Portada ligeramente mayor para equilibrar */
.book-cover{
    width:96px;
    min-width:96px;
    height:140px;
    background:linear-gradient(180deg,#e6eefc,#fff);
    border-radius:8px;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:inset 0 -10px 30px rgba(37,99,235,0.03);
}
.book-cover img{ width:100%; height:100%; object-fit:cover; }

/* Info del libro: más espacio entre elementos para que el resumen respire */
.book-info{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:8px;                /* más espacio vertical entre título/autor/meta/resumen */
    justify-content:flex-start;
    align-items:flex-start;
}
.book-title{
    font-size:1rem;
    font-weight:700;
    color:#071033;
    margin:0;
}
.book-author{
    font-size:0.88rem;
    color:var(--muted);
    margin:0;
}
.book-meta{
    margin-top:auto;
    display:flex;
    gap:8px;
    align-items:center;
    justify-content:flex-start;
    flex-wrap:wrap;
}
.link-title{
    color:#1a56db;
    text-decoration:none;
    font-weight:700;
}
.link-title:hover{ text-decoration:underline; }
.link-author{
    color:#1b6be0;
    text-decoration:none;
    font-weight:600;
}
.book-description{
    color:var(--muted);
    font-size:0.95rem;
    margin:8px 0 0;
    line-height:1.4;
}
.pill{
    font-size:0.78rem;
    color:var(--muted);
    background:rgba(15,23,42,0.04);
    padding:5px 7px;
    border-radius:999px;
}
/* Responsive tweaks */
@media (max-width:520px){
    .search-row form{ width:100%; padding:0 8px; }
    .book-card{padding:12px}
    .book-cover{width:72px;height:104px;min-width:72px}
    h1{font-size:1.35rem}
    .search-row{padding:0 8px}
}
footer{
    text-align:center;
    padding:16px 8px;
    font-size:0.85rem;
    color:var(--muted);
    border-top:1px solid rgba(15,23,42,0.1);
}

</style>
<body>
    <h1>Bienvenido al Catálogo de Libros</h1>
    <p>Aquí encontrarás una selección de libros disponibles.</p>
    <header class="search-row">
        <form action="" method="post">
            <div class="search">
                <input type="search" placeholder="Buscar libros..." name="search" />
            </div>
            <button type="submit" name="btnBuscar" class="btn">Buscar</button>
        </form>
    </header>