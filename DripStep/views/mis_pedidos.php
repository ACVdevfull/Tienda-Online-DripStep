<?php
// 1. Iniciamos sesión y conectamos a la base de datos
session_start();
require_once '../config/db.php';

// 2. Seguridad: Si no está logueado, lo mandamos al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];
$nombre_usuario = $_SESSION['usuario']['nombre'];

// 3. Consultamos los pedidos de este usuario específico
// Ordenamos por fecha DESC para que el más reciente salga arriba
$sql = "SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY fecha DESC";
$resultado = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos | DripStep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>📦 Mis Pedidos Realizados</h2>
                    <span class="badge bg-dark px-3 py-2">Usuario: <?= $nombre_usuario ?></span>
                </div>

                <?php if(isset($_GET['status']) && $_GET['status'] == 'ok'): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <strong>¡Compra realizada con éxito!</strong> Gracias por confiar en DripStep. 👟
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-4">ID Pedido</th>
                                        <th>Fecha y Hora</th>
                                        <th>Estado</th>
                                        <th class="text-end pe-4">Total Pagado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($resultado->num_rows > 0): ?>
                                        <?php while($row = $resultado->fetch_assoc()): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold">#<?= $row['id'] ?></td>
                                                
                                                <td>
                                                    <?= date('d/m/Y - H:i', strtotime($row['fecha'])) ?>h
                                                </td>
                                                
                                                <td>
                                                    <span class="badge rounded-pill bg-success">
                                                        <?= ucfirst($row['estado']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4 fw-bold text-primary">
                                                    <?= number_format($row['total'], 2) ?> €
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-cart-x fs-1"></i><br>
                                                Aún no has realizado ninguna compra.<br>
                                                <a href="../index.php" class="btn btn-outline-dark mt-3">Ir a la tienda</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="../index.php" class="btn btn-secondary">← Volver a la Tienda</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2025 DripStep - Historial de Clientes</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>