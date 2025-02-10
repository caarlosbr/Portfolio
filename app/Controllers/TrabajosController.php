<?php

namespace App\Controllers;

use App\Models\Trabajos;

class TrabajosController extends BaseController {
    /**
     * Eliminar un trabajo.
     */
    public function eliminarTrabajoAction($id)
    {
        // Asegurar que $id es un número entero
        $id = (int)$id;
    
        // Verificar si el usuario ha iniciado sesión
        if (empty($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /perfil');
            exit;
        }
    
        $usuarioId = $_SESSION['usuario_id'];
        $trabajo = Trabajos::getInstancia(); // Obtenemos instancia del modelo
    
        // Verificar que el trabajo existe y pertenece al usuario
        $trabajoData = $trabajo->get($id);
        
        if (!$trabajoData) {
            $_SESSION['error'] = "El trabajo no existe o ya fue eliminado.";
            header('Location: /perfil');
            exit;
        }
    
        if ((int) $trabajoData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para eliminar este trabajo.";
            header('Location: /');
            exit;
        }
    
        // Intentar eliminar el trabajo y verificar si la eliminación fue exitosa
        if ($trabajo->delete($id)) {
            $_SESSION['mensaje'] = "Trabajo eliminado correctamente.";
        };
    
        header('Location: /perfil');
        exit;
    }
    
    
    
    /**
     * Ocultar o mostrar un trabajo.
     */    
    public function ocultarTrabajoAction($id)
    {
        $id = (int) $id;
    
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /login');
            exit;
        }
    
        $usuarioId = $_SESSION['usuario_id'];
    
        // Obtener los datos del trabajo directamente sin setear ID
        $trabajoData = Trabajos::getInstancia()->get($id);
    
        if (!$trabajoData) {
            $_SESSION['error'] = "El trabajo no existe.";
            header('Location: /perfil');
            exit;
        }
    
        // Verificar que el trabajo pertenezca al usuario autenticado
        if ($trabajoData[0]['usuarios_id'] !== $usuarioId) {
            $_SESSION['error'] = "No tienes permiso para ocultar este trabajo.";
            header('Location: /perfil');
            exit;
        }
    
        // Ocultar el trabajo sin necesidad de setear ID
        Trabajos::getInstancia()->ocultarTrabajo($id);
    
        $_SESSION['mensaje'] = "El trabajo ha sido ocultado correctamente.";
        header('Location: /perfil');
        exit;
    }
    
    

    /**
     * Mostrar el formulario para añadir un nuevo trabajo.
     */
    public function nuevoTrabajoAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        $this->renderHTML('../views/nuevo_trabajo_view.php', []);
    }

    /**
     * Procesar el formulario para añadir un nuevo trabajo.
     */
    public function anadirTrabajoAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? null;
            $fecha_inicio = $_POST['fecha_inicio'] ?? null;
            $fecha_final = $_POST['fecha_final'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $logros = $_POST['logros'] ?? null;
            $visible = 1;
            $usuarios_id = $_SESSION['usuario_id'];
    
            if ($titulo && $fecha_inicio && $descripcion && $usuarios_id) {
                $trabajo = Trabajos::getInstancia();
                $trabajo->setTitulo($titulo);
                $trabajo->setFechaInicio($fecha_inicio);
                $trabajo->setFechaFinal($fecha_final);
                $trabajo->setDescripcion($descripcion);
                $trabajo->setLogros($logros);
                $trabajo->setVisible($visible);
                $trabajo->setUsuariosId($usuarios_id);
                $trabajo->set();
    
                if ($trabajo->getMensaje() === "Trabajo añadido correctamente.") {
                    $_SESSION['mensaje'] = $trabajo->getMensaje();
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error: " . $trabajo->getMensaje();
                    header('Location: /trabajo/nuevo');
                    exit;
                }
            }
    
            $_SESSION['error'] = "Error: Faltan datos obligatorios para añadir el trabajo.";
            header('Location: /trabajo/nuevo');
            exit;
        }
    
        header('Location: /trabajo/nuevo');
        exit;
    }

    public function editTrabajoAction($params = [])
    {
        // Obtener el ID del trabajo desde los parámetros del router
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
            $titulo = trim($_POST['titulo'] ?? '');
            $fecha_inicio = $_POST['fecha_inicio'] ?? null;
            $fecha_final = $_POST['fecha_final'] ?? null;
            $descripcion = trim($_POST['descripcion'] ?? '');
            $logros = trim($_POST['logros'] ?? '');
            $visible = isset($_POST['visible']) ? 1 : 0;
            $usuarios_id = $_SESSION['usuario_id'];

            // Validar que los datos esenciales estén presentes
            if ($id && !empty($titulo) && !empty($fecha_inicio) && !empty($descripcion) && $usuarios_id) {
                $trabajo = Trabajos::getInstancia();
                $trabajoData = $trabajo->get($id);

                // Verificar que el trabajo pertenece al usuario autenticado
                if ($trabajoData[0]['usuarios_id'] !== $usuarios_id) {
                    $_SESSION['error'] = "No tienes permiso para editar este trabajo.";
                    header('Location: /perfil');
                    exit;
                }

                $trabajo->setID($id);
                $trabajo->setTitulo($titulo);
                $trabajo->setFechaInicio($fecha_inicio);
                $trabajo->setFechaFinal($fecha_final);
                $trabajo->setDescripcion($descripcion);
                $trabajo->setLogros($logros);
                $trabajo->setVisible($visible);
                $trabajo->setUsuariosId($usuarios_id);
                $trabajo->edit();

                // Verificar si la actualización fue exitosa
                if ($trabajo->getMensaje() === "Trabajo actualizado correctamente.") {
                    $_SESSION['mensaje'] = "Trabajo actualizado correctamente.";
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error al actualizar: " . $trabajo->getMensaje();
                }
            } else {
                $_SESSION['error'] = "Error: Faltan datos obligatorios.";
            }

            // Redirigir de vuelta al formulario en caso de error
            header('Location: /trabajo/editar/' . $id);
            exit;
        }

        // Si no es POST, mostrar el formulario con los datos del trabajo
        if ($id) {
            $trabajo = Trabajos::getInstancia()->get($id);
            if (empty($trabajo)) {
                $_SESSION['error'] = "Error: No se encontró el trabajo.";
                header('Location: /perfil');
                exit;
            }

            // Verificar que el trabajo pertenece al usuario autenticado
            if ($trabajo[0]['usuarios_id'] !== $_SESSION['usuario_id']) {
                $_SESSION['error'] = "No tienes permiso para editar este trabajo.";
                header('Location: /perfil');
                exit;
            }

            $this->renderHTML('../views/edit_trabajo.php', ['trabajo' => $trabajo[0]]);
        } else {
            $_SESSION['error'] = "Error: ID no válido.";
            header('Location: /perfil');
            exit;
        }
    }
    
}
