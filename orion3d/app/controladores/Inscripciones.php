<?php
class Inscripciones extends Controlador {
    public $inscripcionModelo;
    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) { redireccionar('/login'); }
        $this->inscripcionModelo = $this->modelo('InscripcionModelo');
    }

    public function index() {
        // Solo profes y admins
        if ($_SESSION['rol'] == 'usuario') { redireccionar('/inc/header'); }

        $inscritos = $this->inscripcionModelo->obtenerInscritosPorProfesor($_SESSION['id_usuario']);

        $datos = [
            'titulo' => 'Control de Inscripciones',
            'inscritos' => $inscritos
        ];

        $this->vista('inscripciones/index', $datos);
    }
}