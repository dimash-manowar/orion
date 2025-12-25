<?php require RUTA_APP . '/vistas/inc/header.php'; ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white">Mis <span class="text-cyan">Cursos</span></h2>
        <a href="<?php echo RUTA_URL; ?>/cursos/nuevo" class="btn btn-orion-cyan">
            <i class="fas fa-plus"></i> Crear Nuevo Curso
        </a>
    </div>

    <div class="row">
        <?php foreach($datos['cursos'] as $curso): ?>
        <div class="col-md-4 mb-4">
            <div class="card-curso-orion">
                <div class="card-img-wrapper">
                    <img src="<?php echo RUTA_URL; ?>/public/assets/img/cursos/<?php echo $curso->imagen_portada; ?>" class="card-img-top">
                    <div class="badge-precio"><?php echo $curso->precio; ?>€</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-white"><?php echo $curso->titulo; ?></h5>
                    <p class="card-text text-muted"><?php echo substr($curso->descripcion, 0, 80); ?>...</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge-status <?php echo $curso->estado; ?>"><?php echo ucfirst($curso->estado); ?></span>
                        <div class="btn-group">
                            <a href="#" class="btn-icon-edit"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn-icon-delete"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-neon-footer"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require RUTA_APP . '/vistas/inc/footer.php'; ?>