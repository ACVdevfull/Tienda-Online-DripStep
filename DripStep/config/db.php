<?php

$servidor = "localhost";
$usuario = "root";     
$password = "";         
$base_datos = "db_dripstep";

// Crear conexión
$db = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar si hay error
if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}

$db->query("SET NAMES 'utf8'");

// Iniciar la sesión aquí para no tener que repetirlo en todos los archivos
if(!isset($_SESSION)){
    session_start();
}
?>
