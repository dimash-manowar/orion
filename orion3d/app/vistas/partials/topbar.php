<header class="topbar-orion">
    <div class="topbar-left">
        <button id="btn-hamburguesa"><i class="fas fa-bars"></i></button>
        <h1 class="logo-text">ORION<span>3D</span></h1>
        <span class="zona-indicador ms-3 d-none d-md-inline">| <?php echo $zona_texto ?? ''; ?></span>
    </div>

    <div class="topbar-right">
        <div class="iconos-notificacion">
            <a href="#"><i class="fas fa-bell"></i><span class="punto-alerta"></span></a>
            <a href="#"><i class="fas fa-comment-alt"></i></a>
        </div>

        <div class="perfil-min">
            <div class="user-info-min">
                <span class="user-name d-none d-md-inline"><?php echo $_SESSION['nombre_usuario']; ?></span>
                <img src="<?php echo $_SESSION['foto_perfil']; ?>" alt="Avatar">
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-menu-orion">
                <a href="<?php echo RUTA_URL; ?>/perfil"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                <a href="<?php echo RUTA_URL; ?>/configuracion"><i class="fas fa-cog"></i> Configuración</a>
                <div class="divider-orion"></div>
                <a href="<?php echo RUTA_URL; ?>/auth/logout" class="logout-link"><i class="fas fa-power-off"></i> Cerrar Sesión</a>
            </div>
        </div>
    </div>
</header>