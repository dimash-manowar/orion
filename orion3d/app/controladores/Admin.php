<?php
class Admin extends Controlador {
    private $cursoModelo;
    public function __construct() {
        // Protección: Si no es admin, fuera
        if ($_SESSION['rol'] != 'admin') {
            redireccionar('/inicio');
        }
        $this->cursoModelo = $this->modelo('CursoModelo');
    }

    public function index() {
        $this->vista('admin/dashboard');
    }

    public function cursos() {
        $cursos = $this->cursoModelo->obtenerCursosAdmin();
        $datos = ['cursos' => $cursos];
        $this->vista('admin/cursos/index', $datos);
    }

    public function nuevo_curso() {
        $this->vista('admin/cursos/nuevo');
    }
}