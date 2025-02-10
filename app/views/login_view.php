<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Iniciar Sesión</h1>
    </header>
    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/registrar">Registrar</a></li>
        </ul>
    </nav>
    <form method="post" action="/login">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit" name="submit">Iniciar Sesión</button>
    </form>
</body>
</html>