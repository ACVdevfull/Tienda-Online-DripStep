<?php
/**
 * CAPA DE PRESENTACIÓN: Página de Inicio
 */
require_once 'config/db.php';
include 'views/header.php'; 

// Consulta para obtener los productos y su STOCK actualizado de la base de datos
$sql = "SELECT * FROM productos";
$resultado = $db->query($sql);
?>

<div class="hero-banner text-dark mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                <h1 class="display-3 fw-bold">
                    Pisa fuerte con <span style="color: rgb(127, 6, 213);">DRIPSTEP</span>
                    
                </h1>

                
                <p class="lead fs-4 fw-light text-muted">
                    Las mejores sneakers exclusivas al mejor precio.
                </p>
                
                <a href="#catalogo" class="btn btn-outline-dark btn-lg mt-3 rounded-pill px-4">Ver Catálogo</a>
            </div>

            <div class="col-md-6 text-center">
                <img src="fotos/LOGO-removebg-preview.png." class="img-fluid" alt="Sneaker DripStep" 
                     style="max-height: 400px; drop-shadow: 0 10px 20px rgba(240, 8, 8, 0.1);">
            </div>
        </div>
    </div>
</div>

<div id="catalogo"></div>

<div class="container mb-5">
    <h2 class="text-center mb-5 fw-bold text-uppercase">Últimos Lanzamientos</h2>
    
    <div class="row">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while($p = $resultado->fetch_assoc()): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        
                        <?php 
                            $ruta_foto = "fotos/" . $p['imagen'];
                            if (!empty($p['imagen']) && file_exists($ruta_foto)) {
                                echo "<img src='$ruta_foto' class='card-img-top' alt='{$p['nombre']}' style='height: 280px; object-fit: cover;'>";
                            } else {
                                echo "<img src='https://via.placeholder.com/300x250?text=Sin+Imagen' class='card-img-top' style='height: 280px; object-fit: cover;'>";
                            }
                        ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-uppercase"><?= $p['nombre']; ?></h5>
                            <p class="card-text text-muted">
                                <?= substr($p['descripcion'] ?? '', 0, 85) . '...'; ?>
                            </p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h4 mb-0 text-primary fw-bold"><?= number_format($p['precio'], 2); ?> €</span>
                                    
                                    <?php if ($p['stock'] > 0): ?>
                                        <span class="badge bg-light text-dark border">Stock: <?= $p['stock']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">¡Agotado!</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($p['stock'] > 0): ?>
                                    <form action="funciones/añadir_carrito.php" method="POST" class="mb-2">
                                        <input type="hidden" name="id" value="<?= $p['id']; ?>">
                                        <input type="hidden" name="nombre" value="<?= $p['nombre']; ?>">
                                        <input type="hidden" name="precio" value="<?= $p['precio']; ?>">
                                        <input type="hidden" name="imagen" value="<?= $p['imagen']; ?>">
                                        
                                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">
                                            Añadir al 🛒
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary w-100 py-2 fw-bold mb-2" disabled>
                                        🚫 Sin Stock
                                    </button>
                                <?php endif; ?>

                                <a href="views/ver_producto.php?id=<?= $p['id']; ?>" class="btn btn-outline-info w-100">
                                    Ver más detalles
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    Actualmente no hay productos disponibles en el catálogo.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="js/main.js"></script>
<?php include 'views/footer.php'; ?>