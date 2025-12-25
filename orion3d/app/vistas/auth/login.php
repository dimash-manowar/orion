<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORION3D | Acceso</title>
    <link rel="icon" href="<?php echo RUTA_URL; ?>/public/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
</head>

<body>

    <div class="contenedor-formulario">
        <div class="tarjeta-formulario fondo-nebula">
            <h2 class="titulo-formulario">💫 Acceder a ORION3D</h2>
            <p class="subtitulo-formulario">Introduce tus credenciales para empezar la travesía.</p>

            <form id="formLogin" action="<?php echo RUTA_URL; ?>/auth/login" method="POST"autocomplete="off">

                <div class="grupo-control">
                    <label for="email">Correo Electrónico</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="input-orion <?php echo (!empty($datos['email_error'])) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $datos['email']; ?>"
                        required>
                    <?php if (!empty($datos['email_error'])): ?>
                        <span class="feedback-error"><?php echo $datos['email_error']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="grupo-control">
                    <label for="password">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="input-orion <?php echo (!empty($datos['password_error'])) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $datos['password']; ?>"
                        required>
                    <?php if (!empty($datos['password_error'])): ?>
                        <span class="feedback-error"><?php echo $datos['password_error']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="flex-botones">
                    <button type="submit" class="boton-orion">Iniciar Sesión</button>
                    <a href="<?php echo RUTA_URL; ?>/auth/registro" class="enlace-login">¿No tienes cuenta? Registrarme</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>