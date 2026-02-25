<?php
require_once '../config/db.php';

// Verificamos si somos admin y si nos pasan un ID
if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin' && isset($_GET['id'])){
    
    $id = (int)$_GET['id'];
    
    // 1. Buscamos el nombre de la foto
    $query = mysqli_query($db, "SELECT imagen FROM productos WHERE id = $id");
    $producto = mysqli_fetch_assoc($query);
    
    if($producto){
        $ruta_foto = '../fotos/' . $producto['imagen'];
        if(file_exists($ruta_foto)){
            unlink($ruta_foto); // Esto borra el archivo físico
        }
    }

    // 2. Borramos de la base de datos
    $borrar = mysqli_query($db, "DELETE FROM productos WHERE id = $id");
}

// Volver al panel
header("Location: ../views/panel_admin.php");
?>