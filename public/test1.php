<?php
/**
 * Archivo test para pruebas
 * @author  Carlos Borreguero Redondo
 */

// Requerimos los archivo de configuracion, y la clase Mascotas
require_once("../app/conf/config.php");
require_once("../app/Models/Usuarios.php");

use App\Models\Usuarios;

// Creamos mascotas sin utilizar el patron de diseño
/* $mascota1 = new Mascotas();
$mascota2 = new Mascotas(); */

// Creamos mascotas utilizando el Singleton
/* $mascota3 = Mascotas::getInstancia();
$mascota = Mascotas::getInstancia(); */

// De esa forma solo se ha creado un objeto
/* $mascota -> setNombre("Rocky");
$mascota -> setPeso("24");
$mascota -> setRaza("Bichon Maltes");

$mascota->set();

$mascota -> get(30); */

// Creamos un objeto
$usuario = Usuarios::getInstancia();

$usuario -> setNombre("Carlos");
$usuario -> setApellidos("Borreguero Redondo");
$usuario -> setCategoriaProfesional("Desarrollador Web");
$usuario -> setEmail("carlos@gmail.com");
$usuario -> setResumenPerfil("Soy un desarrollador web con experiencia en PHP, Laravel, Symfony, etc.");
$usuario -> setPassword("carlos");
$usuario -> setVisible(1);
$usuario -> setCuentaActiva(1);



/* $mascota->delete(20);  */

var_dump($usuario);





