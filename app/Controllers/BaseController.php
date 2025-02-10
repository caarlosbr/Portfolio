<?php

namespace App\Controllers;

class BaseController
{
    /**
     * Renderiza una vista y permite pasar datos.
     *
     * @param string $view Ruta de la vista a renderizar.
     * @param array $data Array asociativo con los datos para la vista.
     */
    public function renderHTML($view, $data = [])
    {
        if (!empty($data)) {
            extract($data); // Extrae las variables del array asociativo
        }
        // Cambiar la ruta base para apuntar a app/vistas
        $basePath = dirname(__DIR__); // Ruta base al directorio 'app'
        require $basePath . "/views/" . $view;
    }
    
}
