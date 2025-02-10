<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Trabajos;
use App\Models\Proyectos;
use App\Models\RedesSociales;
use App\Models\Skills;

class ProfileController extends BaseController
{
    public function perfilAction()
    {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        // Obtener el usuario autenticado
        $usuarioId = $_SESSION['usuario_id'];
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->get($usuarioId);

        if (!$usuario) {
            $_SESSION = [];
            session_destroy();
            header('Location: /login');
            exit;
        }

        // Manejo de la actualización del perfil
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
            $claseUsuario->setID($usuarioId); // Asegurar que el ID está configurado
            $claseUsuario->setNombre($_POST['nombre'] ?? $usuario['nombre']);
            $claseUsuario->setApellidos($_POST['apellidos'] ?? $usuario['apellidos']);
            $claseUsuario->setEmail($_POST['email'] ?? $usuario['email']);
            $claseUsuario->setCategoriaProfesional($_POST['categoria_profesional'] ?? $usuario['categoria_profesional']);
            $claseUsuario->setResumenPerfil($_POST['resumen_perfil'] ?? $usuario['resumen_perfil']);

            // Manejo de la subida de imagen
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
                $fileName = basename($_FILES['foto_perfil']['name']);
                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid('img_') . '.' . $fileExt;
                $uploadFilePath = $uploadDir . $newFileName;

                if (in_array(mime_content_type($fileTmpPath), ['image/jpeg', 'image/png', 'image/gif'])) {
                    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                        $claseUsuario->setFoto('/uploads/' . $newFileName);
                    }
                }
            }

            // Ejecutar actualización
            $claseUsuario->edit();
            $_SESSION['usuario'] = $_POST['nombre'];

            header("Location: /perfil");
            exit;
        }

        // Obtener datos del usuario autenticado
        $trabajos = Trabajos::getInstancia()->getTrabajosPorUsuariosId($usuarioId) ?? [];
        $proyectos = Proyectos::getInstancia()->getProyectosPorUsuariosId($usuarioId) ?? [];
        $redesSociales = RedesSociales::getInstancia()->getRedesSocialesPorUsuarioId($usuarioId) ?? [];
        $habilidades = Skills::getInstancia()->getSkillsPorUsuariosId($usuarioId) ?? [];

        $data = [
            'usuario' => $usuario,
            'trabajos' => $trabajos,
            'proyectos' => $proyectos,
            'redesSociales' => $redesSociales,
            'habilidades' => $habilidades,
            'auth' => isset($_SESSION['auth']),
        ];

        $this->renderHTML('../views/perfil_view.php', $data);
    }
}
