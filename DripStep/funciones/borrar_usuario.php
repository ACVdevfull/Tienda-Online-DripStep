<?php
// funciones/borrar_usuario.php
require_once '../config/db.php';

if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin' && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    // Evitar borrarse a uno mismo
    if($id != $_SESSION['usuario']['id']){
        $db->query("DELETE FROM usuarios WHERE id = $id");
    }
}
header("Location: ../views/panel_admin.php");
?>