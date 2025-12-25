<?php
class Lecciones extends Controlador {
    private $leccionModelo;
    public function __construct() {
        if (!isset($_SESSION['id_usuario'])) { redireccionar('/login'); }
        $this->leccionModelo = $this->modelo('LeccionModelo');
    }

    // Pantalla principal del editor para un curso específico
    public function curso($id_curso) {
        $lecciones = $this->leccionModelo->obtenerLeccionesPorCurso($id_curso);
        
        $datos = [
            'id_curso' => $id_curso,
            'lecciones' => $lecciones,
            'titulo' => 'Editor de Contenido'
        ];

        $this->vista('lecciones/index', $datos);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar CSRF
            if (!validar_csrf_token($_POST['csrf_token'])) { die("Error de seguridad."); }

            // Subir vídeo usando TU helper
            // Usamos 'video' como tipo y 'lecciones_video' como carpeta
            $resVideo = subirArchivo($_FILES['video'], 'video', 'lecciones_video');
            $videoUrl = ($resVideo['exito']) ? $resVideo['ruta'] : '';

            $datos = [
                $_POST['id_curso'],
                trim($_POST['titulo']),
                trim($_POST['contenido']),
                $videoUrl,
                $_POST['orden']
            ];

            if ($this->leccionModelo->crearLeccion($datos)) {
                redireccionar('/lecciones/curso/' . $_POST['id_curso']);
            }
        }
    }
}