<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?mensaje=error');
    exit();
}

include 'template/header.php';
include_once 'conexion.php';

$id = $_GET['id'];

$sentencia = $bd->prepare("SELECT * FROM jugador WHERE id_jugador = ?");
$sentencia->execute([$id]);
$jugador = $sentencia->fetch(PDO::FETCH_OBJ);

if (!$jugador) {
    header('Location: index.php?mensaje=error');
    exit();
}

$sentencia_pos = $bd->query("SELECT id_posicion, nombre FROM posicion ORDER BY nombre ASC");
$posiciones = $sentencia_pos->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    Editar Datos del Jugador
                </div>
                <form class="p-4" method="POST" action="editar_proceso.php">
                    <input type="hidden" name="id_jugador" value="<?php echo $jugador->id_jugador; ?>">

                    <div class="mb-2">
                        <label class="form-label">DNI:</label>
                        <input type="text" class="form-control" name="dni" maxlength="15" value="<?php echo htmlspecialchars((string)$jugador->dni); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" maxlength="50" value="<?php echo htmlspecialchars((string)$jugador->nombre); ?>" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Apellido:</label>
                            <input type="text" class="form-control" name="apellido" maxlength="50" value="<?php echo htmlspecialchars((string)$jugador->apellido); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Altura (m):</label>
                            <input type="number" step="0.01" class="form-control" name="altura" value="<?php echo htmlspecialchars((string)($jugador->altura ?? '')); ?>">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Peso (kg):</label>
                            <input type="number" step="0.01" class="form-control" name="peso" value="<?php echo htmlspecialchars((string)($jugador->peso ?? '')); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Fecha Nac.:</label>
                            <input type="date" class="form-control" name="fecha_nacimiento" value="<?php echo htmlspecialchars((string)($jugador->fecha_nacimiento ?? '')); ?>">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Camiseta #:</label>
                            <input type="number" class="form-control" name="nro_camiseta" value="<?php echo htmlspecialchars((string)($jugador->nro_camiseta ?? '')); ?>">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" name="telefono" maxlength="14" value="<?php echo htmlspecialchars((string)($jugador->telefono ?? '')); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Posición:</label>
                        <select class="form-select" name="id_posicion">
                            <option value="">Sin asignar</option>
                            <?php foreach ($posiciones as $pos): ?>
                                <option value="<?php echo $pos->id_posicion; ?>"
                                    <?php echo ($pos->id_posicion == $jugador->id_posicion) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pos->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>