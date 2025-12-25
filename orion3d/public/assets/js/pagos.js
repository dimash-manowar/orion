function iniciarPayPal(monto) {
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{ amount: { value: monto } }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Enviamos a nuestro controlador por AJAX para validar y dar de alta el curso
                procesarPagoServidor(details.id, 'paypal');
            });
        }
    }).render('#paypal-button-container');
}
function procesarPagoPayPal(details, id_curso, monto) {
    // Enviamos los detalles a nuestro controlador PHP
    fetch(RUTA_URL + '/pagos/confirmar_paypal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_transaccion: details.id,
            id_curso: id_curso,
            monto: monto
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // ¡Éxito! Redirigimos al alumno a su nuevo curso con un efecto de carga
            window.location.href = data.url;
        } else {
            alert("Error al activar el curso: " + data.msg);
        }
    });
}