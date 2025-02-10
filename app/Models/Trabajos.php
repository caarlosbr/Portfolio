<?php

namespace App\Models;
require_once("DBAbstractModel.php");

class Trabajos extends DBAbstractModel {

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
    private $fecha_inicio;
    private $fecha_final;
    private $logros;
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
    public function setFechaInicio($fecha_inicio) { $this->fecha_inicio = $fecha_inicio; }
    public function setFechaFinal($fecha_final) { $this->fecha_final = $fecha_final; }
    public function setLogros($logros) { $this->logros = $logros; }
    public function setVisible($visible) { $this->visible = $visible; }
    public function setUsuariosId($usuarios_id) { $this->usuarios_id = $usuarios_id; }
    
    public function getMensaje() { return $this->mensaje; }

    // Método para insertar un nuevo trabajo en la base de datos
    public function set(): void {
        $this->query = "INSERT INTO trabajos (titulo, descripcion, fecha_inicio, fecha_final, logros, visible, usuarios_id) 
                        VALUES (:titulo, :descripcion, :fecha_inicio, :fecha_final, :logros, :visible, :usuarios_id)";
        $this->parametros = [
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_final' => $this->fecha_final,
            'logros' => $this->logros,
            'visible' => $this->visible,
            'usuarios_id' => $this->usuarios_id
        ];
        $this->get_results_from_query();
        $this->mensaje = "Trabajo añadido correctamente.";
    }

    // Método para obtener trabajo(s) de la base de datos
    public function get($id = "") {
        $this->query = $id ? "SELECT * FROM trabajos WHERE id = :id" : "SELECT * FROM trabajos";
        $this->parametros = $id ? ['id' => $id] : [];
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método para actualizar un trabajo en la base de datos
    public function edit() {
        $this->query = "UPDATE trabajos SET titulo = :titulo, descripcion = :descripcion, fecha_inicio = :fecha_inicio, 
                        fecha_final = :fecha_final, logros = :logros, visible = :visible WHERE id = :id";
        $this->parametros = [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_final' => $this->fecha_final,
            'logros' => $this->logros,
            'visible' => $this->visible
        ];
        $this->get_results_from_query();
        $this->mensaje = "Trabajo actualizado correctamente.";
    }

    // Método para eliminar un trabajo de la base de datos
    public function delete($id = "") {
        $this->query = "DELETE FROM trabajos WHERE id = :id";
        $this->parametros = ['id' => (int)$id]; // Convertimos a entero por seguridad
        $this->get_results_from_query();
        $this->mensaje = "Trabajo eliminado correctamente";
    }
    
    // Método para cambiar la visibilidad de un trabajo
    public function ocultarTrabajo($id) {
        $this->query = "UPDATE trabajos SET visible = NOT visible WHERE id = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        $this->mensaje = "Estado de visibilidad cambiado.";
    }
    
    // Método para obtener trabajos por ID de usuario
    public function getTrabajosPorUsuariosId($usuarios_id) {
        $this->query = "SELECT * FROM trabajos WHERE usuarios_id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        return $this->rows;
    }
}