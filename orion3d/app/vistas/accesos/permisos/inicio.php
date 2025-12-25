<link rel="stylesheet" href="<?= RUTA_URL ?>/public/assets/css/accesos.css?v=<?= time() ?>">

<div class="accesos-card">
  <div class="accesos-head">
    <div>
      <h2>Permisos</h2>
      <p class="muted">Listado de permisos del sistema.</p>
    </div>
  </div>

  <div class="accesos-body">
    <div class="tabla">
      <div class="tabla__head">
        <div>ID</div>
        <div>Módulo</div>
        <div>Clave</div>
        <div>Descripción</div>
      </div>

      <?php foreach($permisos as $p): ?>
        <div class="tabla__row" style="grid-template-columns: .4fr .8fr 1.6fr 1.2fr;">
          <div class="muted"><?= (int)$p['id'] ?></div>
          <div><span class="pill"><?= htmlspecialchars($p['modulo']) ?></span></div>
          <div class="strong"><?= htmlspecialchars($p['clave']) ?></div>
          <div class="muted"><?= htmlspecialchars($p['descripcion'] ?? '') ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script src="<?= RUTA_URL ?>/public/assets/js/accesos.js?v=<?= time() ?>"></script>
