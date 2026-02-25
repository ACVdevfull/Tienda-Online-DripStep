<?php
// views/editar_producto.php
require_once '../config/db.php'; 

// 1. Verificar si nos pasan un ID
if (!isset($_GET['id'])) {
    header("Location: panel_admin.php");
    exit;
}

$id = (int)$_GET['id'];

// 2. Obtener los datos actuales de ese producto
$query = mysqli_query($db, "SELECT * FROM productos WHERE id = $id");
$producto = mysqli_fetch_assoc($query);

// Si no existe el producto (por si ponen un ID inventado en la URL)
if (!$producto) {
    header("Location: panel_admin.php");
    exit;
}

include 'header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">✏️ Editar: <?= $producto['nombre']; ?></h4>
                </div>
                <div class="card-body">
                    
                    <form action="../funciones/actualizar_producto.php" method="POST" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id" value="<?= $producto['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Nombre Modelo</label>
                            <input type="text" name="nombre" class="form-control" 
                                   value="<?= $producto['nombre']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" 
                                   value="<?= $producto['precio']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="<?= $producto['stock']; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"><?= $producto['descripcion']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Actual:</label>
                            <div class="mb-2">
                                <?php if(!empty($producto['imagen'])): ?>
                                    <img src="../fotos/<?= $producto['imagen']; ?>" width="100" class="rounded border">
                                <?php else: ?>
                                    <p class="text-muted">Sin foto</p>
                                <?php endif; ?>
                            </div>
                            
                            <label class="form-label text-primary">¿Cambiar Foto? (Opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <small class="text-muted">Si no seleccionas nada, se mantiene la foto actual.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">💾 Guardar Cambios</button>
                            <a href="panel_admin.php" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
