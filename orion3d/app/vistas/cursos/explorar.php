<?php require RUTA_APP . '/vistas/inc/header.php'; ?>
<div class="container-fluid py-5">
    <div class="text-center mb-5">
        <h1 class="text-white display-4 fw-bold">Explora el Universo <span class="text-cyan">3D</span></h1>
        <p class="text-muted">Domina las herramientas del futuro con nuestros instructores expertos.</p>

        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
            <button class="btn-filter active">Todos</button>
            <button class="btn-filter">Modelado</button>
            <button class="btn-filter">Texturizado</button>
            <button class="btn-filter">Animación</button>
            <button class="btn-filter">Impresión 3D</button>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($datos['cursos'] as $curso): ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="orion-card">
                    <div class="card-badge"><?php echo ucfirst($curso->nivel); ?></div>
                    <img src="<?php echo RUTA_URL; ?>/public/assets/img/cursos/<?php echo $curso->portada; ?>" class="card-img-top" alt="...">

                    <div class="card-body">
                        <small class="text-cyan fw-bold text-uppercase"><?php echo $curso->categoria; ?></small>
                        <h5 class="card-title text-white mt-1"><?php echo $curso->titulo; ?></h5>
                        <p class="card-text text-muted small text-truncate-2">
                            <?php echo $curso->descripcion; ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="instructor">
                                <i class="fas fa-user-circle text-muted"></i>
                                <span class="text-white-50 small"><?php echo $curso->profesor; ?></span>
                            </div>
                            <div class="price text-white fw-bold h5 mb-0">
                                $<?php echo $curso->precio; ?>
                            </div>
                        </div>

                        <a href="<?php echo RUTA_URL; ?>/cursos/detalle/<?php echo $curso->id_curso; ?>" class="btn btn-orion-outline-cyan w-100 mt-3">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <aside class="col-lg-3">
            <div class="filter-sidebar p-4 card-orion">
                <h5 class="text-white mb-4"><i class="fas fa-sliders-h text-cyan"></i> Filtros</h5>

                <form action="<?php echo RUTA_URL; ?>/cursos/explorar" method="GET">
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase">Área de estudio</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="cat" value="todos" checked>
                            <label class="text-white-50">Todos los campos</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cat" value="web">
                            <label class="text-white-50">Programación Web</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cat" value="unity">
                            <label class="text-white-50">Unity (C#)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cat" value="blender">
                            <label class="text-white-50">Blender (3D)</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small text-uppercase">Nivel de dificultad</label>
                        <select name="nivel" class="input-orion w-100 mt-2">
                            <option value="todos">Cualquier nivel</option>
                            <option value="basico">🟢 Básico (Iniciación)</option>
                            <option value="intermedio">🟡 Intermedio</option>
                            <option value="avanzado">🔴 Avanzado (Pro)</option>
                        </select>
                    </div>

                    <button type="submit" id="btn_filtros" class="btn btn-orion-cyan w-100 mt-2">Aplicar Filtros</button>
                </form>
            </div>
        </aside>

        <div class="col-lg-9">
            <div class="row g-4" id="contenedor-cursos">
            </div>
        </div>
    </div>
</div>
<?php require RUTA_APP . '/vistas/inc/footer.php'; ?>