<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/requerimientos/editar.php
include 'views/templates/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-edit me-2"></i> Editar Formato de Solicitud #<?= $requerimiento['id'] ?>
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>requerimientos/editar/<?= $requerimiento['id'] ?>" method="post">
            <!-- Sección 1: Información General del Solicitante -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">1. Información General del Solicitante</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_solicitante" class="form-label">Nombre del solicitante <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_solicitante" name="nombre_solicitante" required 
                                value="<?= isset($_POST['nombre_solicitante']) ? $_POST['nombre_solicitante'] : $requerimiento['nombre_solicitante'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cargo" class="form-label">Cargo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cargo" name="cargo" required 
                                value="<?= isset($_POST['cargo']) ? $_POST['cargo'] : $requerimiento['cargo'] ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                value="<?= isset($_POST['email']) ? $_POST['email'] : $requerimiento['email'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono de contacto</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                value="<?= isset($_POST['telefono']) ? $_POST['telefono'] : $requerimiento['telefono'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Descripción del Proyecto -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">2. Descripción del Proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Nombre del proyecto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required 
                            value="<?= isset($_POST['titulo']) ? $_POST['titulo'] : $requerimiento['titulo'] ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del problema o necesidad <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?= isset($_POST['descripcion']) ? $_POST['descripcion'] : $requerimiento['descripcion'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="objetivo" class="form-label">Objetivo del desarrollo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="objetivo" name="objetivo" rows="3" required><?= isset($_POST['objetivo']) ? $_POST['objetivo'] : $requerimiento['objetivo'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resultados" class="form-label">Resultados esperados <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="resultados" name="resultados" rows="3" required><?= isset($_POST['resultados']) ? $_POST['resultados'] : $requerimiento['resultados'] ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Especificaciones Iniciales -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">3. Especificaciones Iniciales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="funcionalidades" class="form-label">Funcionalidades clave requeridas <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="funcionalidades" name="funcionalidades" rows="4" required><?= isset($_POST['funcionalidades']) ? $_POST['funcionalidades'] : $requerimiento['funcionalidades'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tecnologia" class="form-label">Plataforma o tecnología sugerida (si aplica)</label>
                        <textarea class="form-control" id="tecnologia" name="tecnologia" rows="2"><?= isset($_POST['tecnologia']) ? $_POST['tecnologia'] : $requerimiento['tecnologia'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="usuarios" class="form-label">Usuarios finales <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="usuarios" name="usuarios" rows="2" required><?= isset($_POST['usuarios']) ? $_POST['usuarios'] : $requerimiento['usuarios'] ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Recursos y Restricciones -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">4. Recursos y Restricciones</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="recursos" class="form-label">Recursos disponibles</label>
                        <textarea class="form-control" id="recursos" name="recursos" rows="3"><?= isset($_POST['recursos']) ? $_POST['recursos'] : $requerimiento['recursos'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="restricciones" class="form-label">Restricciones o limitaciones conocidas</label>
                        <textarea class="form-control" id="restricciones" name="restricciones" rows="3"><?= isset($_POST['restricciones']) ? $_POST['restricciones'] : $requerimiento['restricciones'] ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Impacto del Proyecto -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">5. Impacto del Proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="impacto" class="form-label">Impacto esperado en los procesos actuales <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="impacto" name="impacto" rows="3" required><?= isset($_POST['impacto']) ? $_POST['impacto'] : $requerimiento['impacto'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kpi" class="form-label">Indicadores clave de éxito (KPI)</label>
                        <textarea class="form-control" id="kpi" name="kpi" rows="3"><?= isset($_POST['kpi']) ? $_POST['kpi'] : $requerimiento['kpi'] ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Cronograma y Priorización -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">6. Cronograma y Priorización</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha deseada para inicio del proyecto</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                value="<?= isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : $requerimiento['fecha_inicio'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_entrega" class="form-label">Fecha deseada para entrega final</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                value="<?= isset($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : $requerimiento['fecha_entrega'] ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="prioridad" class="form-label">Nivel de prioridad <span class="text-danger">*</span></label>
                        <select class="form-select" id="prioridad" name="prioridad" required>
                            <option value="baja" <?= (isset($_POST['prioridad']) ? $_POST['prioridad'] : $requerimiento['prioridad']) == 'baja' ? 'selected' : '' ?>>Baja</option>
                            <option value="media" <?= (isset($_POST['prioridad']) ? $_POST['prioridad'] : $requerimiento['prioridad']) == 'media' ? 'selected' : '' ?>>Media</option>
                            <option value="alta" <?= (isset($_POST['prioridad']) ? $_POST['prioridad'] : $requerimiento['prioridad']) == 'alta' ? 'selected' : '' ?>>Alta</option>
                            <option value="urgente" <?= (isset($_POST['prioridad']) ? $_POST['prioridad'] : $requerimiento['prioridad']) == 'urgente' ? 'selected' : '' ?>>Urgente</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección 8: Anexos -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">7. Anexos</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="anexos" class="form-label">Descripción de documentación adicional</label>
                        <textarea class="form-control" id="anexos" name="anexos" rows="3"
                            placeholder="Indique brevemente el contenido de los documentos adjuntos"><?= isset($_POST['anexos']) ? $_POST['anexos'] : $requerimiento['anexos'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivos_anexos" class="form-label">Añadir nuevos archivos</label>
                        <input type="file" class="form-control" id="archivos_anexos" name="archivos_anexos[]" multiple>
                        <div class="form-text">
                            Puedes seleccionar varios archivos. Formatos permitidos: PDF, Word, Excel, imágenes y texto plano. Tamaño máximo: 10MB por archivo.
                        </div>
                    </div>
                    
                    <div id="lista_archivos" class="mt-3">
                        <!-- Aquí se mostrarán los archivos seleccionados mediante JavaScript -->
                    </div>
                    
                    <!-- Anexos existentes -->
                    <?php if (!empty($anexos)): ?>
                        <h6 class="mt-4 mb-3">Documentos adjuntos existentes:</h6>
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
                                                    <a href="<?= BASE_URL ?>anexos/descargar/<?= $anexo['id'] ?>" class="btn btn-outline-primary" title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Eliminar" 
                                                            onclick="confirmarEliminarAnexo(<?= $anexo['id'] ?>, '<?= htmlspecialchars($anexo['titulo'], ENT_QUOTES) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-4">
                            No hay documentos adjuntos para este requerimiento.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de anexo -->
            <div class="modal fade" id="eliminarAnexoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Eliminar Anexo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar este documento?</p>
                            <p><strong>Título: </strong><span id="anexo-titulo"></span></p>
                            <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="#" id="btn-eliminar-anexo" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= BASE_URL ?>requerimientos/ver/<?= $requerimiento['id'] ?>" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times-circle me-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>

<!-- JavaScript para la gestión de anexos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar archivos seleccionados
    const input = document.getElementById('archivos_anexos');
    const lista = document.getElementById('lista_archivos');
    
    input.addEventListener('change', function() {
        lista.innerHTML = '';
        
        if (this.files.length > 0) {
            const table = document.createElement('table');
            table.className = 'table table-sm table-striped';
            
            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr>
                    <th>Archivo</th>
                    <th>Tipo</th>
                    <th>Tamaño</th>
                    <th>Título para el documento</th>
                </tr>
            `;
            table.appendChild(thead);
            
            const tbody = document.createElement('tbody');
            
            Array.from(this.files).forEach((file, index) => {
                const tr = document.createElement('tr');
                
                // Formatear tamaño
                let fileSize;
                if (file.size > 1024 * 1024) {
                    fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                } else if (file.size > 1024) {
                    fileSize = (file.size / 1024).toFixed(2) + ' KB';
                } else {
                    fileSize = file.size + ' bytes';
                }
                
                tr.innerHTML = `
                    <td>${file.name}</td>
                    <td>${file.type || 'Desconocido'}</td>
                    <td>${fileSize}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm" 
                               name="anexos_titulos[]" placeholder="Título para ${file.name}" 
                               value="${file.name.split('.')[0]}" required>
                    </td>
                `;
                
                tbody.appendChild(tr);
            });
            
            table.appendChild(tbody);
            lista.appendChild(table);
        }
    });
});

// Función para confirmar eliminación de anexo
function confirmarEliminarAnexo(id, titulo) {
    document.getElementById('anexo-titulo').textContent = titulo;
    document.getElementById('btn-eliminar-anexo').href = '<?= BASE_URL ?>anexos/eliminar/' + id;
    
    // Mostrar el modal de confirmación
    var modal = new bootstrap.Modal(document.getElementById('eliminarAnexoModal'));
    modal.show();
}

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
</script>