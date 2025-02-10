<?php
namespace App\Models;
require_once("DBAbstractModel.php");

class Skills extends DBAbstractModel
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
    private $habilidades;
    private $usuarios_id;

    // Setters para las propiedades de la clase
    public function setID($id) { $this->id = $id; }
    public function setHabilidades($habilidades) { $this->habilidades = $habilidades; }
    public function setUsuariosId($usuarios_id) { $this->usuarios_id = $usuarios_id; }

    public function getMensaje() { return $this->mensaje; }

    // Método para insertar una nueva habilidad en la base de datos
    public function set() {
        $this->query = "INSERT INTO skills (habilidades, usuarios_id) VALUES (:habilidades, :usuarios_id)";
        $this->parametros = [
            'habilidades' => $this->habilidades,
            'usuarios_id' => $this->usuarios_id
        ];
        $this->get_results_from_query();
        $this->mensaje = "Skill añadida correctamente";
    }

    // Método para obtener habilidad(es) de la base de datos
    public function get($id = '') {
        $this->query = $id ? "SELECT * FROM skills WHERE id = :id" : "SELECT * FROM skills";
        $this->parametros = $id ? ['id' => $id] : [];
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para actualizar una habilidad en la base de datos
    public function edit() {
        $this->query = "UPDATE skills SET habilidades = :habilidades WHERE id = :id";
        $this->parametros = [
            'id' => $this->id,
            'habilidades' => $this->habilidades
        ];
        $this->get_results_from_query();
        $this->mensaje = "Skill actualizada correctamente";
    }

    // Método para eliminar una habilidad de la base de datos
    public function delete() {
        $this->query = "DELETE FROM skills WHERE id = :id";
        $this->parametros = ['id' => $this->id];
        $this->get_results_from_query();
        $this->mensaje = "Skill eliminada correctamente";
    }

    // Método para obtener todas las habilidades de la base de datos
    public function getAllSkills() {
        $this->query = "SELECT * FROM skills";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para obtener habilidades por ID de usuario
    public function getSkillsPorUsuariosId($usuarios_id) {
        $this->query = "SELECT * FROM skills WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para cambiar la visibilidad de una habilidad
    public function ocultarSkill($id) {
        $this->query = "UPDATE skills SET visible = NOT visible WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Estado de visibilidad cambiado";
    }
}