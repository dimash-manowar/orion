<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORION3D | <?= $titulo ?? 'Panel' ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

    <link rel="icon" href="<?php echo RUTA_URL; ?>/public/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/estilos.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/panelUsuario.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/gammificacion.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/mensajeria.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/cursos.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/lecciones.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/inscripciones.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/recurso.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/player.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/tienda.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/pagos.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/perfil.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/estilos_mañana.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/niveles.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/requisitos.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/glosario.css?v=<?= time() ?>" defer>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/accesos.css?v=<?= time() ?>" defer>


</head>

<body class="tema-<?= $_SESSION['rol'] ?>">
    <?php
    require_once RUTA_APP . '/helpers/helpers.php';
    refreshAccesosSiCambiaVersion();
    ?>

    <div class="app-container">

        <!-- SIDEBAR -->
        <?php require RUTA_APP . '/vistas/partials/sidebar.php'; ?>

        <div class="main-wrapper">

            <!-- TOPBAR -->
            <?php require RUTA_APP . '/vistas/partials/topbar.php'; ?>

            <!-- CONTENIDO CENTRAL -->
            <main class="central-content p-4">

                <?php
                if (empty($contenido) || !file_exists($contenido)) {
                    echo "<div class='text-danger'>⚠ No se encontró la vista de contenido.</div>";
                } else {
                    require $contenido;
                }
                ?>
            </main>

        </div>
    </div>    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script src="<?= RUTA_URL ?>/public/assets/js/main.js?v=<?= time() ?>" defer></script>
</body>

</html>