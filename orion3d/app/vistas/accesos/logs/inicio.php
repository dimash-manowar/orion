

<div class="accesos-card">
    <div class="accesos-head">
        <div>
            <h2>Auditoría</h2>
            <p class="muted">Registro de cambios de roles/permisos (AJAX).</p>
        </div>

        <div class="accesos-toolbar">
            <input id="logQ" class="accesos-input" type="text" placeholder="Buscar (usuario, acción, id, texto en detalle...)">
            <button class="accesos-btn accesos-btn--primary" id="btnBuscar">Buscar</button>
        </div>
    </div>

    <div class="accesos-body">

        <div class="logs-filtros">
            <div>
                <label class="label">Acción</label>
                <select id="logAccion" class="accesos-select">
                    <option value="">Todas</option>
                    <?php foreach ($acciones as $a): ?>
                        <option value="<?= htmlspecialchars($a) ?>"><?= htmlspecialchars($a) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="label">Actor</label>
                <select id="logActor" class="accesos-select">
                    <option value="0">Todos</option>
                    <?php foreach ($actores as $u): ?>
                        <option value="<?= (int)$u['id_usuario'] ?>">
                            <?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="label">Desde</label>
                <input id="logDesde" class="accesos-input" type="date">
            </div>

            <div>
                <label class="label">Hasta</label>
                <input id="logHasta" class="accesos-input" type="date">
            </div>

            <div class="right">
                <label class="label">Por página</label>
                <select id="logPerPage" class="accesos-select">
                    <option value="10">10</option>
                    <option value="15" selected>15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <hr class="sep">

        <div id="logsMeta" class="muted" style="margin-bottom:10px;"></div>

        <div class="tabla" id="logsTabla">
            <div class="tabla__head logs-head">
                <div>Fecha</div>
                <div>Actor</div>
                <div>Acción</div>
                <div>Objetivo</div>
                <div>IP</div>
                <div></div>
            </div>

            <div id="logsBody"></div>
        </div>

        <div class="logs-paginacion">
            <button class="accesos-btn" id="btnPrev">←</button>
            <div class="muted" id="pageInfo">Página 1</div>
            <button class="accesos-btn" id="btnNext">→</button>
        </div>
    </div>
</div>

<script>
    window.ORION_LOGS = {
        endpoint: "<?= RUTA_URL ?>/accesos/logsAjax"
    };
</script>

<script src="<?= RUTA_URL ?>/public/assets/js/accesos-logs.js?v=<?= time() ?>"></script>