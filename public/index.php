<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../bootstrap.php';


use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\TrabajosController;
use App\Controllers\ProyectosController;
use App\Controllers\SkillsController;
use App\Controllers\RedesController;

// Configuración del enrutador
$router = new Router();

// Definición de rutas
$router->add([
    'name' => 'home',
    'path' => '/^\/$/',
    'action' => [HomeController::class, 'IndexAction'],
    'auth' => ['Invitado', 'Usuario']
]);

$router->add([
    'name' => 'buscarUsuario',
    'path' => '/^\/buscar$/',
    'action' => [HomeController::class, 'buscarAction'],
    'auth' => ['Invitado', 'Usuario']
]);

$router->add([
    'name' => 'login',
    'path' => '/^\/login$/',
    'action' => [AuthController::class, 'loginAction'],
    'auth' => ['Invitado']
]);

$router->add([
    'name' => 'cerrarSesion',
    'path' => '/^\/cerrarsesion$/',
    'action' => [AuthController::class, 'cerrarSesionAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'registrar',
    'path' => '/^\/registrar$/',
    'action' => [AuthController::class, 'registrarAction'],
    'auth' => ['Invitado']
]);


$router->add([
    'name' => 'perfil',
    'path' => '/^\/perfil$/',
    'action' => [ProfileController::class, 'perfilAction'],
    'auth' => ['Usuario']
]);


$router->add([
    'name' => 'ocultarUsuario',
    'path' => '/^\/usuario\/ocultar\/([0-9]+)$/',
    'action' => [HomeController::class, 'ocultarAction'],
    'auth' => ['Usuario']
]);


$router->add([
    'name' => 'verificar',
    'path' => '/^\/verificar\/(.+)$/',
    'action' => [AuthController::class, 'verificarAction'],
    'auth' => ['Invitado']
]);

// Rutas para trabajos
$router->add([
    'name' => 'eliminarTrabajo',
    'path' => '/^\/trabajo\/eliminar\/([0-9]+)$/',
    'action' => [TrabajosController::class, 'eliminarTrabajoAction'], // Cambiar a eliminarTrabajoAction
    'auth' => ['Usuario']
]);
$router->add([
    'name' => 'ocultarTrabajo',
    'path' => '/^\/trabajo\/ocultar\/([0-9]+)$/',
    'action' => [TrabajosController::class, 'ocultarTrabajoAction'],
    'auth' => ['Usuario']
]);


// Rutas para proyectos
$router->add([
    'name' => 'eliminarProyecto',
    'path' => '/^\/proyecto\/eliminar\/([0-9]+)$/', // Eliminado el carácter incorrecto `
    'action' => [ProyectosController::class, 'eliminarProyectoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'ocultarProyecto',
    'path' => '/^\/proyecto\/ocultar\/([0-9]+)$/',
    'action' => [ProyectosController::class, 'ocultarProyectoAction'],
    'auth' => ['Usuario']
]);

// Rutas para redes
$router->add([
    'name' => 'eliminarRedSocial',
    'path' => '/^\/redsocial\/eliminar\/([0-9]+)$/',
    'action' => [RedesController::class, 'eliminarRedSocialAction'], // Cambiar a eliminarTrabajoAction
    'auth' => ['Usuario']
]);
$router->add([
    'name' => 'ocultarRedSocial',
    'path' => '/^\/redsocial\/ocultar\/([0-9]+)$/',
    'action' => [RedesController::class, 'ocultarRedSocialAction'],
    'auth' => ['Usuario']
]);

// Rutas para habilidades
$router->add([
    'name' => 'eliminarSkill',
    'path' => '/^\/skill\/eliminar\/([0-9]+)$/',
    'action' => [SkillsController::class, 'eliminarHabilidadAction'], // Cambiar a eliminarTrabajoAction
    'auth' => ['Usuario']
]);
$router->add([
    'name' => 'ocultarSkill',
    'path' => '/^\/skill\/ocultar\/([0-9]+)$/',
    'action' => [SkillsController::class, 'ocultarHabilidadAction'],
    'auth' => ['Usuario']
]);


$router->add([
    'name' => 'nuevoTrabajo',
    'path' => '/^\/trabajo\/nuevo$/',
    'action' => [TrabajosController::class, 'nuevoTrabajoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'anadirTrabajo',
    'path' => '/^\/trabajo\/anadir$/',
    'action' => [TrabajosController::class, 'anadirTrabajoAction'],
    'auth' => ['Usuario']
]);


$router->add([
    'name' => 'nuevoProyecto',
    'path' => '/^\/proyecto\/nuevo$/',
    'action' => [ProyectosController::class, 'nuevoProyectoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'anadirProyecto',
    'path' => '/^\/proyecto\/anadir$/',
    'action' => [ProyectosController::class, 'anadirProyectoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'nuevaHabilidad',
    'path' => '/^\/skill\/nuevo$/',
    'action' => [SkillsController::class, 'nuevaHabilidadAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'anadirHabilidad',
    'path' => '/^\/skill\/anadir$/',
    'action' => [SkillsController::class, 'anadirHabilidadAction'],
    'auth' => ['Usuario']
]);


$router->add([
    'name' => 'nuevaRedSocial',
    'path' => '/^\/redsocial\/nuevo$/',
    'action' => [RedesController::class, 'nuevaRedSocialAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'anadirRedSocial',
    'path' => '/^\/redsocial\/anadir$/',
    'action' => [RedesController::class, 'anadirRedSocialAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'editarTrabajo',
    'path' => '/^\/trabajo\/editar\/([0-9]+)$/',
    'action' => [TrabajosController::class, 'editTrabajoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'editarProyecto',
    'path' => '/^\/proyecto\/editar\/([0-9]+)$/',
    'action' => [ProyectosController::class, 'editProyectoAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'editarRedSocial',
    'path' => '/^\/redsocial\/editar\/([0-9]+)$/',
    'action' => [RedesController::class, 'editRedSocialAction'],
    'auth' => ['Usuario']
]);

$router->add([
    'name' => 'editarSkill',
    'path' => '/^\/skill\/editar\/([0-9]+)$/',
    'action' => [SkillsController::class, 'editSkillAction'],
    'auth' => ['Usuario']
]);

// Obtener la solicitud actual
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Buscar coincidencias con las rutas definidas
$route = $router->match($requestUri);

if ($route) {
    $controllerName = $route['action'][0];
    $actionName = $route['action'][1];
    $pathPattern = $route['path'];

    // Verificar autenticación si la ruta lo requiere
/*     if (isset($route['auth']) && in_array('Usuario', $route['auth'])) {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }
    } */

    $matches = [];
    if (preg_match($pathPattern, $requestUri, $matches)) {
        array_shift($matches); // Elimina la coincidencia completa (el primer elemento del array)

        if (class_exists($controllerName) && method_exists($controllerName, $actionName)) {
            $controller = new $controllerName();
            // Llama al controlador y pasa los parámetros capturados (como el ID)
            $controller->$actionName(...$matches);
        } else {
            http_response_code(404);
            echo "Error 404: Acción o controlador no encontrados.";
        }
    } else {
        http_response_code(404);
        echo "Error 404: Ruta no encontrada.";
    }
} else {
    http_response_code(404);
    echo "Error 404: Ruta no encontrada.";
}


