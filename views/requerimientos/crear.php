<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>
<?php
// views/requerimientos/crear.php
include 'views/templates/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-plus-circle me-2"></i> Nuevo Formato de Solicitud de Desarrollo Digital
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>requerimientos/crear" method="post">
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
                                value="<?= $_POST['nombre_solicitante'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cargo" class="form-label">Cargo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cargo" name="cargo" required 
                                value="<?= $_POST['cargo'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                value="<?= $_POST['email'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono de contacto</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                value="<?= $_POST['telefono'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Área o departamento</label>
                        <input type="text" class="form-control" disabled value="<?= $_SESSION['area_nombre'] ?? '' ?>">
                        <div class="form-text">El requerimiento se registrará para tu área actual.</div>
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
                            placeholder="Escribe un título descriptivo" value="<?= $_POST['titulo'] ?? '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del problema o necesidad <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required
                            placeholder="Incluye una explicación clara de la situación actual y por qué se necesita el desarrollo digital"><?= $_POST['descripcion'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="objetivo" class="form-label">Objetivo del desarrollo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="objetivo" name="objetivo" rows="3" required
                            placeholder="Indica lo que se espera lograr con la solución propuesta"><?= $_POST['objetivo'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resultados" class="form-label">Resultados esperados <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="resultados" name="resultados" rows="3" required
                            placeholder="Detalla los beneficios o mejoras que se esperan alcanzar con el desarrollo"><?= $_POST['resultados'] ?? '' ?></textarea>
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
                        <textarea class="form-control" id="funcionalidades" name="funcionalidades" rows="4" required
                            placeholder="Lista de características esenciales que debe incluir la solución"><?= $_POST['funcionalidades'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tecnologia" class="form-label">Plataforma o tecnología sugerida (si aplica)</label>
                        <textarea class="form-control" id="tecnologia" name="tecnologia" rows="2"
                            placeholder="Especificar si hay preferencia por un sistema existente, herramientas, o integraciones necesarias"><?= $_POST['tecnologia'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="usuarios" class="form-label">Usuarios finales <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="usuarios" name="usuarios" rows="2" required
                            placeholder="Indica quiénes usarán la solución y cuántos usuarios se estima que la utilizarán"><?= $_POST['usuarios'] ?? '' ?></textarea>
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
                        <textarea class="form-control" id="recursos" name="recursos" rows="3"
                            placeholder="¿Existen presupuestos, herramientas, datos o infraestructura ya disponibles?"><?= $_POST['recursos'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="restricciones" class="form-label">Restricciones o limitaciones conocidas</label>
                        <textarea class="form-control" id="restricciones" name="restricciones" rows="3"
                            placeholder="Ejemplo: plazos, compatibilidad, regulaciones, etc."><?= $_POST['restricciones'] ?? '' ?></textarea>
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
                        <textarea class="form-control" id="impacto" name="impacto" rows="3" required
                            placeholder="Describe cómo mejorará o cambiará la operatividad con este desarrollo"><?= $_POST['impacto'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kpi" class="form-label">Indicadores clave de éxito (KPI)</label>
                        <textarea class="form-control" id="kpi" name="kpi" rows="3"
                            placeholder="Métricas para medir el éxito del desarrollo"><?= $_POST['kpi'] ?? '' ?></textarea>
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
                                value="<?= $_POST['fecha_inicio'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_entrega" class="form-label">Fecha deseada para entrega final</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                value="<?= $_POST['fecha_entrega'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="prioridad" class="form-label">Nivel de prioridad <span class="text-danger">*</span></label>
                        <select class="form-select" id="prioridad" name="prioridad" required>
                            <option value="baja" <?= isset($_POST['prioridad']) && $_POST['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
                            <option value="media" <?= !isset($_POST['prioridad']) || $_POST['prioridad'] == 'media' ? 'selected' : '' ?>>Media</option>
                            <option value="alta" <?= isset($_POST['prioridad']) && $_POST['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
                            <option value="urgente" <?= isset($_POST['prioridad']) && $_POST['prioridad'] == 'urgente' ? 'selected' : '' ?>>Urgente</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección 8: Anexos -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">7. Anexos (Opcionales)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="anexos" class="form-label">Descripción de documentación adicional</label>
                        <textarea class="form-control" id="anexos" name="anexos" rows="3"
                            placeholder="Indique brevemente el contenido de los documentos adjuntos"><?= $_POST['anexos'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivos_anexos" class="form-label">Archivos adjuntos</label>
                        <input type="file" class="form-control" id="archivos_anexos" name="archivos_anexos[]" multiple>
                        <div class="form-text">
                            Puedes seleccionar varios archivos. Formatos permitidos: PDF, Word, Excel, imágenes y texto plano. Tamaño máximo: 10MB por archivo.
                        </div>
                    </div>
                    
                    <div id="lista_archivos" class="mt-3">
                        <!-- Aquí se mostrarán los archivos seleccionados mediante JavaScript -->
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= BASE_URL ?>requerimientos" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times-circle me-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>

<!-- JavaScript para mostrar la lista de archivos seleccionados -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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
</script>