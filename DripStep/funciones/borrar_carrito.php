<?php
/**
 * GESTOR DE ACCIONES (borrar_carrito.php)
 * Con control de Stock
 */
session_start();
require_once '../config/db.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (isset($_SESSION['carrito'][$id])) {
        
        $accion = isset($_GET['accion']) ? $_GET['accion'] : 'borrar';

        // --- CASO A: SUMAR CANTIDAD (CON CONTROL DE STOCK) ---
        if ($accion == 'sumar') {
            // 1. Preguntamos a la base de datos cuánto stock queda REALMENTE
            $sql = "SELECT stock FROM productos WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $producto_bd = $resultado->fetch_assoc();

            $stock_maximo = $producto_bd['stock'];
            $cantidad_en_carrito = $_SESSION['carrito'][$id]['cantidad'];

            // 2. Solo sumamos si no superamos el stock
            if ($cantidad_en_carrito < $stock_maximo) {
                $_SESSION['carrito'][$id]['cantidad']++;
            } else {
               
            }
        }

        // --- CASO B: RESTAR CANTIDAD ---
        elseif ($accion == 'restar') {
            if ($_SESSION['carrito'][$id]['cantidad'] > 1) {
                $_SESSION['carrito'][$id]['cantidad']--;
            }
        }

        // --- CASO C: BORRAR ---
        elseif ($accion == 'borrar') {
            unset($_SESSION['carrito'][$id]);
        }
    }
}

// Redirigimos al carrito
header("Location: ../views/views_carrito.php");
exit();
?>