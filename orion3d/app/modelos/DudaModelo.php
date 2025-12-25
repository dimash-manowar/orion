<?php
class DudaModelo extends Mysql {
    
    public function obtenerDudasPorLeccion($id_leccion) {
        $sql = "SELECT d.*, u.nombre_usuario, u.foto_perfil 
                FROM dudas d
                INNER JOIN usuarios u ON d.id_usuario = u.id_usuario
                WHERE d.id_leccion = ? 
                ORDER BY d.segundo_video ASC";
        return $this->select($sql, [$id_leccion]);
    }

    public function crearDuda($datos) {
        $sql = "INSERT INTO dudas (id_leccion, id_usuario, pregunta, segundo_video) VALUES (?, ?, ?, ?)";
        return $this->insert($sql, $datos);
    }
}