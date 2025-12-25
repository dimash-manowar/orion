<?php
class PagoModelo extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Registrar el pago y activar la inscripción en una sola transacción
    public function completarProcesoPago($id_usuario, $id_curso, $monto, $metodo, $referencia)
    {
        // 1. Registrar el pago
        $sqlPago = "INSERT INTO pagos (id_usuario, id_curso, monto, metodo, referencia_pago, estado) 
                    VALUES (?, ?, ?, ?, ?, 'completado')";
        $id_pago = $this->insert($sqlPago, [$id_usuario, $id_curso, $monto, $metodo, $referencia]);

        if ($id_pago > 0) {
            // 2. Crear la inscripción automática
            $sqlInscripcion = "INSERT INTO inscripciones (id_alumno, id_curso, monto_pagado, metodo_pago, estado_pago) 
                               VALUES (?, ?, ?, ?, 'completado')";
            return $this->insert($sqlInscripcion, [$id_usuario, $id_curso, $monto, $metodo]);
        }
        return false;
    }
    public function obtenerPagoPorId($id_pago)
    {
        // Unimos la tabla pagos con usuarios y cursos para tener todos los datos de la factura
        $sql = "SELECT p.*, u.nombre_usuario, u.email, c.titulo as titulo_curso 
            FROM pagos p
            INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
            INNER JOIN cursos c ON p.id_curso = c.id_curso
            WHERE p.id_pago = ?";

        return $this->select($sql, [$id_pago]);
    }
}
