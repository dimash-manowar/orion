<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>403 - No autorizado</title>
  <link rel="stylesheet" href="<?= RUTA_URL ?>/public/assets/css/errores.css?v=<?= time() ?>">
  
</head>
<body>
  <div class="card">
    <span class="badge">Acceso denegado</span>
    <h1>403 — No tienes permisos</h1>
    <p>Tu usuario no está autorizado para ver esta sección.</p>
    <p>Si crees que es un error, pide a un administrador que te asigne el permiso correspondiente.</p>

    <div class="actions">
      <a class="btn primary" href="<?= RUTA_URL ?>/dashboard">Volver al panel</a>
      <a class="btn" href="<?= RUTA_URL ?>/auth/logout">Cerrar sesión</a>
    </div>
  </div>
</body>
</html>
