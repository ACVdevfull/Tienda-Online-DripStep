<?php
// funciones/actualizar_usuario.php
require_once '../config/db.php';

// Verificar si es admin y viene por POST
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'){

    $id = (int)$_POST['id'];
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $rol = $_POST['rol'];
    $password = $_POST['password'];

    // Lógica para la contraseña
    if(!empty($password)){
        // Si el admin escribió algo, cambiamos la contraseña (Cifrada siempre)
        $pass_segura = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', rol='$rol', password='$pass_segura' WHERE id=$id";
    } else {
        // Si está vacía, actualizamos todo MENOS la contraseña
        $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', rol='$rol' WHERE id=$id";
    }

    // Ejecutar consulta
    if($db->query($sql)){
        header("Location: ../views/panel_admin.php?status=ok");
    } else {
header("Location: ../views/panel_admin.php?status=error");    }

} else {
    // Si intentan entrar directo
    header("Location: ../index.php");
}
?>