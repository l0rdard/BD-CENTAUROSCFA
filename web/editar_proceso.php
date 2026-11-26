<?php
if (
    !isset($_POST['id_jugador']) ||
    empty($_POST['dni']) ||
    empty($_POST['nombre']) ||
    empty($_POST['apellido'])
) {
    header('Location: index.php?mensaje=error');
    exit();
}

include_once 'conexion.php';

$altura = isset($_POST['altura']) && $_POST['altura'] !== '' ? $_POST['altura'] : null;
$peso = isset($_POST['peso']) && $_POST['peso'] !== '' ? $_POST['peso'] : null;
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento'] !== '' ? $_POST['fecha_nacimiento'] : null;
$nro_camiseta = isset($_POST['nro_camiseta']) && $_POST['nro_camiseta'] !== '' ? $_POST['nro_camiseta'] : null;
$telefono = isset($_POST['telefono']) && $_POST['telefono'] !== '' ? $_POST['telefono'] : null;
$id_posicion = isset($_POST['id_posicion']) && $_POST['id_posicion'] !== '' ? $_POST['id_posicion'] : null;

try {
    $sentencia = $bd->prepare("
        UPDATE jugador
        SET dni = ?, nombre = ?, apellido = ?, altura = ?, peso = ?,
            fecha_nacimiento = ?, nro_camiseta = ?, telefono = ?, id_posicion = ?
        WHERE id_jugador = ?
    ");

    $resultado = $sentencia->execute([
        $_POST['dni'],
        $_POST['nombre'],
        $_POST['apellido'],
        $altura,
        $peso,
        $fecha_nacimiento,
        $nro_camiseta,
        $telefono,
        $id_posicion,
        $_POST['id_jugador']
    ]);

    if ($resultado) {
        header('Location: index.php?mensaje=editado');
    } else {
        header('Location: index.php?mensaje=error');
    }
} catch (PDOException $e) {
    header('Location: index.php?mensaje=error');
}
exit();
?>