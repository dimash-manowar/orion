<?php


// Redireccionar página
function redireccionar(string $pagina): void
{
    $pagina = '/' . ltrim($pagina, '/');
    header('Location: ' . RUTA_URL . $pagina);
    exit;
}

/**
 * Función para subir un archivo (imagen, video o audio) de forma segura.
 *
 * @param array $archivo El array $_FILES['nombre_campo'].
 * @param string $tipo_archivo 'imagen', 'video' o 'audio'.
 * @param string $nombre_carpeta La carpeta de destino dentro de public/recursos/uploads/
 * @return array Retorna ['exito' => bool, 'mensaje' => string, 'ruta' => string]
 */
function subirArchivo(array $archivo, string $tipo_archivo, string $nombre_carpeta): array
{
    // Directorio base de subidas
    $directorio_base = RUTA_APP . '/public/recursos/uploads/' . $nombre_carpeta . '/';

    // Crear el directorio si no existe
    if (!is_dir($directorio_base)) {
        // 0777 son permisos de escritura, true es recursivo
        mkdir($directorio_base, 0777, true);
    }

    // 1. Verificar si hubo un error en la subida (excepto 4, que es 'no se seleccionó archivo')
    if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return ['exito' => true, 'mensaje' => 'No se seleccionó archivo (opcional).', 'ruta' => ''];
    } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
        return ['exito' => false, 'mensaje' => 'Error al subir el archivo (código: ' . $archivo['error'] . ')', 'ruta' => ''];
    }

    // 2. Definir Tipos y Tamaños Permitidos
    $config = [
        'imagen' => [
            'tipos' => ['image/jpeg', 'image/png', 'image/gif'],
            'max_size' => 5 * 1024 * 1024 // 5MB
        ],
        'video' => [
            'tipos' => ['video/mp4', 'video/webm'],
            'max_size' => 100 * 1024 * 1024 // 100MB
        ],
        'audio' => [
            'tipos' => ['audio/mp3', 'audio/wav'],
            'max_size' => 20 * 1024 * 1024 // 20MB
        ]
    ];

    // 3. Validar
    if (!isset($config[$tipo_archivo])) {
        return ['exito' => false, 'mensaje' => 'Tipo de archivo no soportado por el helper.', 'ruta' => ''];
    }

    $permitidos = $config[$tipo_archivo]['tipos'];
    $max_size   = $config[$tipo_archivo]['max_size'];

    if (!in_array($archivo['type'], $permitidos)) {
        return ['exito' => false, 'mensaje' => "Tipo de archivo no permitido para {$tipo_archivo}.", 'ruta' => ''];
    }

    if ($archivo['size'] > $max_size) {
        return ['exito' => false, 'mensaje' => "El archivo excede el tamaño máximo permitido ({$max_size} bytes).", 'ruta' => ''];
    }

    // 4. Subir y Mover el archivo
    $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_final = uniqid($tipo_archivo . '_') . '.' . $ext;
    $ruta_destino_server = $directorio_base . $nombre_final;

    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino_server)) {
        // Retornar la ruta pública (ej: http://localhost/orion3d/public/recursos/uploads/perfiles/...)
        $ruta_publica = RUTA_URL . '/public/recursos/uploads/' . $nombre_carpeta . '/' . $nombre_final;
        return ['exito' => true, 'mensaje' => 'Archivo subido con éxito.', 'ruta' => $ruta_publica];
    } else {
        return ['exito' => false, 'mensaje' => 'Error desconocido al mover el archivo. Revisar permisos.', 'ruta' => ''];
    }
}

/**
 * Genera un token CSRF único y lo guarda en la sesión
 */
function generar_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Crea el campo oculto HTML para el formulario
 */
function csrf_field()
{
    $token = generar_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Valida si el token enviado es igual al de la sesión
 */
function validar_csrf_token($token_enviado)
{
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token_enviado)) {
        // Opcional: Cambiar el token después de usarlo para mayor seguridad
        // unset($_SESSION['csrf_token']); 
        return true;
    }
    return false;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController
{
    public static function enviarRecibo($emailAlumno, $nombreAlumno, $cursoTitulo, $monto)
    {
        $mail = new PHPMailer(true);
        try {
            // Configuración del Servidor SMTP (Usa Gmail o SendGrid)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tu_correo@gmail.com';
            $mail->Password   = 'tu_password_de_aplicacion';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Destinatarios
            $mail->setFrom('no-reply@orion3d.com', 'ORION3D Academy');
            $mail->addAddress($emailAlumno);

            // Contenido Estilo Orion (HTML)
            $mail->isHTML(true);
            $mail->Subject = '¡Bienvenido a ' . $cursoTitulo . '!';
            $mail->Body    = "
                <div style='background:#0d1117; color:white; padding:40px; font-family:sans-serif;'>
                    <h1 style='color:#00f2ff;'>¡Acceso Concedido, $nombreAlumno!</h1>
                    <p>Tu pago de <strong>{$monto}€</strong> ha sido procesado con éxito.</p>
                    <p>Ya puedes acceder al curso: <strong>$cursoTitulo</strong> desde tu panel.</p>
                    <br>
                    <a href='" . RUTA_URL . "' style='background:#00f2ff; color:#0b0e14; padding:15px 25px; text-decoration:none; border-radius:5px; font-weight:bold;'>Ir al Curso</a>
                </div>";

            $mail->send();
        } catch (Exception $e) { /* Log de error */
        }
    }
}

use Dompdf\Dompdf;
use Dompdf\Options;

/* function descargarFacturaPDF($id_pago)
{
    // 1. SOLUCIÓN AL $this:
    // Instanciamos el modelo directamente. 
    // Asegúrate de que el nombre de la clase sea exacto al de tu archivo.
    $pagoModelo = new PagoModelo();
    $pago = $pagoModelo->obtenerPagoPorId($id_pago);

    if (!$pago) {
        die("Factura no encontrada.");
    }

    // 2. CONFIGURACIÓN DE DOMPDF
    // Es recomendable activar la carga de imágenes remotas por si usas el logo
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // 3. TU HTML (Manteniendo tu estilo)
    $html = "
        <style>
            body { font-family: 'Helvetica', sans-serif; background: #fff; color: #333; }
            .header { border-bottom: 2px solid #00f2ff; padding-bottom: 10px; margin-bottom: 20px; }
            .total { font-size: 20px; font-weight: bold; color: #000; }
            .factura-box { padding: 30px; border: 1px solid #eee; }
        </style>
        <div class='factura-box'>
            <div class='header'>
                <h1>FACTURA ORION3D</h1>
                <p>Nº de Factura: <strong>INV-{$pago->id_pago}</strong></p>
            </div>
            <p><strong>Cliente:</strong> {$pago->nombre_usuario}</p>
            <p><strong>Curso:</strong> {$pago->titulo_curso}</p>
            <p><strong>Fecha:</strong> {$pago->fecha_pago}</p>
            <hr>
            <p class='total'>Total Pagado: {$pago->monto} €</p>
            <p><strong>Método de pago:</strong> " . strtoupper($pago->metodo) . "</p>
        </div>
    ";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // El stream detiene el script y descarga el archivo
    $dompdf->stream("Factura_ORION3D_{$pago->id_pago}.pdf", ["Attachment" => true]);
    exit();
} */
// En tus helpers o configuración
$categorias = [
    'web'     => ['nombre' => 'Desarrollo Web', 'icono' => 'fa-code', 'color' => '#f1c40f'],
    'unity'   => ['nombre' => 'Videojuegos (Unity)', 'icono' => 'fa-gamepad', 'color' => '#ffffff'],
    'blender' => ['nombre' => 'Modelado 3D (Blender)', 'icono' => 'fa-cube', 'color' => '#e67e22']
];
function obtenerNombreRango($nivel)
{
    if ($nivel < 5) return "Aprendiz de Silicio";
    if ($nivel < 10) return "Scripting Junior";
    if ($nivel < 20) return "Modelador de Mallas";
    if ($nivel < 50) return "Arquitecto de Mundos";
    return "Maestro de la Realidad Digital";
}


require_once RUTA_APP . '/Librerias/Mysql.php'; // tu clase Mysql

// ---------- Sesión ----------
function iniciarSesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
// Verifica si hay una sesión activa
function estaLogueado()
{
    iniciarSesion();
    return isset($_SESSION['id_usuario']);
}
function isLogged(): bool
{
    iniciarSesion();
    return !empty($_SESSION['id_usuario']);
}

function refreshAccesosSiCambiaVersion(): void
{
    iniciarSesion();
    if (empty($_SESSION['id_usuario'])) return;

    require_once RUTA_APP . '/modelos/AccesosModel.php';
    $accesos = new AccesosModel();

    $dbVersion = $accesos->obtenerVersionAccesos();
    $sesVersion = (int)($_SESSION['accesos_version'] ?? 0);

    if ($dbVersion > $sesVersion) {
        // recargar roles/permisos
        $uid = (int)$_SESSION['id_usuario'];
        $roles = $accesos->obtenerRolesDeUsuario($uid);
        $perms = $accesos->obtenerPermisosDeUsuario($uid);       

        $_SESSION['roles'] = $roles;
        $_SESSION['rol'] = $roles[0] ?? ($_SESSION['rol'] ?? 'usuario');

        $_SESSION['permisos'] = [];
        foreach ($perms as $p) $_SESSION['permisos'][$p] = true;

        $_SESSION['accesos_version'] = $dbVersion;
    }
}
// ---------- Redirect robusto ----------
function redirect(string $path): void
{
    // admite "auth/login" o "/auth/login"
    $path = '/' . ltrim($path, '/');
    header('Location: ' . RUTA_URL . $path);
    exit;
}

function requireLogin(): void
{
    iniciarSesion();
    if (empty($_SESSION['id_usuario'])) {
        redirect('auth/login'); // ✅ URL absoluta usando RUTA_URL
    }
}

// ---------- RBAC ----------
function can(string $permiso): bool
{
    iniciarSesion();
    refreshAccesosSiCambiaVersion();
    return !empty($_SESSION['permisos'][$permiso]);
}

function requirePermiso(string $permiso): void
{
    iniciarSesion();
    refreshAccesosSiCambiaVersion();

    if (empty($_SESSION['permisos'][$permiso])) {
        http_response_code(403);

        // Vista simple sin layouts que puedan redirigir
        require RUTA_APP . '/vistas/errores/403.php';
        exit;
    }
}




function crearSesionUsuario($usuario): void
{
    iniciarSesion();
    session_regenerate_id(true);


    // 🔥 limpieza dura (evita permisos fantasma)
    unset($_SESSION['roles'], $_SESSION['permisos'], $_SESSION['accesos_version']);

    $u = (array)$usuario;

    $_SESSION['id_usuario']     = $u['id_usuario'] ?? $u['id'] ?? null;
    $_SESSION['nombre_usuario'] = $u['nombre'] ?? null;
    $_SESSION['email_usuario']  = $u['email'] ?? null;
    $_SESSION['rol']            = $u['rol'] ?? 'usuario';
    $_SESSION['foto_perfil']    = $u['foto_perfil'] ?? 'usuario.png';

    $_SESSION['roles'] = [];
    $_SESSION['permisos'] = [];
    $_SESSION['accesos_version'] = 0;

    refreshAccesosSiCambiaVersion();
}


function cerrarSesion(): void
{
    iniciarSesion();
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        // por si quedaron cookies antiguas con otros paths
        setcookie(session_name(), '', time() - 42000, '/orion3d');
        setcookie(session_name(), '', time() - 42000, '/');
    }

    session_destroy();
    redirect('auth/login');
}
