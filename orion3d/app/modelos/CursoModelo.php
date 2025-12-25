<?php
class CursoModelo extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    // Listar cursos creados por el profesor logueado
    public function obtenerCursosPorProfesor($id_profesor)
    {
        $sql = "SELECT * FROM cursos WHERE id_profesor = ? ORDER BY fecha_creacion DESC";
        return $this->select($sql, [$id_profesor]);
    }
    public function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos ORDER BY fecha_creacion DESC";
        return $this->select($sql); // Asumiendo que tu método select() devuelve un array de objetos o filas
    }
    // Crear un nuevo curso
    public function crearCurso($datos)
    {
        // El orden en $sql debe coincidir exactamente con el orden del array que enviamos desde el controlador
        $sql = "INSERT INTO cursos (id_profesor, titulo, descripcion, precio, categoria, imagen_portada) 
            VALUES (?, ?, ?, ?, ?, ?)";

        return $this->insert($sql, $datos);
    }
    public function obtenerExploradorCursos()
    {
        // Traemos el curso y el nombre del profesor que lo dicta
        $sql = "SELECT c.*, u.nombre as profesor 
            FROM cursos c 
            INNER JOIN usuarios u ON c.id_profesor = u.id_usuario 
            WHERE c.estado = 'publicado' 
            ORDER BY c.fecha_creacion DESC";
        return $this->select($sql);
    }
    public function buscarCursos($termino, $categoria = null)
    {
        $condiciones = "WHERE (c.titulo LIKE ? OR c.descripcion LIKE ?)";
        $params = ["%$termino%", "%$termino%"];

        if ($categoria && $categoria != 'todos') {
            $condiciones .= " AND c.categoria = ?";
            $params[] = $categoria;
        }

        $sql = "SELECT c.*, u.nombre_usuario as profesor 
            FROM cursos c 
            INNER JOIN usuarios u ON c.id_profesor = u.id_usuario 
            $condiciones AND c.estado = 'publicado'";

        return $this->select($sql, $params);
    }
    public function filtrarCursosAvanzado($termino, $categoria, $nivel)
    {
        $condiciones = "WHERE c.estado = 'publicado'";
        $params = [];

        // Filtro por texto
        if (!empty($termino)) {
            $condiciones .= " AND (c.titulo LIKE ? OR c.descripcion LIKE ?)";
            $params[] = "%$termino%";
            $params[] = "%$termino%";
        }

        // Filtro por categoría (Web, Unity, Blender)
        if ($categoria && $categoria != 'todos') {
            $condiciones .= " AND c.categoria = ?";
            $params[] = $categoria;
        }

        // Filtro por nivel
        if ($nivel && $nivel != 'todos') {
            $condiciones .= " AND c.nivel = ?";
            $params[] = $nivel;
        }

        $sql = "SELECT c.*, u.nombre_usuario as profesor 
            FROM cursos c 
            INNER JOIN usuarios u ON c.id_profesor = u.id_usuario 
            $condiciones ORDER BY c.id_curso DESC";

        return $this->select($sql, $params);
    }
    // Obtener los nombres de los cursos requeridos
    public function obtenerPrerrequisitos($id_curso)
    {
        $sql = "SELECT c.id_curso, c.titulo 
                FROM prerrequisitos p
                INNER JOIN cursos c ON p.id_curso_previo = c.id_curso
                WHERE p.id_curso = ?";
        return $this->select($sql, [$id_curso]);
    }

    // Verificar si el alumno ha completado el prerrequisito
    public function haCumplidoRequisito($id_usuario, $id_curso_previo)
    {
        $sql = "SELECT progreso FROM inscripciones 
                WHERE id_alumno = ? AND id_curso = ? AND progreso = 100";
        $resultado = $this->select_one($sql, [$id_usuario, $id_curso_previo]);
        return ($resultado) ? true : false;
    }
    function ganarXP($id_usuario, $cantidad)
    {

        // 1. Sumar XP
        $sql = "UPDATE usuarios SET xp = xp + ? WHERE id_usuario = ?";
        $this->select_one($sql, [$cantidad, $id_usuario]);

        // 2. Lógica de subir nivel (Cada nivel pide 1000 XP adicionales)
        // Ejemplo: Nivel 1 (0-999), Nivel 2 (1000-1999)...
        $sqlLevel = "UPDATE usuarios SET nivel_rango = FLOOR(xp / 1000) + 1 WHERE id_usuario = ?";
        $this->update($sqlLevel, [$id_usuario]);
    }
}
