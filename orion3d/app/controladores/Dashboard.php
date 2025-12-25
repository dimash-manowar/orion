<?php

class Dashboard extends Controlador
{
    private $auth;

    public function __construct()
    {        
        
        if (!isset($_SESSION['id_usuario'])) {
            redireccionar('/auth/login');
            exit;
        }

        $this->auth = $this->modelo('AuthModelo');
    }

    public function inicio()
{
    $usuario = $this->auth->obtenerUsuarioPorId($_SESSION['id_usuario']);

    $datos = [
        'titulo' => 'Dashboard',
        'usuario' => $usuario,
        'zona_texto' => 'Panel de Control', // ahora lo usamos en topbar
        'contenido' => RUTA_APP . '/vistas/dashboard/inicio.php'
    ];

    $this->vista('layouts/panel', $datos);
}

}
