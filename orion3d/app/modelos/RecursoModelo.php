<?php
class RecursoModelo extends Mysql {
    public function __construct() {
        parent::__construct();
    }

    public function obtenerRecursosPorProfesor($id_profesor) {
        $sql = "SELECT r.*, c.titulo as nombre_curso 
                FROM recursos r 
                LEFT JOIN cursos c ON r.id_curso = c.id_curso 
                WHERE r.id_profesor = ? 
                ORDER BY r.fecha_subida DESC";
        return $this->select($sql, [$id_profesor]);
    }

    public function guardar($datos) {
        $sql = "INSERT INTO recursos (id_profesor, id_curso, nombre_visible, nombre_archivo, tipo_archivo) VALUES (?,?,?,?,?)";
        return $this->insert($sql, $datos);
    }
}