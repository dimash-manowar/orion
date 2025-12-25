<?php
class InscripcionModelo extends Mysql {
    public function __construct() {
        parent::__construct();
    }

    // Obtener alumnos inscritos en los cursos del profesor logueado
    public function obtenerInscritosPorProfesor($id_profesor) {
        $sql = "SELECT i.*, u.nombre_usuario, u.email, u.foto_perfil, c.titulo as nombre_curso
                FROM inscripciones i
                INNER JOIN usuarios u ON i.id_alumno = u.id_usuario
                INNER JOIN cursos c ON i.id_curso = c.id_curso
                WHERE c.id_profesor = ?
                ORDER BY i.fecha_inscripcion DESC";
        
        return $this->select($sql, [$id_profesor]);
    }
}