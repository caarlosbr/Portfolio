<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Trabajo</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Añadir Nuevo Trabajo</h1>
    </header>
   <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/perfil">Perfil</a></li>
            <li><a href="/cerrarSesion">Cerrar Sesión</a></li>
        </ul>
    </nav> 

    <form action="/trabajo/anadir" method="post">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required >
        
        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        
        <label for="fecha_final">Fecha Final:</label>
        <input type="date" id="fecha_final" name="fecha_final">
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" cols="50" rows="20"></textarea>
        
        <input type="hidden" name="anadir_trabajo" value="1">
        <button type="submit">Guardar Trabajo</button>
    </form>
</body>
</html>
