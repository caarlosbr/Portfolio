<?php

namespace App\Controllers;

use App\Models\Skills;

class SkillsController extends BaseController {
    /**
     * Eliminar una habilidad.
     */
    public function eliminarHabilidadAction($id)
    {
        $id = (int)$id;

        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /perfil');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $skill = Skills::getInstancia();
        $skill->setUsuariosId($usuarioId);
        $skill->setID($id);
        
        $skillData = $skill->get($id);
        if (!$skillData) {
            $_SESSION['error'] = "La habilidad no existe.";
            header('Location: /');
            exit;
        }

        if ($skillData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para eliminar esta habilidad.";
            header('Location: /');
            exit;
        }

        $skill->delete();
        $_SESSION['mensaje'] = "Habilidad eliminada correctamente.";
        header('Location: /perfil');
        exit;
    }
    
    /**
     * Ocultar o mostrar una habilidad.
     */
    public function ocultarHabilidadAction($id)
    {
        $id = (int)$id;

        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /perfil');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $skill = Skills::getInstancia();
        $skill->setUsuariosId($usuarioId);
        $skill->setID($id);
        
        $skillData = $skill->get($id);
        if (!$skillData) {
            $_SESSION['error'] = "La habilidad no existe.";
            header('Location: /');
            exit;
        }

        if ($skillData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para modificar esta habilidad.";
            header('Location: /');
            exit;
        }

        $skill->ocultarSkill($id);
        $_SESSION['mensaje'] = "Estado de la habilidad actualizado correctamente.";
        header('Location: /perfil');
        exit;
    }

    /**
     * Mostrar formulario para añadir una nueva habilidad.
     */
    public function nuevaHabilidadAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        $this->renderHTML('../views/nueva_habilidad_view.php', []);
    }

    /**
     * Procesar el formulario y añadir una nueva habilidad.
     */
    public function anadirHabilidadAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $habilidades = $_POST['habilidades'] ?? null;
            $usuarios_id = $_SESSION['usuario_id'];
    
            if ($habilidades && $usuarios_id) {
                $skills = Skills::getInstancia();
                $skills->setHabilidades($habilidades);
                $skills->setUsuariosId($usuarios_id);
                $skills->set();
    
                if ($skills->getMensaje() === 'Skill añadida correctamente') {
                    $_SESSION['mensaje'] = "Habilidad añadida correctamente.";
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error al añadir la habilidad.";
                    header('Location: /skill/nuevo');
                    exit;
                }
            }
    
            $_SESSION['error'] = "Faltan datos obligatorios para añadir la habilidad.";
            header('Location: /skill/nuevo');
            exit;
        }
    
        header('Location: /skill/nuevo');
        exit;
    }

    public function editSkillAction($params = [])
    {
        // Obtener el ID de la habilidad desde los parámetros del router
        $id = $params;
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
            $habilidades = trim($_POST['habilidades'] ?? '');
            $usuarios_id = $_SESSION['usuario_id'];

            // Validar que los datos esenciales estén presentes
            if ($id && !empty($habilidades) && $usuarios_id) {
                $skill = Skills::getInstancia();
                $skillData = $skill->get($id);

                // Verificar que la habilidad pertenece al usuario autenticado
                if ($skillData[0]['usuarios_id'] !== $usuarios_id) {
                    $_SESSION['error'] = "No tienes permiso para editar esta habilidad.";
                    header('Location: /perfil');
                    exit;
                }

                $skill->setID($id);
                $skill->setHabilidades($habilidades);
                $skill->setUsuariosId($usuarios_id);
                $skill->edit();

                // Verificar si la actualización fue exitosa
                if ($skill->getMensaje() === "Skill actualizada correctamente") {
                    $_SESSION['mensaje'] = "Skill actualizada correctamente.";
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error al actualizar: " . $skill->getMensaje();
                }
            } else {
                $_SESSION['error'] = "Error: Faltan datos obligatorios.";
            }

            // Redirigir de vuelta al formulario en caso de error
            header('Location: /skill/editar/' . $id);
            exit;
        }

        // Si no es POST, mostrar el formulario con los datos de la habilidad
        if ($id) {
            $skill = Skills::getInstancia()->get($id);
            if (empty($skill)) {
                $_SESSION['error'] = "Error: No se encontró la habilidad.";
                header('Location: /perfil');
                exit;
            }

            // Verificar que la habilidad pertenece al usuario autenticado
            if ($skill[0]['usuarios_id'] !== $_SESSION['usuario_id']) {
                $_SESSION['error'] = "No tienes permiso para editar esta habilidad.";
                header('Location: /');
                exit;
            }

            $this->renderHTML('../views/edit_skill.php', ['skill' => $skill[0]]);
        } else {
            $_SESSION['error'] = "Error: ID no válido.";
            header('Location: /perfil');
            exit;
        }
    }
}
