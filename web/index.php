<?php
include 'template/header.php';
include_once 'conexion.php';

$sentencia = $bd->query("
    SELECT j.*, p.nombre AS posicion
    FROM jugador j
    LEFT JOIN posicion p ON j.id_posicion = p.id_posicion
    ORDER BY j.id_jugador ASC
");
$jugadores = $sentencia->fetchAll(PDO::FETCH_OBJ);

$sentencia_pos = $bd->query("SELECT id_posicion, nombre FROM posicion ORDER BY nombre ASC");
$posiciones = $sentencia_pos->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 mb-4">

            <?php if (isset($_GET['mensaje'])): ?>
                <?php if ($_GET['mensaje'] == 'falta'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> Completá DNI, nombre y apellido.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['mensaje'] == 'registrado'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Listo!</strong> Jugador registrado correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['mensaje'] == 'editado'): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Actualizado:</strong> Los datos del jugador fueron modificados.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['mensaje'] == 'eliminado'): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Eliminado:</strong> El jugador fue borrado del sistema.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['mensaje'] == 'error_fk'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>No se puede eliminar:</strong> Este jugador tiene registros asociados.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['mensaje'] == 'error'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> No se pudo completar la operación.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    Plantel de Jugadores (CentaurosCFA)
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Posición</th>
                                    <th>Peso / Alt.</th>
                                    <th>Teléfono</th>
                                    <th class="text-center" colspan="2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($jugadores)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">No hay jugadores registrados todavía.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($jugadores as $dato): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars((string)($dato->nro_camiseta ?? '-')); ?></td>
                                            <td><?php echo htmlspecialchars((string)$dato->dni); ?></td>
                                            <td><?php echo htmlspecialchars($dato->nombre . ' ' . $dato->apellido); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars((string)($dato->posicion ?? 'Sin asignar')); ?></span></td>
                                            <td>
                                                <?php echo $dato->peso !== null ? htmlspecialchars((string)$dato->peso) . ' kg' : '-'; ?> /
                                                <?php echo $dato->altura !== null ? htmlspecialchars((string)$dato->altura) . ' m' : '-'; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars((string)($dato->telefono ?? '-')); ?></td>
                                            <td class="text-center">
                                                <a class="text-success fs-5" href="editar.php?id=<?php echo $dato->id_jugador; ?>" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="text-danger fs-5" href="eliminar.php?id=<?php echo $dato->id_jugador; ?>"
                                                   onclick="return confirm('¿Seguro que querés eliminar a <?php echo htmlspecialchars($dato->nombre . ' ' . $dato->apellido); ?>?');"
                                                   title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    Registrar Nuevo Jugador
                </div>
                <form class="p-3" method="POST" action="registrar.php">
                    <div class="mb-2">
                        <label class="form-label">DNI:</label>
                        <input type="text" class="form-control" name="dni" maxlength="15" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Apellido:</label>
                            <input type="text" class="form-control" name="apellido" maxlength="50" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Altura (m):</label>
                            <input type="number" step="0.01" class="form-control" name="altura" placeholder="Ej: 1.85">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Peso (kg):</label>
                            <input type="number" step="0.01" class="form-control" name="peso" placeholder="Ej: 92.50">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Fecha Nac.:</label>
                            <input type="date" class="form-control" name="fecha_nacimiento">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Camiseta #:</label>
                            <input type="number" class="form-control" name="nro_camiseta">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" name="telefono" maxlength="14" placeholder="Ej: 351-1234567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Posición:</label>
                        <select class="form-select" name="id_posicion">
                            <option value="">Sin asignar</option>
                            <?php foreach ($posiciones as $pos): ?>
                                <option value="<?php echo $pos->id_posicion; ?>">
                                    <?php echo htmlspecialchars($pos->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrar Jugador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>