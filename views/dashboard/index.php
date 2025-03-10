<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/dashboard/index.php
include 'views/templates/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i> 
                    Bienvenido/a, <?= $_SESSION['nombre'] ?>
                </h2>
                <p class="text-muted">
                    <?= isAdmin() ? 'Panel de administración' : 'Panel de usuario' ?> | 
                    Área: <?= $_SESSION['area_nombre'] ?? $_SESSION['area_id'] ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i> Estadísticas de Requerimientos
                </h5>
            </div>
            <div class="card-body">
                <canvas id="estadisticasChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i> Requerimientos Recientes
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($requerimientosRecientes)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Área</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requerimientosRecientes as $req): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL ?>requerimientos/ver/<?= $req['id'] ?>" class="text-decoration-none">
                                                <?= $req['titulo'] ?>
                                            </a>
                                        </td>
                                        <td><?= $req['area_nombre'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $req['estado'] == 'pendiente' ? 'warning' : ($req['estado'] == 'en proceso' ? 'info' : 'success') ?>">
                                                <?= ucfirst($req['estado']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($req['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay requerimientos recientes.
                    </div>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>requerimientos" class="btn btn-outline-primary">
                        <i class="fas fa-list-alt me-2"></i> Ver todos los requerimientos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bell me-2"></i> Notificaciones Recientes
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($notificaciones)): ?>
                    <ul class="list-group">
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <li class="list-group-item notificacion-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <?= $notificacion['mensaje'] ?>
                                        <small class="text-muted d-block">
                                            <?= date('d/m/Y H:i', strtotime($notificacion['created_at'])) ?>
                                        </small>
                                    </div>
                                    <a href="<?= BASE_URL ?>notificaciones/marcar-leida/<?= $notificacion['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-check"></i>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>notificaciones/marcar-todas-leidas" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i> Marcar todas como leídas
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No tienes notificaciones pendientes.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i> Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>requerimientos/crear" class="btn btn-outline-primary w-100 p-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div>Nuevo Requerimiento</div>
                        </a>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>requerimientos" class="btn btn-outline-info w-100 p-3">
                            <i class="fas fa-list-alt fa-2x mb-2"></i>
                            <div>Ver Requerimientos</div>
                        </a>
                    </div>
                    
                    <?php if (isAdmin()): ?>
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>requerimientos?filtrar&estado=pendiente" class="btn btn-outline-warning w-100 p-3">
                            <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                            <div>Pendientes</div>
                        </a>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>requerimientos?filtrar&estado=en proceso" class="btn btn-outline-info w-100 p-3">
                            <i class="fas fa-spinner fa-2x mb-2"></i>
                            <div>En Proceso</div>
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>requerimientos?filtrar&prioridad=alta" class="btn btn-outline-danger w-100 p-3">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <div>Alta Prioridad</div>
                        </a>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="<?= BASE_URL ?>notificaciones" class="btn btn-outline-secondary w-100 p-3">
                            <i class="fas fa-bell fa-2x mb-2"></i>
                            <div>Notificaciones</div>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Crear gráfico de estadísticas
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('estadisticasChart').getContext('2d');
    
    const estadisticasChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'En Proceso', 'Finalizados'],
            datasets: [{
                data: [
                    <?= $estadisticas['pendiente'] ?>,
                    <?= $estadisticas['en proceso'] ?>,
                    <?= $estadisticas['finalizado'] ?>
                ],
                backgroundColor: [
                    '#ffc107', // Amarillo para pendientes
                    '#17a2b8', // Azul para en proceso
                    '#28a745'  // Verde para finalizados
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php include 'views/templates/footer.php'; ?>