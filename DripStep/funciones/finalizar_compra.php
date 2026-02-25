<?php
/**
 * CAPA DE NEGOCIO: Procesar Pedido y Descontar Stock
 */
session_start();
require_once '../config/db.php'; 

// 1. SEGURIDAD
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header("Location: ../index.php");
    exit();
}

// Datos generales
$id_usuario = $_SESSION['usuario']['id'];
$fecha = date('Y-m-d H:i:s');
$total_pedido = 0;

// Calcular total
foreach ($_SESSION['carrito'] as $producto) {
    $total_pedido += ($producto['precio'] * $producto['cantidad']);
}

// INICIAR TRANSACCIÓN
$db->begin_transaction();

try {
    // A) INSERTAR PEDIDO
    $sql_pedido = "INSERT INTO pedidos (usuario_id, fecha, total, estado) VALUES (?, ?, ?, 'confirmado')";
    $stmt = $db->prepare($sql_pedido);
    $stmt->bind_param("isd", $id_usuario, $fecha, $total_pedido);
    $stmt->execute();
    
    $id_pedido = $db->insert_id; 

    // B) INSERTAR DETALLES Y DESCONTAR STOCK
    foreach ($_SESSION['carrito'] as $item) {
        $id_prod = $item['id'];
        $cantidad = $item['cantidad'];

        // 1. Insertar detalle (SIN PRECIO)
        $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt_detalle = $db->prepare($sql_detalle);
        $stmt_detalle->bind_param("iii", $id_pedido, $id_prod, $cantidad);
        $stmt_detalle->execute();

        // 2. Descontar Stock
        $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt_stock = $db->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $id_prod);
        $stmt_stock->execute();
    }

    // C) CONFIRMAR
    $db->commit();
    unset($_SESSION['carrito']); 
    
    // REDIRECCIÓN FINAL (ÉXITO)
    header("Location: ../views/mis_pedidos.php?status=ok");
    exit();

} catch (Exception $e) {
    $db->rollback();
    // En caso de error, vuelta al carrito
    header("Location: ../views/views_carrito.php?status=error");
    exit();
}
?>