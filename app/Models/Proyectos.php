<?php

namespace App\Models;
require_once("DBAbstractModel.php");

class Proyectos extends DBAbstractModel {

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
    private $titulo;
    private $descripcion;
    private $logo;
    private $tecnologias;
    private $visible;
    private $created_at;
    private $updated_at;
    private $usuarios_id;

    // Setters para las propiedades de la clase
    public function setID($id) {
        $this->id = $id;
    }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setLogo($logo) { $this->logo = $logo; }
    public function setTecnologias($tecnologias) { $this->tecnologias = $tecnologias; }
    public function setVisible($visible) { $this->visible = $visible; }
    public function setUsuariosId($usuarios_id) { $this->usuarios_id = $usuarios_id; }
    
    public function getMensaje() { return $this->mensaje; }

    // Método para insertar un nuevo proyecto en la base de datos
    public function set(): void {
        $this->query = "INSERT INTO proyectos (titulo, descripcion, logo, tecnologias, visible, usuarios_id) 
                        VALUES (:titulo, :descripcion, :logo, :tecnologias, :visible, :usuarios_id)";
        $this->parametros = [
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'logo' => $this->logo,
            'tecnologias' => $this->tecnologias,
            'visible' => $this->visible,
            'usuarios_id' => $this->usuarios_id
        ];
        $this->get_results_from_query();
        $this->mensaje = "Proyecto añadido correctamente.";
    }

    // Método para obtener proyecto(s) de la base de datos
    public function get($id = "") {
        $this->query = $id ? "SELECT * FROM proyectos WHERE id = :id" : "SELECT * FROM proyectos WHERE visible = 1";
        $this->parametros = $id ? ['id' => $id] : [];
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para actualizar un proyecto en la base de datos
    public function edit() {
        $this->query = "UPDATE proyectos SET titulo = :titulo, descripcion = :descripcion, logo = :logo, 
                        tecnologias = :tecnologias, visible = :visible WHERE id = :id";
        $this->parametros = [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'logo' => $this->logo,
            'tecnologias' => $this->tecnologias,
            'visible' => $this->visible
        ];
        $this->get_results_from_query();
        $this->mensaje = "Proyecto actualizado correctamente.";
    }

    // Método para eliminar un proyecto de la base de datos
    public function delete($id = "") {
        $this->query = "DELETE FROM proyectos WHERE id = :id";
        $this->parametros = ['id' => (int)$id]; // Convertimos a entero para mayor seguridad
        $this->get_results_from_query();
        $this->mensaje = "Proyecto eliminado correctamente.";
    }

    // Método para ocultar un proyecto (establecer visible a 0)
    public function ocultarProyecto($id)
    {
        $this->query = "UPDATE proyectos SET visible = NOT visible WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Proyecto ocultado correctamente.";
    }

    // Método para obtener proyectos por ID de usuario
    public function getProyectosPorUsuariosId($usuarios_id) {
        $this->query = "SELECT * FROM proyectos WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        return $this->rows;
    }
}