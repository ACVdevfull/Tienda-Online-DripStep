<?php 
require_once '../config/db.php'; // Conectamos a la BD
include 'header.php';           // Cargamos el menú
  ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($_SESSION['error_login'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error_login']; ?>
                    </div>
                    <?php unset($_SESSION['error_login']); // Borrar el error tras mostrarlo ?>
                <?php endif; ?>

                <form action="../funciones/login_usuario.php" method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>