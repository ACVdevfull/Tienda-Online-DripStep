<?php
// funciones/añadir_carrito.php
session_start();
require_once '../config/db.php'; // Necesitamos conexión a BD

if(isset($_POST['id'])){
    
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    
    // 1. Consultamos el stock real en la base de datos
    $sql = "SELECT stock FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $prod_bd = $res->fetch_assoc();
    
    $stock_real = $prod_bd['stock']; // Stock total en el almacén

    // 2. Comprobamos cuánto llevamos en el carrito
    $cantidad_actual_carrito = 0;
    if(isset($_SESSION['carrito'][$id])){
        $cantidad_actual_carrito = $_SESSION['carrito'][$id]['cantidad'];
    }

    // 3. LOGICA: Solo añadimos si (lo que tengo + 1) es menor o igual al stock
    if (($cantidad_actual_carrito + 1) <= $stock_real) {
        
        $producto = [
            "id" => $id,
            "nombre" => $nombre,
            "precio" => $precio,
            "imagen" => $imagen,
            "cantidad" => 1
        ];

        if(isset($_SESSION['carrito'])){
            if(isset($_SESSION['carrito'][$id])){
                $_SESSION['carrito'][$id]['cantidad']++;
            } else {
                $_SESSION['carrito'][$id] = $producto;
            }
        } else {
            $_SESSION['carrito'][$id] = $producto;
        }
        
        // Redirigimos con éxito
        header("Location: ../index.php?status=agregado");
        
    } else {
        // SI NO HAY STOCK: Redirigimos con aviso de error
        header("Location: ../index.php?status=error_stock");
    }
    exit;
}
?>