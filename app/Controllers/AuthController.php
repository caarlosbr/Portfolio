<?php
namespace App\Controllers;

use App\Models\Usuarios;
use Symfony\Component\Mime\Email;
use App\Core\EmailConfig;

class AuthController extends BaseController
{
    // Método para manejar la acción de inicio de sesión
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($email && $password) {
                $usuario = Usuarios::getInstancia()->login($email, $password);

                if ($usuario) {
                    session_start();
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario'] = $usuario['nombre'];
                    $_SESSION['auth'] = true;
                    $_SESSION['mensaje'] = "Inicio de sesión exitoso.";
                    header('Location: /');
                    exit;
                } else {
                    $_SESSION['error'] = "Correo electrónico o contraseña incorrectos.";
                    header('Location: /login');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Por favor, completa todos los campos.";
                header('Location: /login');
                exit;
            }
        } else {
            $this->renderHTML('../views/login_view.php');
        }
    }

    // Método para manejar la acción de cerrar sesión
    public function cerrarSesionAction()
    {
        session_unset();
        session_destroy();
        header('Location: /');
    }

    // Método para manejar la acción de registro de usuario
    public function registrarAction()
    {
        if (isset($_POST['submit'])) {
            $password = $_POST['password'] ?? null;
            $repetirPassword = $_POST['repetir_password'] ?? null;
    
            if ($password !== $repetirPassword) {
                echo "<h2>Las contraseñas no coinciden.</h2>";
                echo "<a href='/registrar'>Volver</a>";
                exit;
            }
    
            $token = str_replace('/', '', uniqid('', true) . base64_encode(random_bytes(32)));
            $fecha_creacion_token = date('Y-m-d H:i:s');
            
            $usuario = Usuarios::getInstancia();
            $usuario->setNombre($_POST['nombre'] ?? null);
            $usuario->setApellidos($_POST['apellidos'] ?? null);
            $usuario->setEmail($_POST['email'] ?? null);
            $usuario->setPassword($password); // Guardar la contraseña sin hashear
            
            $usuario->setToken($token);
            $usuario->setFechaCreacionToken($fecha_creacion_token);
    
            // Procesar la imagen
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = basename($_FILES['foto']['name']);
                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid('img_') . '.' . $fileExt;
                $uploadFilePath = $uploadDir . $newFileName;
    
                if (in_array(mime_content_type($fileTmpPath), ['image/jpeg', 'image/png', 'image/gif'])) {
                    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                        $usuario->setFoto($uploadFilePath);
                    } else {
                        echo "<h2>Error al mover la imagen.</h2>";
                        exit();
                    }
                } else {
                    echo "<h2>Formato de imagen no permitido.</h2>";
                    exit();
                }
            }
            
            $usuario->set();
            $usuario->enviarCorreoActivacion();
            
            if ($usuario->getMensaje() === 'Usuario registrado correctamente') {
                echo "<h2>Usuario registrado correctamente. Revisa tu correo para activar tu cuenta.</h2>";
                echo "<a href='/'>Inicio</a>";
                exit;
            } else {
                echo "<h2>Error: " . $usuario->getMensaje() . "</h2>";
            }
        } else {
            $this->renderHTML('../views/registrar_view.php');
        }
    }

    // Método para verificar el token de activación
    public function verificarAction($token)
    {
        $usuario = Usuarios::getInstancia();

        if ($usuario->verificarToken($token)) {
            $_SESSION['mensaje'] = $usuario->getMensaje();
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error'] = $usuario->getMensaje();
            header('Location: /registrar');
            exit;
        }
    }
}