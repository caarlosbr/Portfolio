<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<header>
    <h1>Editar Proyecto!</h1>
</header>    
<nav>
    <ul>
        <li><a href="/">Inicio</a></li>
        <li><a href="/login">Iniciar Sesion</a></li>
    </ul>
</nav>

<form action="/proyecto/editar/<?= htmlspecialchars($data['proyecto']['id'] ?? '') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['proyecto']['id'] ?? '') ?>">
    
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($data['proyecto']['titulo'] ?? '') ?>" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($data['proyecto']['descripcion'] ?? '') ?></textarea><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>