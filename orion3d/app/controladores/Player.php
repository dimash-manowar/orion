<?php
class Player extends Controlador
{
    private $cursoModelo;
    private $leccionModelo;
    public function __construct()
    {

        if (!isset($_SESSION['id_usuario'])) {
            redireccionar('/login');
        }
        $this->cursoModelo = $this->modelo('CursoModelo');
        $this->leccionModelo = $this->modelo('LeccionModelo');
    }

    public function ver($id_curso, $id_leccion_actual = null)
    {
        // 1. Verificar que el alumno está inscrito (Seguridad)
        // [Aquí iría la lógica de comprobación]

        $curso = $this->cursoModelo->obtenerCursoPorId($id_curso);
        $lecciones = $this->leccionModelo->obtenerLeccionesPorCurso($id_curso);

        // Si no se especifica lección, cargamos la primera
        if (!$id_leccion_actual && !empty($lecciones)) {
            $leccion_activa = $lecciones[0];
        } else {
            // Buscar la lección específica en el array
            $leccion_activa = $this->leccionModelo->obtenerLeccionPorId($id_leccion_actual);
        }

        $datos = [
            'curso' => $curso,
            'lecciones' => $lecciones,
            'leccion_activa' => $leccion_activa,
            'titulo' => $leccion_activa->titulo
        ];

        $this->vista('player/index', $datos);
    }
}
