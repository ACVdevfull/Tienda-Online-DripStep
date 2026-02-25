<?php
// Archivo: funciones/login_usuario.php
// Propósito: Validar credenciales y crear la sesión

require_once '../config/db.php';

// Comprobamos si llegan datos por POST
if(isset($_POST['email']) && isset($_POST['password'])){
    
    // Limpiamos los datos (seguridad básica)
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Buscamos al usuario por su email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $login = $db->query($sql);

    if($login && $login->num_rows == 1){
        // El usuario existe, sacamos sus datos
        $usuario = $login->fetch_assoc();

        // 2. Verificamos la contraseña (hash)
        $verify = password_verify($password, $usuario['password']);

        if($verify){
            // Guardamos los datos importantes en la SESIÓN
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigimos según el rol
            if($usuario['rol'] == 'admin'){
                header("Location: ../views/panel_admin.php");
            } else {
                header("Location: ../index.php");
            }
        } else {
            // Contraseña mal
            $_SESSION['error_login'] = "Login incorrecto: Contraseña errónea.";
            header("Location: ../views/login.php");
        }
    } else {
        // Email no existe
        $_SESSION['error_login'] = "Login incorrecto: El email no existe.";
        header("Location: ../views/login.php");
    }

} else {
    // Si intentan entrar aquí sin enviar el formulario
    header("Location: ../index.php");
}
?>