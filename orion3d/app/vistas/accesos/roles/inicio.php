

<div class="accesos-card">
  <div class="accesos-head">
    <div>
      <h2>Roles</h2>
      <p class="muted">Listado de roles disponibles.</p>
    </div>
  </div>

  <div class="accesos-body">
    <div class="tabla">
      <div class="tabla__head">
        <div>ID</div>
        <div>Nombre</div>
        <div>Descripción</div>
      </div>

      <?php foreach($roles as $r): ?>
        <div class="tabla__row">
          <div class="muted"><?= (int)$r['id'] ?></div>
          <div class="strong"><?= htmlspecialchars($r['nombre']) ?></div>
          <div class="muted"><?= htmlspecialchars($r['descripcion'] ?? '') ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script src="<?= RUTA_URL ?>/public/assets/js/accesos.js?v=<?= time() ?>"></script>