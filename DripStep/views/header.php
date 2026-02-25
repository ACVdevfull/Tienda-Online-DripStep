<?php
// =============================================================================
// 1. LÓGICA DE CONTROL (CONTROLLER)
// =============================================================================

// A. CONTROL DE SESIÓN
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// B. DETECTOR DE RUTAS Y CONFIGURACIÓN
// Detectamos si estamos en la raíz para ajustar los enlaces automáticamente
$ruta = file_exists('config/db.php') ? '' : '../';
// Variable auxiliar para evitar repetir ternarios en los href
$views_path = ($ruta === '') ? 'views/' : ''; 

// C. ESTADO DEL USUARIO
$usuario      = $_SESSION['usuario'] ?? null; // Null coalescing operator (PHP 7+)
$esta_logueado = !empty($usuario);
$es_admin      = ($esta_logueado && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$nombre_user   = $esta_logueado ? $usuario['nombre'] : '';

// D. LÓGICA DEL CARRITO 
$cantidad_total = 0;
if (isset($_SESSION['carrito'])) {
    // Usamos array_column y array_sum para evitar bucles foreach en la vista
    $cantidad_total = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
}

// E. GESTIÓN DE ALERTAS / MENSAJES DE ESTADO
$alerta = null; // Inicializamos vacío
if (isset($_GET['status'])) {
    switch($_GET['status']) {
        case 'ok':
            $alerta = ['tipo' => 'success', 'icono' => '✅', 'msg' => '<strong>¡Éxito!</strong> Operación realizada correctamente.'];
            break;
        case 'registrado':
            $alerta = ['tipo' => 'success', 'icono' => '👋', 'msg' => '<strong>¡Bienvenido!</strong> Tu cuenta ha sido creada.'];
            break;
        case 'agregado':
            $alerta = ['tipo' => 'success', 'icono' => '🛒', 'msg' => '<strong>¡Añadido!</strong> Producto agregado al carrito.'];
            break;
        case 'error_stock':
            $alerta = ['tipo' => 'warning', 'icono' => '⚠️', 'msg' => '<strong>¡Stock Insuficiente!</strong> No quedan más unidades.'];
            break;
        case 'error':
        default:
            $alerta = ['tipo' => 'danger', 'icono' => '❌', 'msg' => '<strong>Error:</strong> Ha ocurrido un problema.'];
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DripStep - Sneakers Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $ruta ?>css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= $ruta ?>index.php">👟 DripStep</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item">
          <a class="nav-link" href="<?= $ruta ?>index.php">Inicio</a>
        </li>

        <li class="nav-item">
            <a class="nav-link position-relative" href="<?= $views_path ?>views_carrito.php">
                🛒 Carrito
                <?php if($cantidad_total > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $cantidad_total ?>
                    </span>
                <?php endif; ?>
            </a>
        </li>

        <?php if($esta_logueado): ?>
            
            <li class="nav-item ms-2">
                <a class="nav-link" href="<?= $views_path ?>mis_pedidos.php">📦 Mis Pedidos</a>
            </li>

            <?php if($es_admin): ?>
                <li class="nav-item ms-2">
                    <a class="nav-link text-warning" href="<?= $views_path ?>panel_admin.php">⚙️ Panel Admin</a>
                </li>
            <?php endif; ?>

            <li class="nav-item ms-3">
                <a class="nav-link text-danger border border-danger rounded px-2" href="<?= $ruta ?>funciones/logout.php">
                    Salir (<?= htmlspecialchars($nombre_user) ?>)
                </a>
            </li>

        <?php else: ?>
            <li class="nav-item ms-2"><a class="nav-link" href="<?= $views_path ?>registro_usuario.php">Registrarse</a></li>
            <li class="nav-item ms-2"><a class="nav-link" href="<?= $views_path ?>login.php">Entrar</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <?php if ($alerta): ?>
        <div class="alert alert-<?= $alerta['tipo'] ?> alert-dismissible fade show" role="alert">
            <?= $alerta['icono'] ?> <?= $alerta['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>