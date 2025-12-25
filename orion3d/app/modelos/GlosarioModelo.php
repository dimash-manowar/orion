<?php
class GlosarioModelo extends Mysql {
    
    public function obtenerPorCurso($id_curso, $categoria) {
        // Obtenemos términos específicos del curso Y términos generales de esa tecnología
        $sql = "SELECT * FROM glosario 
                WHERE id_curso = ? OR (id_curso IS NULL AND categoria = ?) 
                ORDER BY termino ASC";
        return $this->select($sql, [$id_curso, $categoria]);
    }
}