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

<div class="contenedor-formulario">
    <div class="tarjeta-formulario fondo-nebula">
        <h2 class="titulo-formulario">🌌 Crear Cuenta ORION3D</h2>
        <p class="subtitulo-formulario">Únete a la constelación y empieza a aprender.</p>

        <form id="formRegistro" action="<?php echo RUTA_URL; ?>/auth/registro" method="POST" enctype="multipart/form-data" autocomplete="off">

            <div class="grupo-control">
                <label for="nombre">Nombre Completo</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    class="input-orion <?php echo (!empty($datos['nombre_error'])) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $datos['nombre']; ?>"
                    required>
                <?php if (!empty($datos['nombre_error'])): ?>
                    <span class="feedback-error"><?php echo $datos['nombre_error']; ?></span>
                <?php endif; ?>
            </div>

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

            <div class="grupo-control">
                <label for="confirmar_password">Confirmar Contraseña</label>
                <input
                    type="password"
                    name="confirmar_password"
                    id="confirmar_password"
                    class="input-orion <?php echo (!empty($datos['confirmar_password_error'])) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $datos['confirmar_password']; ?>"
                    required>
                <?php if (!empty($datos['confirmar_password_error'])): ?>
                    <span class="feedback-error"><?php echo $datos['confirmar_password_error']; ?></span>
                <?php endif; ?>
            </div>
            <div class="grupo-control">
                <label for="foto_perfil">Foto de Perfil (Opcional)</label>
                <input
                    type="file"
                    name="foto_perfil"
                    id="foto_perfil"
                    class="input-orion-file"
                    accept="image/*">
                <div class="flex-botones">
                    <button type="submit" class="boton-orion">Registrarse</button>
                    <a href="<?php echo RUTA_URL; ?>/auth/login" class="enlace-login">¿Ya tienes cuenta? Iniciar Sesión</a>
                </div>

        </form>
    </div>
</div>

