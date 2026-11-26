<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?mensaje=error');
    exit();
}

include_once 'conexion.php';

try {
    $sentencia = $bd->prepare("DELETE FROM jugador WHERE id_jugador = ?");
    $resultado = $sentencia->execute([$_GET['id']]);

    if ($resultado) {
        header('Location: index.php?mensaje=eliminado');
    } else {
        header('Location: index.php?mensaje=error');
    }
} catch (PDOException $e) {
    header('Location: index.php?mensaje=error_fk');
}
exit();
?>