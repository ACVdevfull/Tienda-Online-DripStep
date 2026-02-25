<?php
// funciones/actualizar_producto.php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {

    $id          = (int)$_POST['id'];
    $nombre      = mysqli_real_escape_string($db, $_POST['nombre']);
    $precio      = (float)$_POST['precio'];
    $stock       = (int)$_POST['stock'];
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);

    // 1. Recuperar datos antiguos (Necesitamos saber el nombre de la foto vieja)
    $query_antigua = mysqli_query($db, "SELECT imagen FROM productos WHERE id = $id");
    $datos_antiguos = mysqli_fetch_assoc($query_antigua);
    $nombre_imagen = $datos_antiguos['imagen']; // Por defecto, nos quedamos con la que había

    // 2. ¿El usuario ha subido una NUEVA foto?
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        
        $directorio = '../fotos/';
        $info = pathinfo($_FILES['imagen']['name']);
        $nuevo_nombre = date('YmdHis') . "_" . rand(100, 999) . "." . $info['extension'];
        
        // Subir la nueva
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $nuevo_nombre)) {
            
            // Si había una foto antes y el archivo existe, lo borramos
            if (!empty($nombre_imagen) && file_exists($directorio . $nombre_imagen)) {
                unlink($directorio . $nombre_imagen);
            }
            
            // Actualizamos la variable para la base de datos
            $nombre_imagen = $nuevo_nombre;
        }
    }

    // 3. ACTUALIZAR EN BASE DE DATOS (UPDATE)
    // Usamos sentencias preparadas por seguridad
    $sql = "UPDATE productos SET nombre=?, precio=?, stock=?, descripcion=?, imagen=? WHERE id=?";
    
    $stmt = $db->prepare($sql);
    
    $stmt->bind_param("sdissi", $nombre, $precio, $stock, $descripcion, $nombre_imagen, $id);

    if ($stmt->execute()) {
        header("Location: ../views/panel_admin.php?status=editado");
    } else {
header("Location: ../views/panel_admin.php?status=error");    }

    $stmt->close();
    $db->close();

} else {
    header("Location: ../views/panel_admin.php");
}
?>