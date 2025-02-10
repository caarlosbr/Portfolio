<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Red Social</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<header>
    <h1>Editar Red Social!</h1>
</header>    
<nav>
    <ul>
        <li><a href="/">Inicio</a></li>
        <li><a href="/login">Iniciar Sesion</a></li>
    </ul>
</nav>

<form action="/redsocial/editar/<?= htmlspecialchars($data['redSocial']['id'] ?? '') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['redSocial']['id'] ?? '') ?>">
    
    <label for="redes_sociales">Red Social:</label>
    <input type="text" id="redes_sociales" name="redes_sociales" value="<?= htmlspecialchars($data['redSocial']['redes_sociales'] ?? '') ?>" required><br>

    <label for="url">URL:</label>
    <input type="text" id="url" name="url" value="<?= htmlspecialchars($data['redSocial']['url'] ?? '') ?>" required><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>