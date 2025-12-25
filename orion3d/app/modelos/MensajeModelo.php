<?php
class MensajeModelo extends Mysql {
    
    public function __construct() {
        parent::__construct();
    }

    // Obtiene los hilos de conversación (quién me ha escrito o a quién escribí)
    public function obtenerConversaciones($id_usuario) {
        $sql = "SELECT DISTINCT 
                    u.id_usuario, u.nombre_usuario, u.foto_perfil, u.rol,
                    (SELECT contenido FROM mensajes 
                     WHERE (id_remitente = u.id_usuario AND id_destinatario = ?) 
                        OR (id_remitente = ? AND id_destinatario = u.id_usuario)
                     ORDER BY fecha_envio DESC LIMIT 1) as ultimo_msg,
                    (SELECT leido FROM mensajes 
                     WHERE id_remitente = u.id_usuario AND id_destinatario = ? 
                     ORDER BY fecha_envio DESC LIMIT 1) as estado_leido
                FROM usuarios u
                INNER JOIN mensajes m ON (u.id_usuario = m.id_remitente OR u.id_usuario = m.id_destinatario)
                WHERE u.id_usuario != ? AND (m.id_remitente = ? OR m.id_destinatario = ?)";
        
        return $this->select($sql, [$id_usuario, $id_usuario, $id_usuario, $id_usuario, $id_usuario, $id_usuario]);
    }

    public function enviar($datos) {
        $sql = "INSERT INTO mensajes (id_remitente, id_destinatario, contenido, adjunto) VALUES (?,?,?,?)";
        return $this->insert($sql, $datos);
    }
}