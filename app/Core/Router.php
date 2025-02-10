<?php
/**
 * Summary of namespace App\Controllers
 */

 
 namespace App\Core;
 class Router
{
    private $routes = array();
    public function add($route )
    {
    $this->routes[] = $route;
    }
    public function match(string $request)
    {
        foreach ($this->routes as $route) {
            $patron = $route['path'];
            if (preg_match($patron, $request)) {
                return $route;
            }
        }
        return null; // Devuelve null si no hay coincidencia
    }
    
}