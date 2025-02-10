<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Habilidad</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<header>
    <h1>Editar Habilidad!</h1>
</header>    
<nav>
    <ul>
        <li><a href="/">Inicio</a></li>
        <li><a href="/login">Iniciar Sesion</a></li>
    </ul>
</nav>

<form action="/skill/editar/<?= htmlspecialchars($data['skill']['id'] ?? '') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['skill']['id'] ?? '') ?>">
    
    <label for="habilidades">Habilidad:</label>
    <input type="text" id="habilidades" name="habilidades" value="<?= htmlspecialchars($data['skill']['habilidades'] ?? '') ?>" required><br>

    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>