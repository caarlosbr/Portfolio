<?php

namespace App\Controllers;

use App\Models\RedesSociales;

class RedesController extends BaseController {
    /**
     * Eliminar una red social.
     */
    public function eliminarRedSocialAction($id)
    {
        $id = (int)$id;
    
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /perfil');
            exit;
        }
    
        $usuarioId = $_SESSION['usuario_id'];
        $redSocial = RedesSociales::getInstancia();
    
        $redSocialData = $redSocial->get($id);
        if (!$redSocialData) {
            $_SESSION['error'] = "La red social no existe.";
            header('Location: /');
            exit;
        }
        
        if ($redSocialData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para eliminar esta red social.";
            header('Location: /');
            exit;
        }
    
        // Llamamos a delete directamente con el ID
        $redSocial->delete($id);
    
        $_SESSION['mensaje'] = "Red social eliminada correctamente.";
        header('Location: /perfil');
        exit;
    }
    
    
    /**
     * Ocultar o mostrar una red social.
     */
    public function ocultarRedSocialAction($id)
    {
        $id = (int)$id;

        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /perfil');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $redSocial = RedesSociales::getInstancia();
        $redSocial->setUsuariosId($usuarioId);
        $redSocial->setID($id);
        
        $redSocialData = $redSocial->get($id);
        if (!$redSocialData) {
            $_SESSION['error'] = "La red social no existe.";
            header('Location: /');
            exit;
        }

        if ($redSocialData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para modificar esta red social.";
            header('Location: /');
            exit;
        }

        $redSocial->ocultarRedSocial($id);
        $_SESSION['mensaje'] = "Estado de la red social actualizado correctamente.";
        header('Location: /perfil');
        exit;
    }

    /**
     * Mostrar formulario para añadir una nueva red social.
     */
    public function nuevaRedSocialAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        $this->renderHTML('../views/nueva_red_social_view.php', []);
    }

    /**
     * Procesar el formulario y añadir una nueva red social.
     */
    public function anadirRedSocialAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $url = $_POST['url'] ?? null;
            $redes_sociales = $_POST['redes_sociales'] ?? null;
            $visible = 1;
            $usuarios_id = $_SESSION['usuario_id'];
    
            if ($url && $usuarios_id && $redes_sociales) {
                $redSocial = RedesSociales::getInstancia();
                $redSocial->setUrl($url);
                $redSocial->setRedesSociales($redes_sociales);
                $redSocial->setUsuariosId($usuarios_id);
                $redSocial->setVisible($visible);
                $redSocial->set();
    
                if ($redSocial->getMensaje() === "Red social añadida correctamente") {
                    $_SESSION['mensaje'] = $redSocial->getMensaje();
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error: " . $redSocial->getMensaje();
                    header('Location: /redsocial/nuevo');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Error: Datos incompletos.";
                header('Location: /redsocial/nuevo');
                exit;
            }
        } else {
            // Renderizar la vista para añadir una nueva red social
            $this->renderHTML('../views/redsocial_nuevo_view.php');
        }
    }

    public function editRedSocialAction($params = [])
    {
        // Obtener el ID de la red social desde los parámetros del router
        $id = (int) $params;
        

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        // Si se envía el formulario con POST, procesamos la actualización
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asegurar que el ID venga del formulario si está vacío en la URL
            $id = $_POST['id'] ?? $id;

            // Obtener los datos enviados
            $redes_sociales = trim($_POST['redes_sociales'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $usuarios_id = $_SESSION['usuario_id'];

            // Validar que los datos esenciales estén presentes
            if ($id && !empty($redes_sociales) && !empty($url) && $usuarios_id) {
                $redSocial = RedesSociales::getInstancia();
                $redSocialData = $redSocial->get($id);

                // Verificar que la red social pertenece al usuario autenticado
                if ($redSocialData[0]['usuarios_id'] !== $usuarios_id) {
                    $_SESSION['error'] = "No tienes permiso para editar esta red social.";
                    header('Location: /perfil');
                    exit;
                }

                $redSocial->setID($id);
                $redSocial->setRedesSociales($redes_sociales);
                $redSocial->setUrl($url);
                $redSocial->setUsuariosId($usuarios_id);
                $redSocial->edit();

                // Verificar si la actualización fue exitosa
                if ($redSocial->getMensaje() === "Red social actualizada correctamente") {
                    $_SESSION['mensaje'] = "Red social actualizada correctamente.";
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error al actualizar: " . $redSocial->getMensaje();
                }
            } else {
                $_SESSION['error'] = "Error: Faltan datos obligatorios.";
            }

            // Redirigir de vuelta al formulario en caso de error
            header('Location: /redsocial/editar/' . $id);
            exit;
        }

        // Si no es POST, mostrar el formulario con los datos de la red social
        if ($id) {
            $redSocial = RedesSociales::getInstancia()->get($id);
            if (empty($redSocial)) {
                $_SESSION['error'] = "Error: No se encontró la red social.";
                header('Location: /perfil');
                exit;
            }

            // Verificar que la red social pertenece al usuario autenticado
            if ($redSocial[0]['usuarios_id'] !== $_SESSION['usuario_id']) {
                $_SESSION['error'] = "No tienes permiso para editar esta red social.";
                header('Location: /perfil');
                exit;
            }

            $this->renderHTML('../views/edit_redsocial.php', ['redSocial' => $redSocial[0]]);
        } else {
            $_SESSION['error'] = "Error: ID no válido.";
            header('Location: /perfil');
            exit;
        }
    }
}
