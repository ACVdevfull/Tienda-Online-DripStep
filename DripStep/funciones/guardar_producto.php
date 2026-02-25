<?php
// Archivo: funciones/guardar_producto.php

// 1. Configuración de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Verificar que vienen datos por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/panel_admin.php");
    exit();
}

// 3. Incluir conexión (Capa de Acceso a Datos)
require_once '../config/db.php'; 

if (!isset($db)) {
    die("Error: No se encontró la variable de conexión.");
}

// 4. Recoger datos
$nombre      = $_POST['nombre'] ?? '';
$precio      = $_POST['precio'] ?? 0;
$stock       = $_POST['stock'] ?? 0;
$descripcion = $_POST['descripcion'] ?? '';

// 5. PROCESAR LA IMAGEN
$nombre_imagen = null; 

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $directorio_destino = '../fotos/';
    
    if (!is_dir($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    
    $info_archivo = pathinfo($_FILES['imagen']['name']);
    $extension = $info_archivo['extension'];
    $nombre_imagen = date('YmdHis') . "_" . rand(100, 999) . "." . $extension;
    
    $ruta_final = $directorio_destino . $nombre_imagen;
    
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_final)) {
        header("Location: ../views/panel_admin.php?status=error");
        exit();
    }
}

// 6. INSERTAR EN LA BASE DE DATOS 
$sql = "INSERT INTO productos (nombre, precio, stock, descripcion, imagen) VALUES (?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sdiss", $nombre, $precio, $stock, $descripcion, $nombre_imagen);
    
    if ($stmt->execute()) {
        header("Location: ../views/panel_admin.php?status=ok");
        exit();
    } else {
        header("Location: ../views/panel_admin.php?status=error");
        exit();
    }
    
    $stmt->close();
}

$db->close();
?>