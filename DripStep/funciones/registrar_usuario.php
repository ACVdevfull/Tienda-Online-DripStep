<?php
// 1. Activar errores para depuración durante el desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Conectamos a la base de datos (Ruta correcta desde carpeta funciones)
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 3. Limpiamos los datos para evitar Inyección SQL básica
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    
    // 4. Encriptar la contraseña 
    $password_encriptada = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // 5. Consulta SQL 
    $sql = "INSERT INTO usuarios (nombre, email, password, rol) 
            VALUES ('$nombre', '$email', '$password_encriptada', 'cliente')";

    if ($db->query($sql)) {
        // Registro éxito -> Vamos al login con aviso verde
        header("Location: ../views/login.php?status=registrado");
        exit(); 
    } else {
        // Si falla (ej: email duplicado), muestra el error de MySQL
        die("Error en la base de datos: " . $db->error);
    }
} else {
    // Si alguien intenta entrar a este archivo sin enviar el formulario, lo echamos al registro
    header("Location: ../views/registro_usuario.php");
    exit();
}
?>