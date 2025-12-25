<?php require RUTA_APP . '/vistas/inc/header.php'; ?>
<div class="reviews-section mt-5">
    <h3 class="text-white">Opiniones de los <span class="text-cyan">Alumnos</span></h3>

    <div class="row">
        <?php foreach ($datos['valoraciones'] as $v): ?>
            <div class="col-md-6 mb-3">
                <div class="review-card">
                    <div class="d-flex mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo ($i <= $v->puntuacion) ? 'text-warning' : 'text-muted'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-white-50 italic">"<?php echo $v->comentario; ?>"</p>
                    <small class="text-cyan">- <?php echo $v->nombre_usuario; ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="requisitos-box p-4 mt-4">
        <h5 class="text-white"><i class="fas fa-project-diagram text-cyan me-2"></i> Ruta de Aprendizaje</h5>
        <p class="text-muted small">Para aprovechar al máximo este curso, te recomendamos tener conocimientos en:</p>

        <ul class="list-unstyled">
            <?php foreach ($datos['prerrequisitos'] as $req): ?>
                <li class="d-flex align-items-center mb-2">
                    <?php
                    $completado = $this->cursoModelo->haCumplidoRequisito($_SESSION['id_usuario'], $req->id_curso);
                    ?>
                    <i class="fas <?php echo $completado ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning'; ?> me-2"></i>
                    <a href="<?php echo RUTA_URL; ?>/cursos/detalle/<?php echo $req->id_curso; ?>" class="text-white-50 decoration-none">
                        <?php echo $req->titulo; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!$datos['requisitos_cumplidos']): ?>
            <div class="alert alert-orion-warning mt-3">
                <small><i class="fas fa-lock me-1"></i> Aún no dominas las bases para este nivel avanzado.</small>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require RUTA_APP . '/vistas/inc/footer.php'; ?>