<div class="player-layout">
    <main class="video-content">
        <div class="video-container">
            <video controls crossorigin playsinline poster="<?php echo RUTA_URL; ?>/public/assets/img/cursos/banner_default.jpg">
                <source src="<?php echo $datos['leccion_activa']->video_url; ?>" type="video/mp4">
                Tu navegador no soporta el video.
            </video>
        </div>
        <div class="mt-4 text-center">
            <button id="btn-completar" class="btn btn-orion-glow p-3" onclick="marcarCompletada(<?php echo $datos['leccion']->id_leccion; ?>)">
                <i class="fas fa-check-circle me-2"></i> Marcar lección como terminada
            </button>
        </div>

        <div class="leccion-header mt-4">
            <h1 class="text-white"><?php echo $datos['leccion_activa']->titulo; ?></h1>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="course-meta">
                    <span class="text-muted">Curso:</span> <span class="text-cyan"><?php echo $datos['curso']->titulo; ?></span>
                </div>
                <div class="player-nav">
                    <button class="btn-nav"><i class="fas fa-chevron-left"></i> Anterior</button>
                    <button class="btn-nav active">Siguiente <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <div class="leccion-body mt-4">
            <div class="tabs-leccion">
                <button class="tab-btn active">Descripción</button>
                <button class="tab-btn">Recursos</button>
                <button class="tab-btn">Dudas</button>
            </div>
            <div class="tab-content text-muted p-3">
                <?php echo $datos['leccion_activa']->contenido; ?>
            </div>
        </div>
        <div class="tabs-leccion">
            <button class="tab-btn" onclick="cambiarTab('desc')">Descripción</button>
            <button class="tab-btn" onclick="cambiarTab('glosario')">Diccionario Tech</button>
        </div>

        <div id="tab-glosario" class="tab-pane d-none mt-3">
            <div class="search-glosario mb-3">
                <input type="text" id="filterGlosario" class="input-orion w-100" placeholder="Buscar término (ej: Raycast, Props...)">
            </div>

            <div class="glosario-list">
                <?php foreach ($datos['glosario'] as $t): ?>
                    <div class="termino-item mb-3" data-termino="<?php echo strtolower($t->termino); ?>">
                        <h6 class="text-cyan mb-1"><?php echo $t->termino; ?></h6>
                        <p class="text-muted small"><?php echo $t->definicion; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="tab-dudas" class="tab-pane d-none mt-3">
            <div class="new-duda-form mb-4">
                <h6 class="text-white">¿Tienes un problema en este punto?</h6>
                <textarea id="textoDuda" class="input-orion w-100 mb-2" placeholder="Describe tu error aquí..."></textarea>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Se marcará en el segundo: <span id="currentVideoTime" class="text-cyan">0:00</span></small>
                    <button class="btn btn-orion-cyan btn-sm" onclick="enviarDuda()">Preguntar al Profe</button>
                </div>
            </div>

            <div class="lista-dudas mt-4">
                <?php foreach ($datos['dudas'] as $duda): ?>
                    <div class="duda-item mb-3 <?php echo $duda->estado; ?>">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-dark text-cyan"><?php echo gmdate("i:s", $duda->segundo_video); ?></span>
                            <small class="text-muted"><?php echo $duda->nombre_usuario; ?></small>
                        </div>
                        <p class="text-white small mt-2 mb-1"><?php echo $duda->pregunta; ?></p>

                        <?php if ($duda->respuesta): ?>
                            <div class="respuesta-profe p-2 mt-2">
                                <i class="fas fa-reply fa-rotate-180 me-2 text-purple"></i>
                                <span class="text-white-50 italic"><?php echo $duda->respuesta; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <aside class="video-sidebar">
        <div class="sidebar-header">
            <h5 class="text-white">Contenido del Curso</h5>
            <div class="progress-info">
                <div class="progress-bar-orion">
                    <div class="fill" style="width: 30%"></div>
                </div>
                <small>30% Completado</small>
            </div>
        </div>

        <div class="playlist-items">
            <?php foreach ($datos['lecciones'] as $index => $item): ?>
                <a href="<?php echo RUTA_URL; ?>/player/ver/<?php echo $datos['curso']->id_curso; ?>/<?php echo $item->id_leccion; ?>"
                    class="playlist-item <?php echo ($item->id_leccion == $datos['leccion_activa']->id_leccion) ? 'active' : ''; ?>">
                    <div class="item-status">
                        <?php if ($index == 0): // Ejemplo de completado 
                        ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="far fa-circle"></i>
                        <?php endif; ?>
                    </div>
                    <div class="item-details">
                        <span class="item-title"><?php echo ($index + 1) . ". " . $item->titulo; ?></span>
                        <small class="item-duration"><i class="far fa-clock"></i> 12:40</small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
</div>