<?php
class Auth extends Controlador
{
    protected $auth;

    public function __construct()
    {
        $this->auth = $this->modelo('AuthModelo');
    }

    

    // --- NUEVO MÉTODO: Muestra el panel del alumno ---
    public function inicio()
    {
        // 1. Seguridad: Verificar si está logueado
        if (!estaLogueado()) {
            redireccionar('/auth');
        }

        
        // 1. Obtener el ID del usuario de la sesión
        $id_usuario = $_SESSION['id_usuario'];

        // 2. Cargar el modelo de usuario y obtener sus datos de XP y Nivel

        $datosUsuario = $this->auth->obtenerUsuarioPorId($id_usuario);        

        // 3. Pasar los datos a la vista
        $datos = [
            'usuario' => $datosUsuario, // Aquí es donde definimos la variable que falta
            'titulo'  => 'Mi Dashboard'
        ];

        // 2. Cargar la vista según tu estructura: vistas/usuarios/dashboard/inicio.php
        $this->vista('inc/header', $datos);
    }

    public function registro()
    {
        $es_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => htmlspecialchars(trim($_POST['nombre'] ?? '')),
                'email' => htmlspecialchars(trim($_POST['email'] ?? '')),
                'password' => trim($_POST['password'] ?? ''),
                'confirmar_password' => trim($_POST['confirmar_password'] ?? ''),
                'foto_perfil' => $_FILES['foto_perfil'] ?? null,
                'ruta_foto' => '',
                'nombre_error' => '',
                'email_error' => '',
                'password_error' => '',
                'confirmar_password_error' => '',
                'foto_perfil_error' => ''
            ];

            // Validaciones... (Email, Nombre, Password, etc. - Tu código está bien aquí)
            if (empty($datos['email'])) {
                $datos['email_error'] = 'Por favor ingrese un email.';
            } elseif ($this->auth->obtenerUsuarioPorEmail($datos['email'])) {
                $datos['email_error'] = 'Este correo ya está registrado.';
            }

            if (empty($datos['nombre'])) {
                $datos['nombre_error'] = 'Por favor ingrese su nombre.';
            }

            if (empty($datos['password'])) {
                $datos['password_error'] = 'Por favor ingrese una contraseña.';
            } elseif (strlen($datos['password']) < 6) {
                $datos['password_error'] = 'La contraseña debe tener al menos 6 caracteres.';
            }

            if ($datos['password'] != $datos['confirmar_password']) {
                $datos['confirmar_password_error'] = 'Las contraseñas no coinciden.';
            }

            // Subida de foto
            if ($datos['foto_perfil'] && $datos['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
                $resultado_subida = subirArchivo($datos['foto_perfil'], 'imagen', 'perfiles');
                if ($resultado_subida['exito']) {
                    $datos['ruta_foto'] = $resultado_subida['ruta'];
                } else {
                    $datos['foto_perfil_error'] = $resultado_subida['mensaje'];
                }
            }

            $sin_errores = empty($datos['email_error']) && empty($datos['nombre_error']) && empty($datos['password_error']) && empty($datos['confirmar_password_error']) && empty($datos['foto_perfil_error']);

            if ($sin_errores) {
                $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);

                if ($this->auth->registrar($datos)) {
                    if ($es_ajax) {
                        if (ob_get_length()) ob_clean();
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode([
                            'exito' => true,
                            'mensaje' => '¡Registro completado!',
                            'redireccionar' => RUTA_URL . '/auth/login' // URL corregida
                        ]);
                        exit;
                    } else {
                        redireccionar('/auth/login'); // URL corregida
                    }
                } else {
                    if ($es_ajax) {
                        echo json_encode(['exito' => false, 'errores_generales' => ['Error en la base de datos.']]);
                        exit;
                    } else {
                        die('Error en la BD');
                    }
                }
            } else {
                if ($es_ajax) {
                    $errores_campos = [];
                    foreach (['nombre_error', 'email_error', 'password_error', 'confirmar_password_error', 'foto_perfil_error'] as $key_error) {
                        if (!empty($datos[$key_error])) {
                            $campo = str_replace('_error', '', $key_error);
                            $errores_campos[$campo] = $datos[$key_error];
                        }
                    }
                    echo json_encode(['exito' => false, 'errores_campos' => $errores_campos]);
                    exit;
                } else {
                    $this->vista('auth/registro', $datos);
                }
            }
        } else {
            $datos = ['nombre' => '', 'email' => '', 'password' => '', 'confirmar_password' => '', 'nombre_error' => '', 'email_error' => '', 'password_error' => '', 'confirmar_password_error' => '', 'foto_perfil_error' => ''];
            $this->vista('auth/registro', $datos);
        }
    }

    public function login()
    {
         
        $es_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'email' => htmlspecialchars(trim($_POST['email'] ?? '')),
                'password' => trim($_POST['password'] ?? ''),
                'email_error' => '',
                'password_error' => ''
            ];

            if (empty($datos['email'])) $datos['email_error'] = 'Por favor ingrese su email.';
            if (empty($datos['password'])) $datos['password_error'] = 'Por favor ingrese su contraseña.';

            if (empty($datos['email_error']) && empty($datos['password_error'])) {
                $usuario_logueado = $this->auth->login($datos['email'], $datos['password']);

                if ($usuario_logueado) {
                    crearSesionUsuario($usuario_logueado);

                    

                    if ($es_ajax) {
                        if (ob_get_length()) ob_clean();
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode([
                            'exito' => true,
                            'mensaje' => 'Bienvenido a ORION3D.',
                            'redireccionar' => RUTA_URL . '/dashboard' // URL del método que creamos arriba
                        ]);
                        exit;
                    } else {
                        redireccionar('/dashboard');
                    }
                } else {
                    $datos['password_error'] = 'Email o contraseña incorrectos.';
                    if ($es_ajax) {
                        if (ob_get_length()) ob_clean();
                        echo json_encode(['exito' => false, 'errores_campos' => ['password' => $datos['password_error']]]);
                        exit;
                    } else {
                        $this->vista('auth/login', $datos);
                    }
                }
            } else {
                if ($es_ajax) {
                    if (ob_get_length()) ob_clean();
                    echo json_encode(['exito' => false, 'errores_campos' => ['email' => $datos['email_error'], 'password' => $datos['password_error']]]);
                    exit;
                } else {
                    $this->vista('auth/login', $datos);
                }
            }
        } else {
            $datos = ['titulo' => 'Iniciar Sesión', 'email' => '', 'password' => '', 'email_error' => '', 'password_error' => ''];
            $this->vista('auth/login', $datos);
        }
    }

    public function logout()
    {
        cerrarSesion();
        redireccionar('/auth/login'); 
    }
    
}
