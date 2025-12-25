<div class="container-fluid">
    <div class="header-editor d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white">Editor de <span class="text-cyan">Lecciones</span></h2>
            <p class="text-muted small">Organiza los módulos y contenido de tu curso.</p>
        </div>
        <button class="btn btn-orion-cyan" onclick="mostrarFormLeccion()">
            <i class="fas fa-plus"></i> Nueva Lección
        </button>
    </div>

    <div class="lecciones-list">
        <?php if(empty($datos['lecciones'])): ?>
            <div class="empty-state text-center p-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aún no hay lecciones. ¡Empieza a crear contenido!</p>
            </div>
        <?php else: ?>
            <?php foreach($datos['lecciones'] as $leccion): ?>
                <div class="leccion-item-card mb-3">
                    <div class="d-flex align-items-center">
                        <div class="drag-handle me-3"><i class="fas fa-grip-lines"></i></div>
                        <div class="leccion-number me-3"><?php echo $leccion->orden; ?></div>
                        <div class="leccion-info flex-grow-1">
                            <h5 class="m-0 text-white"><?php echo $leccion->titulo; ?></h5>
                            <?php if($leccion->video_url): ?>
                                <small class="text-cyan"><i class="fas fa-video"></i> Vídeo adjunto</small>
                            <?php endif; ?>
                        </div>
                        <div class="leccion-actions">
                            <button class="btn-action-edit"><i class="fas fa-pen"></i></button>
                            <button class="btn-action-delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="modalLeccion" class="modal-orion">
    <div class="modal-content-orion">
        <form action="<?php echo RUTA_URL; ?>/lecciones/guardar" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_curso" value="<?php echo $datos['id_curso']; ?>">
            
            <h4 class="text-white mb-4">Nueva Lección</h4>
            
            <div class="form-group-orion mb-3">
                <label>Título</label>
                <input type="text" name="titulo" class="input-orion" required>
            </div>

            <div class="form-group-orion mb-3">
                <label>Vídeo de la clase</label>
                <input type="file" name="video" class="input-orion" accept="video/mp4,video/webm">
                <small class="text-muted">Máx. 100MB (MP4/WebM)</small>
            </div>

            <div class="form-group-orion mb-3">
                <label>Orden</label>
                <input type="number" name="orden" class="input-orion" value="1">
            </div>

            <div class="form-group-orion mb-4">
                <label>Texto/Descripción</label>
                <textarea name="contenido" class="input-orion" rows="4"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn-cancelar" onclick="cerrarFormLeccion()">Cerrar</button>
                <button type="submit" class="btn-enviar">Guardar Lección</button>
            </div>
        </form>
    </div>
</div>