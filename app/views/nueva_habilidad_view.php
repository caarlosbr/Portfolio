<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Habilidad</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Añadir Nueva Habilidad</h1>
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

    <form action="/skill/anadir" method="post">
        <label for="habilidades">Habilidad:</label>
        <input type="text" id="habilidades" name="habilidades" required>

        <label for="categoria">Categoría:</label>
        <input type="text" id="categoria" name="categoria" required>

        <button type="submit">Guardar Habilidad</button>
    </form>

</body>
</html>
