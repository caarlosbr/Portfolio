<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Proyecto</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Añadir Nuevo Proyecto</h1>
    </header>    
    <!-- Mostrar mensajes de error o éxito -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <p><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/perfil">Perfil</a></li>
            <li><a href="/cerrarSesion">Cerrar Sesión</a></li>
        </ul>
    </nav> 

    <form action="/proyecto/anadir" method="post">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" cols="50" rows="20"></textarea>

        <button type="submit">Guardar Proyecto</button>
    </form>

</body>
</html>
