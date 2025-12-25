<?php
class Pagos extends Controlador
{
    private $pagoModelo;
    private $cursoModelo;
    public function __construct()
    {
        $this->pagoModelo = $this->modelo('PagoModelo');
        $this->cursoModelo = $this->modelo('CursoModelo');
    }

    // Método para confirmar pagos de PayPal vía AJAX
    public function confirmar_paypal()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recibimos los datos del JS de PayPal
            $json = file_get_contents('php://input');
            $datos_paypal = json_decode($json, true);

            $id_transaccion = $datos_paypal['id_transaccion'];
            $id_curso = $datos_paypal['id_curso'];
            $monto = $datos_paypal['monto'];
            $id_usuario = $_SESSION['id_usuario'];

            // Verificamos y guardamos
            $resultado = $this->pagoModelo->completarProcesoPago(
                $id_usuario,
                $id_curso,
                $monto,
                'paypal',
                $id_transaccion
            );

            if ($resultado) {
                echo json_encode(['status' => 'success', 'url' => RUTA_URL . '/player/curso/' . $id_curso]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'No se pudo procesar la inscripción.']);
            }
        }
    }

    // Ejemplo para Bizum/Tarjeta (Notificación Online de Redsys)
    public function callback_redsys()
    {
        // Redsys envía los datos por POST de forma asíncrona (Server-to-Server)
        if ($_POST) {
            // Aquí se usaría la librería oficial de Redsys para decodificar el 'Ds_MerchantParameters'
            // Si la firma es válida y el código de respuesta es 0000 a 0099 (Pago OK):

            // $this->pagoModelo->completarProcesoPago(...);
        }
    }
    public function factura($id_pago)
    {
        // Simplemente llamamos a la función del helper
        descargarFacturaPDF($id_pago);
    }
}
