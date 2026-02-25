<?php
// views/editar_usuario.php
require_once '../config/db.php';

// 1. Seguridad: Solo admin puede entrar aquí
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: login.php");
    exit;
}

// 2. Comprobar si nos pasan un ID
if(!isset($_GET['id'])){
    header("Location: panel_admin.php");
    exit;
}

$id = (int)$_GET['id'];

// 3. Sacar los datos del usuario de la BD
$sql = "SELECT * FROM usuarios WHERE id = $id";
$resultado = $db->query($sql);
$usuario = $resultado->fetch_assoc();

include 'header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>✏️ Editar Usuario: <?= $usuario['nombre']; ?></h4>
                </div>
                <div class="card-body">
                    <form action="../funciones/actualizar_usuario.php" method="POST">
                        <input type="hidden" name="id" value="<?= $usuario['id']; ?>">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $usuario['email']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Rol (Permisos)</label>
                            <select name="rol" class="form-select">
                                <option value="cliente" <?= $usuario['rol'] == 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                                <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Cambiar Contraseña (Opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="panel_admin.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>