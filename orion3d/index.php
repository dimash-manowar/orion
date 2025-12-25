<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',          // ✅ CLAVE (no /orion3d)
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Rutas del proyecto
define('RUTA_APP', __DIR__ . '/app');
define('RUTA_PUBLIC', __DIR__ . '/public');
define('RUTA_URL', 'http://localhost/orion3d');

// Autoload Composer (puede estar en raíz o dentro de app/vendor)
$autoloadRoot = __DIR__ . '/vendor/autoload.php';
$autoloadApp  = RUTA_APP . '/vendor/autoload.php';

if (file_exists($autoloadRoot)) {
    require_once $autoloadRoot;
} elseif (file_exists($autoloadApp)) {
    require_once $autoloadApp;
}

// Núcleo MVC
require_once RUTA_APP . '/helpers/helpers.php';
require_once RUTA_APP . '/helpers/menus.php';
require_once RUTA_APP . '/librerias/Base.php';
require_once RUTA_APP . '/librerias/Controlador.php';
require_once RUTA_APP . '/librerias/Core.php';
require_once RUTA_APP . '/librerias/Mysql.php';

// Iniciar Router
$iniciar = new Core();
