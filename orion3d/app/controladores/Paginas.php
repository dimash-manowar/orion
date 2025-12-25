<?php

class Paginas extends Controlador {
    public function __construct() {
        // Cargar modelos o servicios si es necesario
    }

    public function inicio() {
        $datos = [
            'titulo' => 'Mi Plataforma de Cursos - Inicio',
            'descripcion' => 'Aprende Desarrollo Web, Unity y Blender.'
        ];

        // Cargar la vista con los datos
        $this->vista('paginas/inicio', $datos);
    }
}