<?php

namespace App\Controllers;

use App\Models\Proyectos;

class ProyectosController extends BaseController
{   
    public function eliminarProyectoAction($id)
    {
        $id = (int)$id; // Asegurar que sea un número entero válido
    
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
            header('Location: /');
            exit;
        }
    
        $usuario_id = $_SESSION['usuario_id'];
        $proyectoData = Proyectos::getInstancia()->get($id);
    
        // Verificar que el proyecto pertenezca al usuario autenticado
        if (empty($proyectoData) || $proyectoData[0]['usuarios_id'] !== $usuario_id) {
            $_SESSION['error'] = "Error: No tienes permiso para eliminar este proyecto.";
            header('Location: /');
            exit;
        }
    
        // Eliminar el proyecto
        $proyecto = Proyectos::getInstancia();
        $proyecto->delete($id);
    
        $_SESSION['mensaje'] = "Proyecto eliminado correctamente.";
        header('Location: /perfil');
        exit;
    }
    
    
        public function ocultarProyectoAction($id)
        {
            $id = (int) $id;
        
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                $_SESSION['error'] = "Debes iniciar sesión para realizar esta acción.";
                header('Location: /');
                exit;
            }
        
            $usuario_id = $_SESSION['usuario_id'];
        
            // Obtener el proyecto
            $proyectoData = Proyectos::getInstancia()->get($id);
        
            // Verificar que el proyecto exista y sea un array válido
            if (!$proyectoData || !isset($proyectoData[0])) {
                $_SESSION['error'] = "Error: El proyecto no existe.";
                header('Location: /');
                exit;
            }
        
            // Acceder al primer elemento del array para verificar la propiedad usuarios_id
            if ($proyectoData[0]['usuarios_id'] !== $usuario_id) {
                $_SESSION['error'] = "Error: No tienes permiso para ocultar este proyecto.";
                header('Location: /');
                exit;
            }
        
            // Ocultar el proyecto
            Proyectos::getInstancia()->ocultarProyecto($id);
        
            $_SESSION['mensaje'] = "El proyecto ha sido ocultado correctamente.";
            header('Location: /perfil');
            exit;
        }
        
    
    public function nuevoProyectoAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }

        $this->renderHTML('../views/nuevo_proyecto_view.php', []);
    }

    public function anadirProyectoAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario'])) {
            header('Location: /login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $usuarios_id = $_SESSION['usuario_id'];
            $visible = 1;
    
            if ($titulo && $descripcion && $usuarios_id) {
                $proyecto = Proyectos::getInstancia();
                $proyecto->setTitulo($titulo);
                $proyecto->setDescripcion($descripcion);
                $proyecto->setUsuariosId($usuarios_id);
                $proyecto->setVisible($visible);
                $proyecto->set();
    
                if ($proyecto->getMensaje() === "Proyecto añadido correctamente.") {
                    $_SESSION['mensaje'] = $proyecto->getMensaje();
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error: " . $proyecto->getMensaje();
                    header('Location: /');
                    exit;
                }
            }
    
            $_SESSION['mensaje'] = "Error: Faltan datos obligatorios para añadir el proyecto.";
            header('Location: /');
            exit;
        }
    
        header('Location: /');
        exit;
    }

    public function editProyectoAction($params = [])
    {
        // Obtener el ID del proyecto desde los parámetros del router
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
            $descripcion = trim($_POST['descripcion'] ?? '');
            $logo = trim($_POST['logo'] ?? '');
            $tecnologias = trim($_POST['tecnologias'] ?? '');
            $visible = isset($_POST['visible']) ? 1 : 0;
            $usuarios_id = $_SESSION['usuario_id'];

            // Validar que los datos esenciales estén presentes
            if ($id && !empty($titulo) && !empty($descripcion) && $usuarios_id) {
                $proyecto = Proyectos::getInstancia();
                $proyectoData = $proyecto->get($id);

                // Verificar que el proyecto pertenece al usuario autenticado
                if ($proyectoData[0]['usuarios_id'] !== $usuarios_id) {
                    $_SESSION['error'] = "No tienes permiso para editar este proyecto.";
                    header('Location: /');
                    exit;
                }

                // Actualizar los datos del proyecto
                $proyecto->setID($id);
                $proyecto->setTitulo($titulo);
                $proyecto->setDescripcion($descripcion);
                $proyecto->setLogo($logo);
                $proyecto->setTecnologias($tecnologias);
                $proyecto->setVisible($visible);
                $proyecto->setUsuariosId($usuarios_id);
                $proyecto->edit();

                // Verificar si la actualización fue exitosa
                if ($proyecto->getMensaje() === "Proyecto actualizado correctamente.") {
                    $_SESSION['mensaje'] = "Proyecto actualizado correctamente.";
                    header('Location: /perfil');
                    exit;
                } else {
                    $_SESSION['error'] = "Error al actualizar: " . $proyecto->getMensaje();
                }
            } else {
                $_SESSION['error'] = "Error: Faltan datos obligatorios.";
            }

            // Redirigir de vuelta al formulario en caso de error
            header('Location: /');
            exit;
        }

        // Si no es POST, mostrar el formulario con los datos del proyecto
        if ($id) {
            $proyecto = Proyectos::getInstancia()->get($id);
            if (empty($proyecto)) {
                $_SESSION['error'] = "Error: No se encontró el proyecto.";
                header('Location: /');
                exit;
            }

            // Verificar que el proyecto pertenece al usuario autenticado
            if ($proyecto[0]['usuarios_id'] !== $_SESSION['usuario_id']) {
                $_SESSION['error'] = "No tienes permiso para editar este proyecto.";
                header('Location: /');
                exit;
            }

            $this->renderHTML('../views/edit_proyecto.php', ['proyecto' => $proyecto[0]]);
        } else {
            $_SESSION['error'] = "Error: ID no válido.";
            header('Location: /');
            exit;
        }
    }
}