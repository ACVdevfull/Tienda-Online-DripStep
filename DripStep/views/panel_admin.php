<?php
// 1. Seguridad y Conexión
require_once '../config/db.php'; 

// Verificar sesión y rol de admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: login.php");
    exit;
}

include 'header.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Panel de Administración DRIPSTEP</h2>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'ok'): ?>
        <div class="alert alert-success">¡Operación realizada con éxito!</div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-plus-circle"></i> Añadir Zapatilla Nueva
                </div>
                <div class="card-body">
                    <form action="../funciones/guardar_producto.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre Modelo</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Nike Air Force" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="10">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Foto de la Zapatilla</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Guardar Producto</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">Inventario Actual</div>
                <div class="card-body">
                    <div class="table-responsive"> <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Img</th> <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consulta usando tu conexión $db (MySQLi)
                                $productos = mysqli_query($db, "SELECT * FROM productos ORDER BY id DESC");
                                
                                while($p = mysqli_fetch_assoc($productos)):
                                    // Lógica para mostrar la foto
                                    $ruta_foto = "../fotos/" . $p['imagen'];
                                    
                                    // AQUI EL CAMBIO: Usamos la clase css 'admin-thumb' en lugar de style inline
                                    $foto_html = (file_exists($ruta_foto) && !empty($p['imagen'])) 
                                        ? "<img src='$ruta_foto' class='admin-thumb'>" 
                                        : "<span class='text-muted small'>Sin foto</span>";
                                ?>
                                <tr>
                                    <td class="text-center"><?= $foto_html; ?></td> <td><strong><?= $p['nombre']; ?></strong></td>
                                    <td><?= $p['precio']; ?> €</td>
                                    
                                    <td>
                                        <?php if($p['stock'] < 5): ?>
                                            <span class="badge bg-danger"><?= $p['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $p['stock']; ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="editar_producto.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-warning">
                                            ✏️
                                        </a>
                                        
                                        <a href="../funciones/borrar_producto.php?id=<?= $p['id']; ?>" 
                                           class="btn btn-sm btn-danger">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="my-5"> <h3 class="mb-3">👤 Gestión de Usuarios (Requisito RF01)</h3>
<div class="card shadow-sm mb-5">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th> <th>Nombre</th> <th>Email</th> <th>Rol</th> <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hacemos una nueva consulta para usuarios
                $users = mysqli_query($db, "SELECT * FROM usuarios");
                while($u = mysqli_fetch_assoc($users)):
                ?>
                <tr>
                    <td><?= $u['id']; ?></td>
                    <td><?= $u['nombre']; ?></td>
                    <td><?= $u['email']; ?></td>
                    <td><?= $u['rol']; ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $u['id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                        <?php if($u['id'] != $_SESSION['usuario']['id']): // No borrarse a sí mismo ?>
                            <a href="../funciones/borrar_usuario.php?id=<?= $u['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               >🗑️</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>