<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/requerimientos/ver.php
include 'views/templates/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt me-2"></i> Solicitud de Desarrollo Digital #<?= $requerimiento['id'] ?>
        </h1>
        <div>
            <?php if (isAdmin() || $requerimiento['creado_por'] == $_SESSION['user_id']): ?>
                <a href="<?= BASE_URL ?>requerimientos/editar/<?= $requerimiento['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Editar
                </a>
            <?php endif; ?>
            <?php if (isAdmin()): ?>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal">
                    <i class="fas fa-trash me-2"></i> Eliminar
                </button>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>requerimientos" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información de estado y creación -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Estado del Requerimiento</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card border-left-<?= getEstadoColor($requerimiento['estado']) ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-<?= getEstadoColor($requerimiento['estado']) ?> text-uppercase mb-1">
                                        Estado
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= ucfirst($requerimiento['estado']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-<?= getPrioridadColor($requerimiento['prioridad']) ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-<?= getPrioridadColor($requerimiento['prioridad']) ?> text-uppercase mb-1">
                                        Prioridad
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= ucfirst($requerimiento['prioridad']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-flag fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Área Solicitante
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $requerimiento['area_nombre'] ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                        Fecha de Creación
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= date('d/m/Y H:i', strtotime($requerimiento['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cambiar Estado (sólo para administradores) -->
    <?php if (isAdmin()): ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Cambiar Estado</h5>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>requerimientos/cambiarEstado/<?= $requerimiento['id'] ?>" method="post" class="row g-3 align-items-center">
                <div class="col-auto">
                    <select class="form-select" name="estado" required>
                        <option value="pendiente" <?= $requerimiento['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en proceso" <?= $requerimiento['estado'] == 'en proceso' ? 'selected' : '' ?>>En Proceso</option>
                        <option value="finalizado" <?= $requerimiento['estado'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Actualizar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Contenido del Requerimiento -->
    <div class="row">
        <div class="col-12">
            <!-- Sección 1: Información General del Solicitante -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">1. Información General del Solicitante</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Nombre del solicitante:</h6>
                            <p><?= $requerimiento['nombre_solicitante'] ?? 'No especificado' ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Cargo:</h6>
                            <p><?= $requerimiento['cargo'] ?? 'No especificado' ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Correo electrónico:</h6>
                            <p><?= $requerimiento['email'] ?? 'No especificado' ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Teléfono de contacto:</h6>
                            <p><?= $requerimiento['telefono'] ?? 'No especificado' ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Área o departamento:</h6>
                            <p><?= $requerimiento['area_nombre'] ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold">Creado por:</h6>
                            <p><?= $requerimiento['creado_por_nombre'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Descripción del Proyecto -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">2. Descripción del Proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Nombre del proyecto:</h6>
                        <p class="lead"><?= $requerimiento['titulo'] ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Descripción del problema o necesidad:</h6>
                        <p><?= nl2br($requerimiento['descripcion']) ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Objetivo del desarrollo:</h6>
                        <p><?= nl2br($requerimiento['objetivo'] ?? 'No especificado') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Resultados esperados:</h6>
                        <p><?= nl2br($requerimiento['resultados'] ?? 'No especificado') ?></p>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Especificaciones Iniciales -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">3. Especificaciones Iniciales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Funcionalidades clave requeridas:</h6>
                        <p><?= nl2br($requerimiento['funcionalidades'] ?? 'No especificado') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Plataforma o tecnología sugerida:</h6>
                        <p><?= nl2br($requerimiento['tecnologia'] ?? 'No especificado') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Usuarios finales:</h6>
                        <p><?= nl2br($requerimiento['usuarios'] ?? 'No especificado') ?></p>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Recursos y Restricciones -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">4. Recursos y Restricciones</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Recursos disponibles:</h6>
                        <p><?= nl2br($requerimiento['recursos'] ?? 'No especificado') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Restricciones o limitaciones conocidas:</h6>
                        <p><?= nl2br($requerimiento['restricciones'] ?? 'No especificado') ?></p>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Impacto del Proyecto -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">5. Impacto del Proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Impacto esperado en los procesos actuales:</h6>
                        <p><?= nl2br($requerimiento['impacto'] ?? 'No especificado') ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Indicadores clave de éxito (KPI):</h6>
                        <p><?= nl2br($requerimiento['kpi'] ?? 'No especificado') ?></p>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Cronograma y Priorización -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">6. Cronograma y Priorización</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="font-weight-bold">Fecha deseada para inicio del proyecto:</h6>
                            <p><?= $requerimiento['fecha_inicio'] ? date('d/m/Y', strtotime($requerimiento['fecha_inicio'])) : 'No especificada' ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="font-weight-bold">Fecha deseada para entrega final:</h6>
                            <p><?= $requerimiento['fecha_entrega'] ? date('d/m/Y', strtotime($requerimiento['fecha_entrega'])) : 'No especificada' ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="font-weight-bold">Nivel de prioridad:</h6>
                            <p>
                                <span class="badge bg-<?= getPrioridadColor($requerimiento['prioridad']) ?>">
                                    <?= ucfirst($requerimiento['prioridad']) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 7: Anexos -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">7. Anexos</h5>
                    <?php if (isAdmin() || $requerimiento['creado_por'] == $_SESSION['user_id']): ?>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#subirAnexoModal">
                        <i class="fas fa-upload me-2"></i> Subir Documento
                    </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Descripción de documentación adicional:</h6>
                        <p><?= nl2br($requerimiento['anexos'] ?? 'No se ha proporcionado descripción de documentación.') ?></p>
                    </div>
                    
                    <h6 class="font-weight-bold mb-3">Documentos adjuntos:</h6>
                    <?php if (empty($anexos)): ?>
                        <div class="alert alert-info">
                            No se han adjuntado documentos a este requerimiento.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Archivo</th>
                                        <th>Tamaño</th>
                                        <th>Subido por</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($anexos as $anexo): ?>
                                        <tr>
                                            <td><?= $anexo['titulo'] ?></td>
                                            <td><?= $anexo['nombre_archivo'] ?></td>
                                            <td><?= formatFileSize($anexo['tamanio_archivo']) ?></td>
                                            <td><?= $anexo['usuario_nombre'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($anexo['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>anexos/descargar/<?= $anexo['id'] ?>" class="btn btn-outline-primary">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <?php if (isAdmin() || $requerimiento['creado_por'] == $_SESSION['user_id']): ?>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editarAnexoModal<?= $anexo['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#eliminarAnexoModal<?= $anexo['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal para Editar Anexo -->
                                        <?php if (isAdmin() || $requerimiento['creado_por'] == $_SESSION['user_id']): ?>
                                        <div class="modal fade" id="editarAnexoModal<?= $anexo['id'] ?>" tabindex="-1" aria-labelledby="editarAnexoModalLabel<?= $anexo['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="editarAnexoModalLabel<?= $anexo['id'] ?>">Editar Título del Anexo</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= BASE_URL ?>anexos/editar/<?= $anexo['id'] ?>" method="post">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="titulo<?= $anexo['id'] ?>" class="form-label">Título del documento</label>
                                                                <input type="text" class="form-control" id="titulo<?= $anexo['id'] ?>" name="titulo" value="<?= $anexo['titulo'] ?>" required>
                                                            </div>
                                                            <p class="text-muted">Nombre del archivo: <?= $anexo['nombre_archivo'] ?></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal para Eliminar Anexo -->
                                        <div class="modal fade" id="eliminarAnexoModal<?= $anexo['id'] ?>" tabindex="-1" aria-labelledby="eliminarAnexoModalLabel<?= $anexo['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="eliminarAnexoModalLabel<?= $anexo['id'] ?>">Eliminar Anexo</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de que deseas eliminar este documento?</p>
                                                        <p><strong>Título:</strong> <?= $anexo['titulo'] ?></p>
                                                        <p><strong>Archivo:</strong> <?= $anexo['nombre_archivo'] ?></p>
                                                        <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="<?= BASE_URL ?>anexos/eliminar/<?= $anexo['id'] ?>" class="btn btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal para Subir Anexo -->
            <?php if (isAdmin() || $requerimiento['creado_por'] == $_SESSION['user_id']): ?>
            <div class="modal fade" id="subirAnexoModal" tabindex="-1" aria-labelledby="subirAnexoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="subirAnexoModalLabel">Subir Documento Anexo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= BASE_URL ?>anexos/subir/<?= $requerimiento['id'] ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título del documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required
                                        placeholder="Ingrese un título descriptivo para el documento">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="archivo" class="form-label">Seleccionar archivo <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="archivo" name="archivo" required>
                                    <div class="form-text">
                                        Formatos permitidos: PDF, Word, Excel, imágenes y texto plano. Tamaño máximo: 10MB.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i> Subir Documento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Función auxiliar para formatear el tamaño de archivo
            function formatFileSize($bytes) {
                if ($bytes >= 1073741824) {
                    return number_format($bytes / 1073741824, 2) . ' GB';
                } elseif ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 2) . ' KB';
                } else {
                    return $bytes . ' bytes';
                }
            }
            ?>

            <!-- Sección de Avances -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Avances y Comentarios</h5>
                    <?php if (isAdmin()): ?>
                    <a href="<?= BASE_URL ?>avances/crear/<?= $requerimiento['id'] ?>" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#agregarAvanceModal">
                        <i class="fas fa-plus-circle me-2"></i> Agregar Avance
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($avances)): ?>
                        <div class="alert alert-info">
                            No hay avances registrados para este requerimiento.
                        </div>
                    <?php else: ?>
                        <?php foreach ($avances as $avance): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $avance['nombre_usuario'] ?></strong>
                                            <span class="text-muted ms-2"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($avance['created_at'])) ?></span>
                                        </div>
                                        <div>
                                            <?php if ($avance['porcentaje']): ?>
                                                <span class="badge bg-info me-2"><?= $avance['porcentaje'] ?>% Completado</span>
                                            <?php endif; ?>
                                            
                                            <?php if (isAdmin()): ?>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editarAvanceModal<?= $avance['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#eliminarAvanceModal<?= $avance['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><?= nl2br($avance['descripcion']) ?></p>
                                    <?php if ($avance['porcentaje']): ?>
                                        <div class="progress mt-3">
                                            <div class="progress-bar progress-bar-striped" role="progressbar" 
                                                style="width: <?= $avance['porcentaje'] ?>%;" 
                                                aria-valuenow="<?= $avance['porcentaje'] ?>" aria-valuemin="0" aria-valuemax="100">
                                                <?= $avance['porcentaje'] ?>%
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Modal para Editar Avance -->
                            <?php if (isAdmin()): ?>
                            <div class="modal fade" id="editarAvanceModal<?= $avance['id'] ?>" tabindex="-1" 
                                aria-labelledby="editarAvanceModalLabel<?= $avance['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="editarAvanceModalLabel<?= $avance['id'] ?>">Editar Avance</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="<?= BASE_URL ?>avances/editar/<?= $avance['id'] ?>" method="post">
                                            <div class="modal-body">
                                                <input type="hidden" name="requerimiento_id" value="<?= $requerimiento['id'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label for="descripcion<?= $avance['id'] ?>" class="form-label">Descripción del avance</label>
                                                    <textarea class="form-control" id="descripcion<?= $avance['id'] ?>" name="descripcion" rows="5" required><?= $avance['descripcion'] ?></textarea>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="porcentaje<?= $avance['id'] ?>" class="form-label">Porcentaje de avance</label>
                                                    <input type="range" class="form-range" id="porcentaje<?= $avance['id'] ?>" name="porcentaje" 
                                                        min="0" max="100" step="5" value="<?= $avance['porcentaje'] ?>" 
                                                        oninput="porcentajeValueEdit<?= $avance['id'] ?>.value = porcentaje<?= $avance['id'] ?>.value + '%'">
                                                    <output id="porcentajeValueEdit<?= $avance['id'] ?>"><?= $avance['porcentaje'] ?>%</output>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal para Eliminar Avance -->
                            <div class="modal fade" id="eliminarAvanceModal<?= $avance['id'] ?>" tabindex="-1" 
                                aria-labelledby="eliminarAvanceModalLabel<?= $avance['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="eliminarAvanceModalLabel<?= $avance['id'] ?>">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar este avance? Esta acción no se puede deshacer.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <a href="<?= BASE_URL ?>avances/eliminar/<?= $avance['id'] ?>" class="btn btn-danger">Confirmar Eliminación</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar requerimiento -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta solicitud? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= BASE_URL ?>requerimientos/eliminar/<?= $requerimiento['id'] ?>" class="btn btn-danger">Confirmar Eliminación</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar avance (solo admin) -->
<?php if (isAdmin()): ?>
<div class="modal fade" id="agregarAvanceModal" tabindex="-1" aria-labelledby="agregarAvanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="agregarAvanceModalLabel">Agregar Avance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>avances/crear/<?= $requerimiento['id'] ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del avance</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="porcentaje" class="form-label">Porcentaje de avance</label>
                        <input type="range" class="form-range" id="porcentaje" name="porcentaje" min="0" max="100" step="5" value="0" oninput="porcentajeValue.value = porcentaje.value + '%'">
                        <output id="porcentajeValue">0%</output>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Avance</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
// Función para obtener color según estado
function getEstadoColor($estado) {
    switch ($estado) {
        case 'pendiente':
            return 'warning';
        case 'en proceso':
            return 'info';
        case 'finalizado':
            return 'success';
        default:
            return 'secondary';
    }
}

// Función para obtener color según prioridad
function getPrioridadColor($prioridad) {
    switch ($prioridad) {
        case 'baja':
            return 'success';
        case 'media':
            return 'info';
        case 'alta':
            return 'warning';
        case 'urgente':
            return 'danger';
        default:
            return 'secondary';
    }
}
?>

<?php include 'views/templates/footer.php'; ?>