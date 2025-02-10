<?php
namespace App\Models;
require_once("DBAbstractModel.php");

class RedesSociales extends DBAbstractModel
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
    private $redes_sociales;
    private $url;
    private $visible;
    private $usuarios_id;

    // Setters para las propiedades de la clase
    public function setID($id) { $this->id = (int) $id; }
    public function setRedesSociales($redes_sociales) { $this->redes_sociales = $redes_sociales; }
    public function setUrl($url) { $this->url = $url; }
    public function setVisible($visible) { $this->visible = $visible; }
    public function setUsuariosId($usuarios_id) { $this->usuarios_id = $usuarios_id; }

    public function getMensaje() { return $this->mensaje; }

    // Método para insertar una nueva red social en la base de datos
    public function set() {
        $this->query = "INSERT INTO redes_sociales (redes_sociales, url, visible, usuarios_id) VALUES (:redes_sociales, :url, :visible, :usuarios_id)";
        $this->parametros = [
            'redes_sociales' => $this->redes_sociales,
            'url' => $this->url,
            'visible' => $this->visible,
            'usuarios_id' => $this->usuarios_id
        ];
        $this->get_results_from_query();
        $this->mensaje = "Red social añadida correctamente";
    }

    // Método para obtener red(es) social(es) de la base de datos
    public function get($id = '') {
        $this->query = $id ? "SELECT * FROM redes_sociales WHERE id = :id" : "SELECT * FROM redes_sociales";
        $this->parametros = $id ? ['id' => $id] : [];
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para actualizar una red social en la base de datos
    public function edit() {
        $this->query = "UPDATE redes_sociales SET redes_sociales = :redes_sociales, url = :url WHERE id = :id";
        $this->parametros = [
            'id' => $this->id,
            'redes_sociales' => $this->redes_sociales,
            'url' => $this->url
        ];
        $this->get_results_from_query();
        $this->mensaje = "Red social actualizada correctamente";
    }

    // Método para eliminar una red social de la base de datos
    public function delete($id = "") {
        $this->query = "DELETE FROM redes_sociales WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Red social eliminada correctamente";
    }

    // Método para obtener redes sociales por ID de usuario
    public function getRedesSocialesPorUsuarioId($usuarios_id) {
        $this->query = "SELECT * FROM redes_sociales WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para cambiar la visibilidad de una red social
    public function ocultarRedSocial($id) {
        $this->query = "UPDATE redes_sociales SET visible = NOT visible WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Estado de visibilidad cambiado";
    }
}