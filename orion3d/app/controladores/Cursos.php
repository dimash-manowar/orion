<?php
class Cursos extends Controlador
{
    private $cursoModelo;
    private $auth;
    public function __construct()
    {
        if (!isset($_SESSION['id_usuario'])) {
            redireccionar('/login');
        }
        $this->cursoModelo = $this->modelo('CursoModelo');
        $this->auth = $this->modelo('AuthModelo');
    }

    public function nuevo()
    {
        $datos = ['titulo' => 'Crear Nuevo Curso'];
        $this->vista('cursos/nuevo', $datos);
    }
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Validar CSRF
            if (!validar_csrf_token($_POST['csrf_token'])) {
                die("Error de seguridad.");
            }

            // 2. Usar TU función de subida
            // Pasamos: $_FILES['imagen'], tipo 'imagen', carpeta 'cursos'
            $resultadoSubida = subirArchivo($_FILES['imagen'], 'imagen', 'cursos');

            if ($resultadoSubida['exito']) {
                // Si subió bien o si no se seleccionó archivo (que también pusiste como éxito)
                $imagen = !empty($resultadoSubida['ruta']) ? $resultadoSubida['ruta'] : RUTA_URL . '/public/assets/img/cursos/curso_default.jpg';
            } else {
                // Si hubo un error real (tipo no permitido, tamaño excedido)
                die($resultadoSubida['mensaje']);
            }

            $datos = [
                $_SESSION['id_usuario'],
                trim($_POST['titulo']),
                trim($_POST['descripcion']),
                $_POST['precio'],
                $_POST['categoria'],
                $imagen
            ];

            if ($this->cursoModelo->crearCurso($datos)) {
                redireccionar('/cursos');
            }
        } else {
            $this->vista('cursos/nuevo', ['titulo' => 'Nuevo Curso']);
        }
    }
    public function lista()
    {
        $cursos = $this->cursoModelo->obtenerCursos();

        $datos = [
            'titulo' => 'Gestión de Cursos',
            'cursos' => $cursos
        ];

        $this->vista('cursos/lista', $datos);
    }
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 1. Procesar la imagen primero
            $nombre_imagen = "";
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] == 0) {
                $extension = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
                $nombre_imagen = time() . "_curso." . $extension; // Ej: 173471000_curso.jpg
                $ruta_destino = RUTA_APP . "/assets/img/cursos/" . $nombre_imagen;

                move_uploaded_file($_FILES['portada']['tmp_name'], $ruta_destino);
            }

            // 2. Preparar el array de datos siguiendo el orden de los "?" del modelo:
            // Orden: id_profesor, titulo, descripcion, precio, categoria, imagen_portada
            $parametros = [
                $_SESSION['id_usuario'],          // id_profesor
                trim($_POST['titulo']),           // titulo
                trim($_POST['descripcion']),      // descripcion
                $_POST['precio'],                 // precio
                $_POST['categoria'],              // categoria
                $nombre_imagen                    // imagen_portada
            ];

            // 3. Llamar al modelo
            if ($this->cursoModelo->crearCurso($parametros)) {
                // Si todo sale bien, volvemos a la lista
                redireccionar('cursos/lista?success=1');
            } else {
                die('Error al guardar el curso en la base de datos.');
            }
        }
    }
    public function buscar()
    {
        $termino = isset($_GET['q']) ? $_GET['q'] : '';
        $categoria = isset($_GET['cat']) ? $_GET['cat'] : 'todos';

        // Llamamos al método del modelo que creamos antes
        $resultados = $this->cursoModelo->buscarCursos($termino, $categoria);

        $datos = [
            'titulo' => 'Resultados para: ' . $termino,
            'cursos' => $resultados,
            'busqueda' => $termino
        ];

        $this->vista('cursos/explorar', $datos);
    }
    public function actualizar_campo()
    {
        // Recibimos JSON del fetch de JavaScript
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data) {
            $id = $data['id'];
            $columna = $data['columna']; // Ej: 'titulo' o 'descripcion'
            $valor = $data['valor'];

            // Seguridad: Solo permitir editar columnas específicas
            $columnasPermitidas = ['titulo', 'descripcion', 'precio'];
            if (in_array($columna, $columnasPermitidas)) {
                if ($this->cursoModelo->actualizarDatoCurso($id, $columna, $valor)) {
                    echo json_encode(['success' => true]);
                    return;
                }
            }
        }
        echo json_encode(['success' => false]);
    }
    public function valorar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!validar_csrf_token($_POST['csrf_token'])) {
                die("Error CSRF");
            }

            $datos = [
                'id_curso'   => $_POST['id_curso'],
                'id_usuario' => $_SESSION['id_usuario'],
                'puntuacion' => $_POST['puntuacion'],
                'comentario' => trim($_POST['comentario'])
            ];

            if ($this->cursoModelo->guardarValoracion($datos)) {
                redireccionar('/player/ver/' . $_POST['id_curso']);
            }
        }
    }
    public function completarLeccion($id_leccion)
    {
        header('Content-Type: application/json');
        $id_usuario = $_SESSION['id_usuario'];

        // 1. Guardar en lecciones_vistas
        $exito = $this->cursoModelo->registrarLeccionVista($id_usuario, $id_leccion);

        if ($exito) {
            // 2. Si es la primera vez que la ve, le damos 100 XP
            $this->auth->sumarXP($id_usuario, 100);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Ya habías completado esta lección']);
        }
    }
}
