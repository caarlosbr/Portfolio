<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil_view.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Perfil</title>
</head>

<body>
    <header>
        <h1>Ajustes del perfil</h1>
    </header>

    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <?php if (!$data['auth']): ?>
                <li><a href="/login">Loguearse</a></li>
                <li><a href="/registrar">Registrarse</a></li>
            <?php else: ?>
                <li><a href="/cerrarsesion">Cerrar Sesión</a></li>
                <li class="perfil"><a href="/perfil">Perfil: <?= htmlspecialchars($_SESSION['usuario'] ?? 'Invitado') ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <section id="perfil">
            <div class="perfil-container">
                <div class="perfil-card">
                    <div class="perfil-info">
                        <?php $foto = $data['usuario']['foto'] ?? 'uploads/default.jpg'; ?>
                        <img class="imgperfil" src="<?= htmlspecialchars($foto) ?>" alt="Foto de perfil">
                        <h2><?= htmlspecialchars($data['usuario']['nombre'] ?? 'Usuario Desconocido') ?></h2>
                        <p><strong>Email:</strong> <?= htmlspecialchars($data['usuario']['email'] ?? 'No disponible') ?>
                        </p>
                        <p><strong>Categoría profesional:</strong>
                            <?= htmlspecialchars($data['usuario']['categoria_profesional'] ?? 'Sin categoría') ?></p>
                        <p><strong>Resumen:</strong>
                            <?= htmlspecialchars($data['usuario']['resumen_perfil'] ?? 'Sin información') ?></p>
                    </div>

                    <hr>

                    <div class="perfil-edit">
                        <h3>Editar Perfil</h3>
                        <form action="/perfil" method="post" enctype="multipart/form-data">
                            <label for="foto_perfil">Foto de Perfil:</label>
                            <input type="file" id="foto_perfil" name="foto_perfil">

                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($data['usuario']['nombre']) ?>" required>

                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos"
                                value="<?= htmlspecialchars($data['usuario']['apellidos'] ?? '') ?>">

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($data['usuario']['email']) ?>" required>

                            <label for="categoria_profesional">Categoría Profesional:</label>
                            <select name="categoria_profesional" id="categoria_profesional">
                                <option value="Desarrollador"
                                    <?= $data['usuario']['categoria_profesional'] == 'Desarrollador' ? 'selected' : '' ?>>
                                    Desarrollador</option>
                                <option value="Diseñador" <?= $data['usuario']['categoria_profesional'] == 'Diseñador' ? 'selected' : '' ?>>Diseñador</option>
                                <option value="Tester" <?= $data['usuario']['categoria_profesional'] == 'Tester' ? 'selected' : '' ?>>Tester</option>
                                <option value="Analista" <?= $data['usuario']['categoria_profesional'] == 'Analista' ? 'selected' : '' ?>>Analista</option>
                            </select>

                            <label for="resumen_perfil">Resumen Perfil:</label>
                            <textarea name="resumen_perfil" id="resumen_perfil" cols="30"
                                rows="5"><?= htmlspecialchars($data['usuario']['resumen_perfil'] ?? '') ?></textarea>

                            <input type="submit" name="actualizar_perfil" value="Actualizar perfil">
                        </form>
                    </div>

                    <hr>


                    <div class="perfil-trabajos">
                        <h4>Trabajos:
                            <a href="/trabajo/nuevo" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Añadir
                            </a>
                        </h4>
                        <ul>
                            <?php foreach ($data['trabajos'] as $trabajo): ?>
                                <li>
                                    <?= htmlspecialchars($trabajo['titulo'] ?? 'Sin título') ?> |
                                    <?= htmlspecialchars($trabajo['fecha_inicio'] ?? 'Desconocida') ?> -
                                    <?= htmlspecialchars($trabajo['fecha_final'] ?? 'Actualidad') ?>

                                    <a href="/trabajo/eliminar/<?= $trabajo['id'] ?>" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>

                                    <a href="/trabajo/editar/<?= $trabajo['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>

                                    <?php if ($trabajo['visible'] == 1): ?>
                                        <a href="/trabajo/ocultar/<?= $trabajo['id'] ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-eye-slash"></i> Ocultar
                                        </a>
                                    <?php else: ?>
                                        <a href="/trabajo/ocultar/<?= $trabajo['id'] ?>" class="btn btn-success">
                                            <i class="fa-solid fa-eye"></i> Mostrar
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <hr>

                    <div class="perfil-skills">
                        <h4>Habilidades:
                            <a href="/skill/nuevo" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Añadir
                            </a>
                        </h4>
                        <ul>
                            <?php foreach ($data['habilidades'] as $skill): ?>
                                <li>
                                    <?= htmlspecialchars($skill['habilidades'] ?? 'Sin habilidad') ?>

                                    <a href="/skill/eliminar/<?= $skill['id'] ?>" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>

                                    <a href="/skill/editar/<?= $skill['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>

                                    <?php if ($skill['visible'] == 1): ?>
                                        <a href="/skill/ocultar/<?= $skill['id'] ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-eye-slash"></i> Ocultar
                                        </a>
                                    <?php else: ?>
                                        <a href="/skill/ocultar/<?= $skill['id'] ?>" class="btn btn-success">
                                            <i class="fa-solid fa-eye"></i> Mostrar
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <hr>

                    <div class="perfil-proyectos">
                        <h4>Proyectos:
                            <a href="/proyecto/nuevo" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Añadir
                            </a>
                        </h4>
                        <ul>
                            <?php foreach ($data['proyectos'] as $proyecto): ?>
                                <li>
                                    <?= htmlspecialchars($proyecto['titulo'] ?? 'Sin título') ?>

                                    <a href="/proyecto/eliminar/<?= $proyecto['id'] ?>" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>


                                    <a href="/proyecto/editar/<?= $proyecto['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>

                                    <?php if ($proyecto['visible'] == 1): ?>
                                        <a href="/proyecto/ocultar/<?= $proyecto['id'] ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-eye-slash"></i> Ocultar
                                        </a>
                                    <?php else: ?>
                                        <a href="/proyecto/ocultar/<?= $proyecto['id'] ?>" class="btn btn-success">
                                            <i class="fa-solid fa-eye"></i> Mostrar
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <hr>

                    <div class="perfil-redes">
                        <h4>Redes Sociales:
                            <a href="/redsocial/nuevo" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Añadir
                            </a>
                        </h4>
                        <ul>
                            <?php foreach ($data['redesSociales'] as $red): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($red['url']) ?>" target="_blank">
                                        <?= htmlspecialchars($red['url']) ?>
                                    </a>

                                    <a href="/redsocial/eliminar/<?= $red['id'] ?>" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>

                                    <a href="/redsocial/editar/<?= $red['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>

                                    <?php if ($red['visible'] == 1): ?>
                                        <a href="/redsocial/ocultar/<?= $red['id'] ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-eye-slash"></i> Ocultar
                                        </a>
                                    <?php else: ?>
                                        <a href="/redsocial/ocultar/<?= $red['id'] ?>" class="btn btn-success">
                                            <i class="fa-solid fa-eye"></i> Mostrar
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>


                    <hr>

                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>Porfolio</p>
    </footer>

</body>

</html>