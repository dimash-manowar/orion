<?php
class CertificadoModelo extends Mysql {
    
    public function verificarFinalizacion($id_usuario, $id_curso) {
        // Contamos lecciones totales del curso
        $sqlTotales = "SELECT COUNT(id_leccion) as total FROM lecciones WHERE id_curso = ?";
        $total = $this->select($sqlTotales, [$id_curso]);

        // Contamos lecciones completadas por el alumno
        $sqlCompletas = "SELECT COUNT(id_progreso) as completas 
                         FROM progreso_lecciones 
                         WHERE id_alumno = ? AND id_leccion IN (SELECT id_leccion FROM lecciones WHERE id_curso = ?)";
        $completas = $this->select($sqlCompletas, [$id_usuario, $id_curso]);

        return ($total->total > 0 && $total->total == $completas->completas);
    }
}