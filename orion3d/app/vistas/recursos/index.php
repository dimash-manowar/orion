<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white">Gestor de <span class="text-cyan">Recursos</span></h2>
            <p class="text-muted small">Sube y organiza material descargable para tus alumnos.</p>
        </div>
        <button class="btn btn-orion-cyan" onclick="abrirModalRecurso()">
            <i class="fas fa-upload"></i> Subir Recurso
        </button>
    </div>

    <div class="row g-3">
        <?php foreach($datos['recursos'] as $recurso): 
            // Lógica para elegir icono
            $icon = 'fa-file-alt';
            if($recurso->tipo_archivo == 'pdf') $icon = 'fa-file-pdf text-danger';
            if($recurso->tipo_archivo == 'modelo') $icon = 'fa-cube text-cyan';
            if($recurso->tipo_archivo == 'zip') $icon = 'fa-file-archive text-warning';
        ?>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="file-card">
                <div class="file-icon">
                    <i class="fas <?php echo $icon; ?> fa-3x"></i>
                </div>
                <div class="file-info text-center">
                    <span class="file-name text-white d-block text-truncate"><?php echo $recurso->nombre_visible; ?></span>
                    <small class="text-muted"><?php echo $recurso->nombre_curso ?: 'General'; ?></small>
                </div>
                <div class="file-actions">
                    <a href="<?php echo RUTA_URL . '/public/recursos/uploads/material/' . $recurso->nombre_archivo; ?>" download class="btn-download"><i class="fas fa-download"></i></a>
                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>