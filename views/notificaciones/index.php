<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/notificaciones/index.php
include 'views/templates/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-bell me-2"></i> Notificaciones
            </h5>
            <?php if (!empty($notificaciones)): ?>
                <a href="<?= BASE_URL ?>notificaciones/marcar-todas-leidas" class="btn btn-light btn-sm">
                    <i class="fas fa-check-double me-2"></i> Marcar todas como leídas
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($notificaciones)): ?>
            <div class="list-group">
                <?php foreach ($notificaciones as $notificacion): ?>
                    <div class="list-group-item list-group-item-action <?= $notificacion['leido'] ? '' : 'bg-light' ?> notificacion-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <div class="d-flex align-items-center">
                                    <?php if (!$notificacion['leido']): ?>
                                        <span class="badge bg-danger me-2">Nuevo</span>
                                    <?php endif; ?>
                                    <?= $notificacion['mensaje'] ?>
                                </div>
                                <?php if ($notificacion['requerimiento_id']): ?>
                                    <div class="mt-1">
                                        <a href="<?= BASE_URL ?>requerimientos/ver/<?= $notificacion['requerimiento_id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver requerimiento
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block"><?= date('d/m/Y H:i', strtotime($notificacion['created_at'])) ?></small>
                                <?php if (!$notificacion['leido']): ?>
                                    <a href="<?= BASE_URL ?>notificaciones/marcar-leida/<?= $notificacion['id'] ?>" class="btn btn-sm btn-outline-secondary mt-1">
                                        <i class="fas fa-check me-1"></i> Marcar como leída
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No tienes notificaciones.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>