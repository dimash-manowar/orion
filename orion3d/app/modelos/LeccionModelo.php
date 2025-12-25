<?php
class LeccionModelo extends Mysql {
    public function __construct() {
        parent::__construct();
    }

    // Obtener todas las lecciones de un curso específico ordenadas
    public function obtenerLeccionesPorCurso($id_curso) {
        $sql = "SELECT * FROM lecciones WHERE id_curso = ? ORDER BY orden ASC";
        return $this->select($sql, [$id_curso]);
    }

    // Guardar una nueva lección
    public function crearLeccion($datos) {
        $sql = "INSERT INTO lecciones (id_curso, titulo, contenido, video_url, orden) VALUES (?, ?, ?, ?, ?)";
        return $this->insert($sql, $datos);
    }
}