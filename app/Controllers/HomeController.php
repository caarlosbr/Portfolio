<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Trabajos;
use App\Models\Proyectos;
use App\Models\RedesSociales;
use App\Models\Skills;

class HomeController extends BaseController {
    public function indexAction() {
        $claseUsuario = Usuarios::getInstancia();
        $query = $_GET['q'] ?? null;
    
        // Obtener usuarios según búsqueda o todos los visibles
        if ($query) {
            $usuarios = $claseUsuario->buscarUsuarios($query) ?? [];
        } else {
            $usuarios = $claseUsuario->get() ?? [];
        }
    
        // Asegurar que $usuarios sea siempre un array
        if (!is_array($usuarios)) {
            $usuarios = [];
        }
    
        $data = [
            'usuarios' => $usuarios,
            'auth' => isset($_SESSION['auth']),
        ];
    
        $this->renderHTML('../views/index_view.php', $data);
    }

    public function ocultarAction() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
            echo "No tienes permiso para realizar esta acción.";
            exit;
        }
    
        // Obtener la URI actual
        $requestUri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $requestUri);
        $id = (int) end($segments);
    
        // Obtener el ID del usuario autenticado
        $usuarioAutenticadoId = $_SESSION['usuario_id'];
    
        if ($id > 0) {
            $claseUsuario = Usuarios::getInstancia();
            $usuario = $claseUsuario->get($id);
    
            // Verificar si el usuario autenticado es el propietario del portafolio
            if ($usuario && $usuario['id'] == $usuarioAutenticadoId) {
                $claseUsuario->ocultarUsuario($id); // Pasar el argumento $id aquí
            } else {
                echo "No tienes permiso para ocultar este portafolio.";
                exit;
            }
        } else {
            echo "ID inválido.";
            exit;
        }
    
        header('Location: /');
        exit;
    }

    public function buscarAction()
    {
        // Obtener la consulta desde GET
        $query = isset($_GET['q']) ? trim(htmlspecialchars($_GET['q'])) : '';
    
        if (!empty($query)) {
            $claseUsuario = Usuarios::getInstancia();
            $usuarios = $claseUsuario->buscarUsuarios($query) ?? [];
    
            if (!is_array($usuarios)) {
                $usuarios = [];
            }
    
            foreach ($usuarios as &$usuario) {
                $trabajos = Trabajos::getInstancia()->getTrabajosPorUsuariosId($usuario['id']);
                $usuario['trabajos'] = $trabajos ?? [];
    
                $proyectos = Proyectos::getInstancia()->getProyectosPorUsuariosId($usuario['id']);
                $usuario['proyectos'] = $proyectos ?? [];
    
                $redesSociales = RedesSociales::getInstancia()->getRedesSocialesPorUsuarioId($usuario['id']);
                $usuario['redes_sociales'] = $redesSociales ?? [];
    
                $skills = Skills::getInstancia()->getSkillsPorUsuariosId($usuario['id']);
                $usuario['skills'] = $skills ?? [];
            }
    
            $data = [
                'usuarios' => $usuarios,
                'auth' => isset($_SESSION['auth']),
            ];
    
            $this->renderHTML('../views/index_view.php', $data);
        } else {
            header('Location: /');
            exit;
        }
    }
    
    
}
