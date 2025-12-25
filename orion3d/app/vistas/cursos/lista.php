<?php require RUTA_APP . '/vistas/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white"><i class="fas fa-layer-group text-cyan me-2"></i> Mis Cursos</h2>
        <a href="<?php echo RUTA_URL; ?>/cursos/nuevo" class="btn btn-info fw-bold">
            <i class="fas fa-plus me-1"></i> NUEVO CURSO
        </a>
    </div>

    <div class="card bg-dark border-0 shadow-lg" style="background: #161b22; border-radius: 15px;">
        <div class="table-responsive p-3">
            <table class="table table-hover table-dark align-middle mb-0">
                <thead>
                    <tr class="text-cyan border-secondary">
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['cursos'] as $curso): ?>
                        <tr class="border-secondary">
                            <td style="width: 100px;">
                                <img src="<?php echo RUTA_URL; ?>/public/assets/img/cursos/<?php echo $curso->imagen_portada; ?>"
                                    class="img-fluid rounded border border-secondary"
                                    alt="Portada" style="max-height: 60px; width: 100%; object-fit: cover;">
                            </td>
                            <td>
                                <span class="fw-bold d-block text-white"><?php echo $curso->titulo; ?></span>
                                <small class="text-muted">ID: #<?php echo $curso->id_curso; ?></small>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-outline-info border border-info text-cyan px-3">
                                    <?php echo strtoupper($curso->categoria); ?>
                                </span>
                            </td>
                            <td class="text-success fw-bold">
                                <?php echo $curso->precio > 0 ? $curso->precio . '€' : 'GRATIS'; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="<?php echo RUTA_URL; ?>/cursos/editar/<?php echo $curso->id_curso; ?>"
                                        class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo RUTA_URL; ?>/cursos/lecciones/<?php echo $curso->id_curso; ?>"
                                        class="btn btn-sm btn-outline-primary" title="Gestionar Lecciones">
                                        <i class="fas fa-book"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn_eliminar btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require RUTA_APP . '/vistas/inc/footer.php'; ?>