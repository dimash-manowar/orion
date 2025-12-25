<?php

class AuthModelo extends Mysql
{

    private $tabla = 'usuarios';

    public function __construct()
    {
        parent::__construct();
    }

    // Registrar usuario
    public function registrar($datos): int
    {
        // Modificamos la consulta para incluir foto_perfil
        $sql = "INSERT INTO {$this->tabla} (nombre, email, password, rol, foto_perfil) VALUES (?, ?, ?, ?, ?)";
        $arrData = [
            $datos['nombre'],
            $datos['email'],
            $datos['password'],
            'usuario',
            $datos['ruta_foto'] // <-- NUEVO: La ruta de la imagen
        ];

        return $this->insert($sql, $arrData);
    }

    // Verificar si el email ya existe
    public function obtenerUsuarioPorEmail($email): ?array
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE email = ?";
        $arrData = [$email];

        // Usamos tu método select_one()
        return $this->select_one($sql, $arrData);
    }

    // Método para login (lo usaremos después)
    // Dentro de la clase Usuario    

    public function login($email, $password)
    {
        // La consulta trae todo, incluyendo el campo 'rol' y 'foto_perfil'
        $sql = "SELECT * FROM {$this->tabla} WHERE email = ?";

        // Usamos select_one de tu clase Mysql
        $usuario = $this->select_one($sql, [$email]);

        if ($usuario) {
            // Verificamos si la contraseña coincide
            if (password_verify($password, $usuario['password'])) {
                return $usuario; // Retorna el array con todos los datos
            }
        }

        return false; // Credenciales incorrectas
    }
    public function obtenerUsuarios()
    {
        $sql = "SELECT id_usuario, nombre_usuario, email, rol, foto_perfil FROM usuarios";
        return $this->select($sql, array());
    }
    public function obtenerUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $resultado = $this->select($sql, [$id]);

        // Si el resultado es un array de arrays, devolvemos solo el primer elemento
        return (isset($resultado[0])) ? $resultado[0] : $resultado;
    }
    public function sumarXP($id, $puntos)
    {
        // 1. Sumamos la XP
        $sql = "UPDATE usuarios SET xp = xp + ? WHERE id_usuario = ?";
        $this->update($sql, [$puntos, $id]);

        // 2. Recalculamos el nivel (1 nivel por cada 1000 XP)
        $sqlNivel = "UPDATE usuarios SET nivel_rango = FLOOR(xp / 1000) + 1 WHERE id_usuario = ?";
        $this->update($sqlNivel, [$id]);
    }
}
