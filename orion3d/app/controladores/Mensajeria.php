<?php
class Mensajeria extends Controlador
{
    private $mensajeModelo;
    public function __construct()
    {
        // Solo usuarios logueados pueden entrar
        if (!isset($_SESSION['id_usuario'])) {
            redireccionar('/login');
        }
        $this->mensajeModelo = $this->modelo('MensajeModelo');
    }

    // En Mensajeria.php
    public function index()
    {
        $conversaciones = $this->mensajeModelo->obtenerConversaciones($_SESSION['id_usuario']);

        // IMPORTANTE: Traer la lista de alumnos/usuarios para el modal de "Nuevo Mensaje"
        $alumnos = $this->mensajeModelo->obtenerUsuarios();

        $datos = [
            'titulo' => 'Mensajería Orion',
            'conversaciones' => $conversaciones,
            'alumnos' => $alumnos // Se lo pasamos a la vista
        ];

        $this->vista('mensajeria/index', $datos);
    }

    public function enviarMensaje()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adjunto = null;

            // Lógica simple para subir el archivo
            if (!empty($_FILES['adjunto']['name'])) {
                $ruta = 'public/assets/uploads/mensajes/';
                $nombreArchivo = time() . '_' . $_FILES['adjunto']['name'];
                if (move_uploaded_file($_FILES['adjunto']['tmp_id'], $ruta . $nombreArchivo)) {
                    $adjunto = $nombreArchivo;
                }
            }

            $datos = [
                $_SESSION['id_usuario'],
                $_POST['id_destinatario'],
                $_POST['contenido'],
                $adjunto
            ];

            if ($this->mensajeModelo->enviar($datos)) {
                // Aquí podrías devolver un JSON para usar AJAX o redireccionar
                redireccionar('/mensajeria');
            }
        }
    }
}
