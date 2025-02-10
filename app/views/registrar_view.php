<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="../css/login.css">
    <script>
        function validarFormulario() {
            var password = document.getElementById('password').value;
            var repetirPassword = document.getElementById('repetir_password').value;

            if (password !== repetirPassword) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <header>
        <h1>Bienvenido!</h1>
    </header>    
    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/login">Iniciar Sesion</a></li>
        </ul>
    </nav>
    <form method="post" action="/registrar" enctype="multipart/form-data" onsubmit="return validarFormulario()">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" required>
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="repetir_password">Repetir Contraseña:</label>
        <input type="password" name="repetir_password" id="repetir_password" required>
        <br>
        <label for="foto">Foto de Perfil:</label>
        <input type="file" name="foto" id="foto">
        <br>
        <button type="submit" name="submit">Registrar</button>
    </form>
</body>
</html>