<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/requerimientos/index.php
include 'views/templates/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-list-alt me-2"></i> Requerimientos
            <?php if (!isAdmin()): ?>
                <span class="badge bg-light text-dark ms-2">Área: <?= $_SESSION['area_nombre'] ?? '' ?></span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <!-- Filtros -->
        <form action="<?= BASE_URL ?>requerimientos" method="get" class="mb-4">
            <input type="hidden" name="filtrar" value="1">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" <?= isset($_GET['estado']) && $_GET['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en proceso" <?= isset($_GET['estado']) && $_GET['estado'] == 'en proceso' ? 'selected' : '' ?>>En proceso</option>
                        <option value="finalizado" <?= isset($_GET['estado']) && $_GET['estado'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="prioridad" class="form-label">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja" <?= isset($_GET['prioridad']) && $_GET['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
                        <option value="media" <?= isset($_GET['prioridad']) && $_GET['prioridad'] == 'media' ? 'selected' : '' ?>>Media</option>
                        <option value="alta" <?= isset($_GET['prioridad']) && $_GET['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
                        <option value="urgente" <?= isset($_GET['prioridad']) && $_GET['prioridad'] == 'urgente' ? 'selected' : '' ?>>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_desde" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?= $_GET['fecha_desde'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_hasta" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?= $_GET['fecha_hasta'] ?? '' ?>">
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i> Filtrar
                    </button>
                    <a href="<?= BASE_URL ?>requerimientos" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Tabla de requerimientos -->
        <?php if (!empty($requerimientos)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <?php if (isAdmin()): ?>
                                <th>Área</th>
                            <?php endif; ?>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requerimientos as $req): ?>
                            <tr>
                                <td><?= $req['id'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>requerimientos/ver/<?= $req['id'] ?>" class="text-decoration-none fw-bold">
                                        <?= $req['titulo'] ?>
                                    </a>
                                </td>
                                <?php if (isAdmin()): ?>
                                    <td><?= $req['area_nombre'] ?></td>
                                <?php endif; ?>
                                <td>
                                    <span class="badge bg-<?= 
                                        $req['prioridad'] == 'baja' ? 'secondary' : 
                                        ($req['prioridad'] == 'media' ? 'primary' : 
                                        ($req['prioridad'] == 'alta' ? 'danger' : 'dark'))
                                    ?>">
                                        <?= ucfirst($req['prioridad']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $req['estado'] == 'pendiente' ? 'warning' : 
                                        ($req['estado'] == 'en proceso' ? 'info' : 'success')
                                    ?>">
                                        <?= ucfirst($req['estado']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($req['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>requerimientos/ver/<?= $req['id'] ?>" class="btn btn-primary" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (isAdmin() || $req['creado_por'] == $_SESSION['user_id']): ?>
                                            <a href="<?= BASE_URL ?>requerimientos/editar/<?= $req['id'] ?>" class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isAdmin() && $req['estado'] != 'finalizado'): ?>
                                            <a href="<?= BASE_URL ?>avances/crear/<?= $req['id'] ?>" class="btn btn-info" title="Registrar avance">
                                                <i class="fas fa-tasks"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isAdmin()): ?>
                                            <button type="button" class="btn btn-danger" title="Eliminar" 
                                                data-bs-toggle="modal" data-bs-target="#eliminarModal<?= $req['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (isAdmin()): ?>
                                        <!-- Modal de confirmación para eliminar -->
                                        <div class="modal fade" id="eliminarModal<?= $req['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas eliminar el requerimiento "<strong><?= $req['titulo'] ?></strong>"?
                                                        <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="<?= BASE_URL ?>requerimientos/eliminar/<?= $req['id'] ?>" class="btn btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No se encontraron requerimientos.
            </div>
        <?php endif; ?>
        
        <!-- Botón para crear nuevo requerimiento -->
        <div class="mt-3">
            <a href="<?= BASE_URL ?>requerimientos/crear" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i> Nuevo Requerimiento
            </a>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>

