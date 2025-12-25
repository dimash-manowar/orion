<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $datos['titulo']; ?></title>
</head>
<body>
    <h1>¡Bienvenido a <?php echo $datos['titulo']; ?>!</h1>
    <p><?php echo $datos['descripcion']; ?></p>

    <h2>Estructura MVC funcionando</h2>
    <p>Si ves este mensaje, la redirección por `.htaccess`, el enrutador `Core.php`, el controlador `Paginas.php` y la vista `inicio.php` están funcionando correctamente.</p>
</body>
</html>