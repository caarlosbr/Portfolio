<?php

namespace App\Models;
use App\Core\EmailConfig;
use Symfony\Component\Mime\Email;

require_once("DBAbstractModel.php");

class Usuarios extends DBAbstractModel
{
    // Patrón Singleton para asegurar una única instancia de la clase
    private static $instancia;
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    // Prevenir la clonación de la instancia
    public function __clone()
    {
        trigger_error("La clonación no está permitida.", E_USER_ERROR);
    }

    // Propiedades de la clase
    private $id;
    private $nombre;
    private $apellidos;
    private $foto;
    private $categoria_profesional;
    private $email;
    private $resumen_perfil;
    private $password;
    private $visible;
    private $created_at;
    private $updated_at;
    private $token;
    private $fecha_creacion_token;
    private $cuenta_activa;

    private $trabajos = [];
    private $skills = [];
    private $proyectos = [];
    private $redes = [];

    // Setters para las propiedades de la clase
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function setToken($token)
    {
        $this->token = $token;
    }
    public function setFechaCreacionToken($fecha_creacion_token)
    {
        $this->fecha_creacion_token = $fecha_creacion_token;
    }
    public function setCategoriaProfesional($categoria_profesional)
    {
        $this->categoria_profesional = $categoria_profesional;
    }
    public function setResumenPerfil($resumen_perfil)
    {
        $this->resumen_perfil = $resumen_perfil;
    }
    public function setCuentaActiva($cuenta_activa)
    {
        $this->cuenta_activa = $cuenta_activa;
    }
    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    // Método para insertar un nuevo usuario en la base de datos
    public function set()
    {
        $this->query = "INSERT INTO usuarios (nombre, apellidos, email, password, token, fecha_creacion_token, cuenta_activa, foto) 
                        VALUES (:nombre, :apellidos, :email, :password, :token, :fecha_creacion_token, 0, :foto)";
        $this->parametros = [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'password' => $this->password, // ✅ Guardar la contraseña en texto plano
            'token' => $this->token,
            'fecha_creacion_token' => $this->fecha_creacion_token,
            'foto' => $this->foto
        ];
        $this->get_results_from_query();
        $this->mensaje = "Usuario registrado correctamente";
    }

    // Método para obtener usuario(s) de la base de datos
    public function get($id = null)
    {
        // Si se proporciona un ID, se obtiene un solo usuario
        if ($id) {
            $this->query = "SELECT * FROM usuarios WHERE id = :id AND cuenta_activa = 1 LIMIT 1";
            $this->parametros = ['id' => $id];
            $this->get_results_from_query();
    
            $usuario = $this->rows[0] ?? null;
            if ($usuario) {
                $usuario['trabajos'] = Trabajos::getInstancia()->getTrabajosPorUsuariosId($usuario['id']);
                $usuario['skills'] = Skills::getInstancia()->getSkillsPorUsuariosId($usuario['id']);
                $usuario['proyectos'] = Proyectos::getInstancia()->getProyectosPorUsuariosId($usuario['id']);
                $usuario['redes'] = RedesSociales::getInstancia()->getRedesSocialesPorUsuarioId($usuario['id']);
            }
    
            return $usuario;
        }
    
        // Si no se proporciona un ID, se obtienen todos los usuarios visibles y con cuenta activa
        $this->query = "SELECT * FROM usuarios WHERE visible = 1 AND cuenta_activa = 1";
        $this->parametros = [];
        $this->get_results_from_query();
    
        foreach ($this->rows as &$usuario) {
            $usuario['trabajos'] = Trabajos::getInstancia()->getTrabajosPorUsuariosId($usuario['id']);
            $usuario['skills'] = Skills::getInstancia()->getSkillsPorUsuariosId($usuario['id']);
            $usuario['proyectos'] = Proyectos::getInstancia()->getProyectosPorUsuariosId($usuario['id']);
            $usuario['redes'] = RedesSociales::getInstancia()->getRedesSocialesPorUsuarioId($usuario['id']);
        }
    
        return $this->rows;
    }

    // Método para actualizar un usuario en la base de datos
    public function edit()
    {
        $this->query = "UPDATE usuarios 
                        SET nombre = :nombre, 
                            apellidos = :apellidos, 
                            email = :email, 
                            categoria_profesional = :categoria_profesional, 
                            resumen_perfil = :resumen_perfil, 
                            foto = :foto
                        WHERE id = :id";
        $this->parametros = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'categoria_profesional' => $this->categoria_profesional,
            'resumen_perfil' => $this->resumen_perfil,
            'foto' => $this->foto
        ];
        $this->get_results_from_query();
        $this->mensaje = "Usuario actualizado correctamente";
    }

    // Método para eliminar un usuario de la base de datos
    public function delete()
    {
        $this->query = "DELETE FROM usuarios WHERE id = :id";
        $this->parametros = ['id' => $this->id];
        $this->get_results_from_query();
        $this->mensaje = "Usuario eliminado correctamente";
    }

    // Método para ocultar un usuario (establecer visible a 0)
    public function ocultarUsuario($id)
    {
        $this->query = "UPDATE usuarios SET visible = 0 WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Usuario ocultado correctamente";
    }

    // Método para buscar usuarios basados en una consulta
    public function buscarUsuarios($query)
    {
        if (empty($query)) {
            return [];
        }

        $this->query = "SELECT * FROM usuarios 
                        WHERE nombre LIKE :query 
                           OR apellidos LIKE :query 
                           OR email LIKE :query";
        $this->parametros['query'] = '%' . $query . '%';
        $this->get_results_from_query();

        return $this->rows;
    }

    // Método para verificar un token de activación
    public function verificarToken($token)
    {
        $this->query = "SELECT fecha_creacion_token FROM usuarios WHERE token = :token";
        $this->parametros = ['token' => $token];
        $this->get_results_from_query();
    
        if (empty($this->rows)) {
            $this->mensaje = 'Token no encontrado';
            return false;
        }
    
        if (strtotime($this->rows[0]['fecha_creacion_token']) < strtotime('-1 day')) {
            $this->mensaje = 'Token expirado';
            return false;
        }
    
        $this->query = "UPDATE usuarios SET cuenta_activa = 1, token = NULL, fecha_creacion_token = NULL WHERE token = :token";
        $this->get_results_from_query();
    
        $this->mensaje = 'Cuenta activada. Ya puedes iniciar sesión.';
        return true;
    }

    // Método para iniciar sesión
    public function login($email, $password)
    {
        $this->query = "SELECT * FROM usuarios WHERE email = :email AND cuenta_activa = 1";
        $this->parametros = ['email' => $email];
        $this->get_results_from_query();
    
        if (count($this->rows) > 0) {
            $usuario = $this->rows[0];
            $password_almacenada = $usuario['password'];
    
            // Comparación directa sin hashing
            if ($password === $password_almacenada) {
                return $usuario; //  Usuario autenticado correctamente
            } else {
                return null; //  Contraseña incorrecta
            }
        } else {
            return null; // x Usuario no encontrado
        }
    }

    // Método para enviar un correo de activación
    public function enviarCorreoActivacion()
    {
        $mailer = EmailConfig::getMailer();
    
        // Verificar si el usuario tiene un token
        $this->query = "SELECT token FROM usuarios WHERE email = :email";
        $this->parametros = ['email' => $this->email];
        $this->get_results_from_query();
    
        if (empty($this->rows[0]['token'])) {
            echo "<h2>Error: No se pudo obtener el token de activación.</h2>";
            return;
        }
    
        $token = $this->rows[0]['token'];
    
        $asunto = "Activa tu cuenta en Portfolio";
        $mensaje = "
            <h2>Bienvenido {$this->nombre}!</h2>
            <p>Para activar tu cuenta, haz clic en el siguiente enlace:</p>
            <a href='http://portfolio.local/verificar/{$token}'>Activar cuenta</a>
            <p>Si no creaste esta cuenta, ignora este correo.</p>
        ";
    
        $email = (new Email())
            ->from('noreply@portfolio.local')
            ->to($this->email)
            ->subject($asunto)
            ->html($mensaje);
    
        try {
            $mailer->send($email);
            echo "<h2>Correo de activación enviado correctamente a {$this->email}</h2>";
        } catch (\Exception $e) {
            echo "<h2>Error al enviar el correo: " . $e->getMessage() . "</h2>";
        }
    }
}