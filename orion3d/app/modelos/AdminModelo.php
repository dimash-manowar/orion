<?php
class AdminModelo extends Mysql {
    
    public function getVentasMensuales() {
        // Agrupamos los pagos por mes del último año
        $sql = "SELECT MONTHNAME(fecha_pago) as mes, SUM(monto) as total 
                FROM pagos 
                WHERE estado = 'completado' 
                GROUP BY MONTH(fecha_pago) 
                LIMIT 12";
        return $this->select($sql);
    }

    public function getMetricasGenerales() {
        $sql = "SELECT 
                (SELECT COUNT(id_usuario) FROM usuarios WHERE rol = 'usuario') as total_alumnos,
                (SELECT COUNT(id_curso) FROM cursos) as total_cursos,
                (SELECT SUM(monto) FROM pagos WHERE estado = 'completado') as ingresos_totales";
        return $this->select($sql);
    }
}