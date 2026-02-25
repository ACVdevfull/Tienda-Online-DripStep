<?php
// Archivo: views/ver_producto.php
session_start();
require_once '../config/db.php'; // Conexión a la base de datos

// 1. COMPROBAR ID: Si no llega un ID por la URL, devolvemos al usuario al inicio
if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id_producto = $_GET['id'];

// 2. CONSULTA: Buscamos esa zapatilla específica en la BD
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

// Si el ID no existe en la BD (alguien puso un número inventado), volvemos
if (!$producto) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $producto['nombre'] ?> | DripStep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark mb-5">
        <div class="container">
            <a class="navbar-brand" href="../index.php">⬅️ Volver a la Tienda</a>
            <a href="views_carrito.php" class="btn btn-outline-warning">
                🛒 Ver Carrito
                <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                    <span class="badge bg-warning text-dark ms-1"><?= count($_SESSION['carrito']) ?></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row gx-5">
            
            <div class="col-md-6 mb-4">
                <div class="producto-detalle-container shadow-sm">
                    <?php if(!empty($producto['imagen'])): ?>
                        <img src="../fotos/<?= $producto['imagen'] ?>" class="img-fluid" alt="<?= $producto['nombre'] ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/500x500?text=Sin+Imagen" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h6 class="text-muted text-uppercase fw-bold">DripStep Originals</h6>
                <h1 class="display-4 fw-bold mb-3"><?= $producto['nombre'] ?></h1>
                
                <h2 class="text-primary fw-bold mb-4"><?= number_format($producto['precio'], 2) ?> €</h2>

                <p class="lead text-secondary mb-4">
                    <?= !empty($producto['descripcion']) ? nl2br($producto['descripcion']) : "Este modelo exclusivo combina estilo y comodidad. Perfecto para el día a día o para destacar en cualquier ocasión."; ?>
                </p>

                <div class="mb-4">
                    <?php if($producto['stock'] > 5): ?>
                        <span class="badge bg-success p-2">✅ En Stock (<?= $producto['stock'] ?> ud.)</span>
                    <?php elseif($producto['stock'] > 0): ?>
                        <span class="badge bg-warning text-dark p-2">⚠️ ¡Quedan pocas! (<?= $producto['stock'] ?> ud.)</span>
                    <?php else: ?>
                        <span class="badge bg-danger p-2">❌ Agotado</span>
                    <?php endif; ?>
                </div>

                <hr>

                <?php if($producto['stock'] > 0): ?>
                    <form action="../funciones/añadir_carrito.php" method="POST">
                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                        <input type="hidden" name="nombre" value="<?= $producto['nombre'] ?>">
                        <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                        <input type="hidden" name="imagen" value="<?= $producto['imagen'] ?>">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-lg py-3">
                                🛒 AÑADIR A LA CESTA
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100" disabled>No disponible</button>
                <?php endif; ?>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">Envío gratis a partir de 50€ · Devoluciones en 30 días</small>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>