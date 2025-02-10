<?php


namespace App\Models;
require_once("DBAbstractModel.php");

class CategoriaSkills extends DBAbstractModel{

    private static $instancia;
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function __clone()
    {
        trigger_error("La clonación no está permitida.", E_USER_ERROR);
    }

    private $id;
    private $categoria;

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function set(){}
    public function get($id = "") {}

    public function edit() {}

    public function delete() {}

    public function getAll()
    {
        $this->query = "SELECT * FROM categorias_skills";
        $this->get_results_from_query();
        return $this->rows;
    }
}