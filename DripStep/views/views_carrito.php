<?php
session_start();
// views/views_carrito.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Carrito - DRIPSTEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">⬅️ Seguir Comprando</a>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">🛒 Tu Carrito de Compras</h2>

    <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
        
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Producto</th>
                        <th>Precio</th>
                        <th class="carrito-col-cantidad">Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_pagar = 0;
                    foreach($_SESSION['carrito'] as $indice => $elemento): 
                        $subtotal = $elemento['precio'] * $elemento['cantidad'];
                        $total_pagar += $subtotal;
                    ?>
                    <tr>
                        <td class="text-start">
                            <?php if(!empty($elemento['imagen'])): ?>
                                <img src="../fotos/<?= $elemento['imagen'] ?>" width="50" class="me-2"> 
                            <?php endif; ?>
                            <strong><?= $elemento['nombre']; ?></strong>
                        </td>
                        <td><?= number_format($elemento['precio'], 2); ?> €</td>
                        
                        <td class="carrito-col-cantidad">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                
                                <a href="../funciones/borrar_carrito.php?id=<?= $elemento['id'] ?>&accion=restar" 
                                   class="btn btn-sm btn-outline-secondary fw-bold carrito-btn-accion">-</a>
                                
                                <span class="fw-bold fs-5"><?= $elemento['cantidad']; ?></span>

                                <a href="../funciones/borrar_carrito.php?id=<?= $elemento['id'] ?>&accion=sumar" 
                                   class="btn btn-sm btn-outline-primary fw-bold carrito-btn-accion">+</a>
                            </div>
                        </td>

                        <td class="fw-bold"><?= number_format($subtotal, 2); ?> €</td>
                        
                        <td>
                            <a href="../funciones/borrar_carrito.php?id=<?= $elemento['id']; ?>&accion=borrar" 
                               class="btn btn-danger btn-sm">
                                🗑️ Borrar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 p-4 bg-light border rounded shadow-sm">
            <h3 class="m-0 fw-bold">Total a pagar: <span class="text-primary"><?= number_format($total_pagar, 2); ?> €</span></h3>
            
            <a href="../funciones/finalizar_compra.php" class="btn btn-success btn-sm px-5 shadow">
                ✅ Confirmar Pedido
            </a>
        </div>

    <?php else: ?>
        <div class="alert alert-info text-center">
            <h3 >Tu carrito está vacío 😢</h3>
            <a href="../index.php" class="btn btn-primary mt-3">Ir a la tienda</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>