<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Red Social</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Añadir Nueva Red Social</h1>
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

    <form action="/redsocial/anadir" method="post">
        <label for="url">URL:</label>
        <input type="text" id="url" name="url" required>

        <label for="redes_sociales">Nombre de la Red Social:</label>
        <input type="text" id="redes_sociales" name="redes_sociales" required>

        <button type="submit">Guardar Red Social</button>
    </form>

</body>
</html>
