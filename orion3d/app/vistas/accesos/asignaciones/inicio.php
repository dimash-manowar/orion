<div class="accesos-card">
    <div class="accesos-head">
        <div>
            <h2>Asignaciones</h2>
            <p class="muted">Permisos por rol (RBAC). Marca y guarda.</p>
        </div>

        <div class="accesos-toolbar">
            <input id="filtroPermisos" class="accesos-input" type="text" placeholder="Buscar permiso... (cursos, pagos, accesos)">
            <button class="accesos-btn" type="button" data-action="marcar-todo">Marcar todo</button>
            <button class="accesos-btn" type="button" data-action="desmarcar-todo">Desmarcar todo</button>
        </div>
    </div>

    <div class="accesos-body">
        <div class="row-2">
            <div>
                <label class="label">Rol</label>
                <select id="rolSelect" class="accesos-select">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="right">
                <form id="formGuardarAsignaciones" method="POST" action="<?= RUTA_URL ?>/accesos/asignacionesGuardar">
                    <input type="hidden" name="rol_id" id="rol_id" value="<?= (int)($roles[0]['id'] ?? 0) ?>">
                    <button type="button" id="btnGuardarAsignaciones" class="accesos-btn accesos-btn--primary">Guardar</button>
                </form>
            </div>
        </div>

        <div class="chipbar">
            <button class="chip" type="button" data-template="solo_lectura">Plantilla: Solo lectura</button>
            <button class="chip" type="button" data-template="panel_basico">Plantilla: Panel básico</button>
            <button class="chip" type="button" data-template="profesor_completo">Plantilla: Profesor completo</button>
        </div>


        <hr class="sep">

        <div id="permisosContainer" class="permisos">
            <?php
            $agrupados = [];
            foreach ($permisos as $p) $agrupados[$p['modulo']][] = $p;
            ?>

            <?php foreach ($agrupados as $modulo => $lista): ?>
                <section class="modulo" data-modulo="<?= htmlspecialchars($modulo) ?>">
                    <header class="modulo__header">
                        <div class="modulo__title">
                            <h3><?= htmlspecialchars(strtoupper($modulo)) ?></h3>
                            <span class="badge"><?= count($lista) ?> permisos</span>
                        </div>

                        <div class="modulo__actions">
                            <button class="accesos-btn accesos-btn--mini" type="button" data-action="modulo-marcar">Marcar</button>
                            <button class="accesos-btn accesos-btn--mini" type="button" data-action="modulo-desmarcar">Desmarcar</button>
                        </div>
                    </header>

                    <div class="modulo__body">
                        <?php foreach ($lista as $p): ?>
                            <?php $pid = (int)$p['id']; ?>
                            <label class="permiso-row" data-text="<?= htmlspecialchars($p['modulo'] . ' ' . $p['clave'] . ' ' . $p['descripcion']) ?>">
                                <input class="chkPermiso" type="checkbox" name="permisos[]" value="<?= $pid ?>" form="formGuardarAsignaciones">
                                <div class="permiso-info">
                                    <div class="permiso-clave"><?= htmlspecialchars($p['clave']) ?></div>
                                    <div class="permiso-desc muted"><?= htmlspecialchars($p['descripcion'] ?? '') ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
window.ORION_ACCESOS = {
  page: "asignaciones",
  map: <?= json_encode($map ?? []) ?>,
  ok: <?= isset($_GET['ok']) ? (int)$_GET['ok'] : 'null' ?>,
  msg: <?= isset($_GET['msg']) ? json_encode($_GET['msg']) : 'null' ?>,
  permById: <?= json_encode(array_reduce($permisos ?? [], function($acc, $p){
    $acc[(string)$p['id']] = $p['clave'];
    return $acc;
  }, [])) ?>
};
</script>

<script src="<?= RUTA_URL ?>/public/assets/js/accesos.js?v=<?= time() ?>"></script>