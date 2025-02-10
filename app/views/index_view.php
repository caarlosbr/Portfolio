<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$usuarios = $data['usuarios'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porfolio</title>
    <link rel="stylesheet" href="../css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php if (isset($_SESSION['auth']) && $_SESSION['auth']) {
            echo '<h1>Bienvenido ' . htmlspecialchars($_SESSION['usuario'] ?? 'Invitado') . '</h1>';
        } else {
            echo '<h1>Bienvenido Invitado</h1>';
        }
        ?>
    </header>

    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <?php if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
                echo '<li><a href="/login">Iniciar sesión</a></li>';
                echo '<li><a href="/registrar">Registrarse</a></li>';
            }
            ?>
            <?php if (isset($_SESSION['auth']) && $_SESSION['auth']) {
                echo '<li><a href="/cerrarsesion">Cerrar Sesión</a></li>';
            }
            ?>
            <?php
            if (isset($_SESSION['auth']) && $_SESSION['auth']) {
                echo '<li class="perfil">' . htmlspecialchars($_SESSION['usuario'] ?? 'Invitado') . '</li>';
                echo '<li><a href="/perfil">Editar Mi Perfil</a></li>';
            } else {
                echo '<li class="perfil">Invitado</li>';
            }
            ?>
            <form action="http://portfolio.local/buscar" method="get">
                <input type="text" name="q" id="q" placeholder="Buscar usuario" required>
                <button type="submit">Buscar</button>
            </form>
        </ul>
    </nav>

    <section id="home">
        <h2>Lista de porfolios</h2>
    </section>

    <section id="usuarios">
        <div class="usuarios-container">
            <?php foreach ($usuarios as $usuario): ?>
                <div class="usuario-card">
                    <?php
                    if (!empty($usuario['foto'])) {
                        echo "<img src='" . htmlspecialchars($usuario['foto']) . "' alt='Foto de perfil' width='150'>";
                    } else {
                        echo "<img src='/uploads/default.jpg' alt='Foto por defecto' width='150'>";
                    }
                    ?>

                    <div class="usuario-info">
                        <h3><?= htmlspecialchars($usuario['nombre']) ?> <?= htmlspecialchars($usuario['apellidos']) ?></h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                        <p><strong>Categoría profesional:</strong>
                            <?= htmlspecialchars($usuario['categoria_profesional'] ?? 'Sin categoría profesional') ?></p>
                        <p><strong>Resumen:</strong> <?= htmlspecialchars($usuario['resumen_perfil']) ?></p>
                    </div>

                    <div class="usuario-trabajos">
                        <h4>Trabajos:</h4>
                        <ul>
                            <?php if (!empty($usuario['trabajos'])): ?>
                                <?php foreach ($usuario['trabajos'] as $trabajo): ?>
                                    <?php if ($trabajo['visible']): ?>
                                        <li><?= htmlspecialchars($trabajo['titulo']) ?> |
                                            <?= htmlspecialchars($trabajo['fecha_inicio']) ?> -
                                            <?= htmlspecialchars($trabajo['fecha_final']) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No hay trabajos disponibles.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="usuario-proyectos">
                        <h4>Proyectos:</h4>
                        <ul>
                            <?php if (!empty($usuario['proyectos'])): ?>
                                <?php foreach ($usuario['proyectos'] as $proyecto): ?>
                                    <?php if ($proyecto['visible']): ?>
                                        <li><?= htmlspecialchars($proyecto['titulo']) ?> |
                                            <?= htmlspecialchars($proyecto['descripcion']) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No hay proyectos disponibles.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="usuario-redes-sociales">
                        <h4>Redes Sociales:</h4>
                        <ul>
                            <?php if (!empty($usuario['redes'])): ?>
                                <?php foreach ($usuario['redes'] as $red_social): ?>
                                    <?php if ($red_social['visible']): ?>
                                        <li><a href="<?= htmlspecialchars($red_social['url']) ?>"
                                                target="_blank"><?= htmlspecialchars($red_social['url']) ?></a></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No hay redes sociales disponibles.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="usuario-habilidades">
                        <h4>Habilidades:</h4>
                        <ul>
                            <?php if (!empty($usuario['skills'])): ?>
                                <?php foreach ($usuario['skills'] as $habilidad): ?>
                                    <?php if ($habilidad['visible']): ?>
                                        <li><?= htmlspecialchars($habilidad['habilidades']) ?> |
                                            <?= htmlspecialchars($habilidad['categorias_skills_categoria']) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No hay habilidades disponibles.</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="usuario-acciones">
                        <!-- Botón para ocultar el portfolio -->
                        <form action="/usuario/ocultar/<?= $usuario['id'] ?>" method="POST">
                            <button type="submit">Ocultar Portfolio</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        <p>Porfolio</p>
    </footer>

    <script>
        const form = document.querySelector('form[action="/buscar"]');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const query = document.querySelector('#q').value.trim();
            if (query) {
                window.location.href = `/buscar/${encodeURIComponent(query)}`;
            }
        });
    </script>

</body>

</html>