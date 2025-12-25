<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card-orion text-center p-4">
                <div class="avatar-edit mb-3">
                    <img src="<?php echo RUTA_URL; ?>/public/assets/img/perfiles/<?php echo $_SESSION['foto']; ?>" class="img-fluid rounded-circle border-cyan">
                </div>
                <h3 class="text-white"><?php echo $_SESSION['nombre']; ?></h3>
                <p class="text-muted">Alumno en ORION3D</p>
                <hr class="border-secondary">
                <div class="stats-perfil d-flex justify-content-around">
                    <div><h4 class="text-cyan">3</h4><small>Cursos</small></div>
                    <div><h4 class="text-purple">1</h4><small>Certificados</small></div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h4 class="text-white mb-4">Mis <span class="text-cyan">Logros</span></h4>
            
            <div class="row">
                <?php foreach($datos['mis_cursos'] as $item): ?>
                <div class="col-md-6 mb-4">
                    <div class="card-curso-logro">
                        <div class="p-3">
                            <h5 class="text-white"><?php echo $item->titulo; ?></h5>
                            <div class="progress-orion-sm my-3">
                                <div class="fill" style="width: <?php echo $item->progreso; ?>%"></div>
                            </div>
                            
                            <?php if($item->progreso == 100): ?>
                                <a href="<?php echo RUTA_URL; ?>/perfil/certificado/<?php echo $item->id_curso; ?>" class="btn-certificado-link">
                                    <i class="fas fa-award"></i> Descargar Certificado
                                </a>
                            <?php else: ?>
                                <a href="<?php echo RUTA_URL; ?>/player/ver/<?php echo $item->id_curso; ?>" class="text-cyan small">Continuar aprendiendo →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>