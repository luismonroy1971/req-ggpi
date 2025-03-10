<?php
// views/avances/crear.php
include 'views/templates/header.php';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-tasks me-2"></i> Registrar Avance
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <h6>Requerimiento:</h6>
            <div class="card border-light">
                <div class="card-body">
                    <h5 class="card-title"><?= $requerimiento['titulo'] ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Área: <?= $requerimiento['area_nombre'] ?> | 
                        Prioridad: <?= ucfirst($requerimiento['prioridad']) ?> | 
                        Estado: <?= ucfirst($requerimiento['estado']) ?>
                    </h6>
                    <p class="card-text"><?= nl2br($requerimiento['descripcion']) ?></p>
                </div>
            </div>
        </div>
        
        <form action="<?= BASE_URL ?>avances/crear/<?= $requerimiento['id'] ?>" method="post">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción del Avance <span class="text-danger">*</span></label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required
                    placeholder="Detalla el avance realizado o actualización del requerimiento"><?= $_POST['descripcion'] ?? '' ?></textarea>
                <div class="form-text">
                    Escribe de forma clara el progreso, los cambios realizados o cualquier información relevante.
                </div>
            </div>
            
            <div class="mb-4">
                <label for="porcentaje" class="form-label">Porcentaje de avance</label>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <input type="range" class="form-range" id="porcentaje" name="porcentaje" min="0" max="100" step="5" 
                               value="<?= $_POST['porcentaje'] ?? '0' ?>" oninput="porcentajeValue.value = porcentaje.value + '%'">
                    </div>
                    <div class="col-md-4">
                        <output id="porcentajeValue" class="form-control text-center">
                            <?= ($_POST['porcentaje'] ?? '0') ?>%
                        </output>
                    </div>
                </div>
                <div class="form-text">
                    Indica el porcentaje estimado de avance del requerimiento completo.
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= BASE_URL ?>requerimientos/ver/<?= $requerimiento['id'] ?>" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Registrar Avance
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>