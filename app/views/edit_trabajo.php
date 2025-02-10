<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Trabajo</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<header>
    <h1>Editar Trabajo!</h1>
</header>    
<nav>
    <ul>
        <li><a href="/">Inicio</a></li>
        <li><a href="/login">Iniciar Sesion</a></li>
    </ul>
</nav>

<form action="/trabajo/editar/<?= htmlspecialchars($data['trabajo']['id'] ?? '') ?>" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['trabajo']['id'] ?? '') ?>">
    
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($data['trabajo']['titulo'] ?? '') ?>" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($data['trabajo']['descripcion'] ?? '') ?></textarea><br>

    <label for="fecha_inicio">Fecha de Inicio:</label>
    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($data['trabajo']['fecha_inicio'] ?? '') ?>" required><br>

    <label for="fecha_final">Fecha Final:</label>
    <input type="date" id="fecha_final" name="fecha_final" value="<?= htmlspecialchars($data['trabajo']['fecha_final'] ?? '') ?>" required><br>


    <button type="submit">Guardar Cambios</button>
</form>
</body>
</html>