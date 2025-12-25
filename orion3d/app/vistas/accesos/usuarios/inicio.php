<div class="accesos-card">
    <div class="accesos-head">
        <div>
            <h2>Usuarios & Roles</h2>
            <p class="muted">Asigna roles (multirol) por usuario.</p>
        </div>

        <form class="accesos-toolbar" method="GET" action="<?= RUTA_URL ?>/accesos/usuarios">
            <input class="accesos-input" type="text" name="q" placeholder="Buscar por nombre o email..." value="<?= htmlspecialchars($q ?? '') ?>">
            <button class="accesos-btn" type="submit">Buscar</button>
        </form>
    </div>

    <div class="accesos-body">
        <div class="tabla">
            <div class="tabla__head">
                <div>Usuario</div>
                <div>Email</div>
                <div>Rol (compat)</div>
                <div>Roles RBAC</div>
                <div></div>
            </div>

            <?php foreach ($usuarios as $u): ?>
                <?php
                $uid = (int)$u['id_usuario'];
                $mapUser = $asignaciones[$uid] ?? [];
                ?>
                <form class="tabla__row" method="POST" action="<?= RUTA_URL ?>/accesos/usuarioRolesGuardar" data-usuario="<?= htmlspecialchars($u['nombre']) ?>">
                    <input type="hidden" name="usuario_id" value="<?= $uid ?>">

                    <div class="strong"><?= htmlspecialchars($u['nombre']) ?></div>
                    <div class="muted"><?= htmlspecialchars($u['email']) ?></div>
                    <div><span class="pill"><?= htmlspecialchars($u['rol']) ?></span></div>

                    <div class="roles-wrap">
                        <?php foreach ($roles as $r): ?>
                            <?php $rid = (int)$r['id']; ?>
                            <?php
                            $soyYo = !empty($_SESSION['id_usuario']) && (int)$_SESSION['id_usuario'] === $uid;
                            $esAdminRow = ($r['nombre'] === 'admin');
                            $deshabilitar = $soyYo && $esAdminRow; // o condicional más complejo
                            ?>
                            <label class="chk-role">
                                <input type="checkbox"
                                    name="roles[]"
                                    value="<?= $rid ?>"
                                    <?= !empty($mapUser[$rid]) ? 'checked' : '' ?>
                                    <?= $deshabilitar ? 'disabled' : '' ?>>
                                <span><?= htmlspecialchars($r['nombre']) ?></span>
                            </label>                            
                        <?php endforeach; ?>
                    </div>

                    <div class="right">
                        <button type="button" class="accesos-btn accesos-btn--primary btnGuardarUsuario">Guardar</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
window.ORION_ACCESOS = {
  page: "usuarios",
  ok: <?= isset($_GET['ok']) ? (int)$_GET['ok'] : 'null' ?>,
  msg: <?= isset($_GET['msg']) ? json_encode($_GET['msg']) : 'null' ?>
};
</script>

<script src="<?= RUTA_URL ?>/public/assets/js/accesos.js?v=<?= time() ?>"></script>
