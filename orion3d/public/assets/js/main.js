// public/recursos/js/main.js

document.addEventListener('DOMContentLoaded', function () {
    const formRegistro = document.getElementById('formRegistro');

    if (formRegistro) {
        // Hacemos la función del manejador del evento 'submit' ASÍNCRONA
        formRegistro.addEventListener('submit', async function (e) {

            e.preventDefault(); // Detener el envío normal del formulario

            // 1. Limpiar errores previos
            limpiarErrores();

            // 2. Recolectar datos del formulario (incluye archivos)
            const formData = new FormData(formRegistro);
            // Definimos la RUTA_URL de PHP para usarla en JS si es necesario
            const url = formRegistro.getAttribute('action');

            // 3. Opciones de la petición fetch
            const options = {
                method: 'POST',
                body: formData,
                // Indicamos al servidor que es una petición AJAX (clave para PHP)
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };

            // 4. Realizar la petición
            fetch(url, options)
                .then(response => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        // Si no es JSON (ej. un error de PHP), rechazamos la promesa.
                        return Promise.reject(new Error('Respuesta inesperada del servidor.'));
                    }
                })
                .then(async data => { // <-- Se hace ASÍNCRONA para usar 'await' con Swal
                    if (data.exito) {
                        // 5. Éxito: Mostrar SweetAlert y ESPERAR su finalización (gracias al timer)
                        await Swal.fire({
                            icon: 'success',
                            title: '¡Registro Exitoso!',
                            text: data.mensaje,
                            showConfirmButton: false,
                            timer: 2500
                        });

                        // 6. Redirigir usando la ruta exacta que nos envía el controlador
                        // Ya no usamos replace ni datasets confusos
                        window.location.href = data.redireccionar;

                    } else {
                        // 7. Fracaso: Mostrar errores

                        // Errores de campos específicos (ej: email_error)
                        if (data.errores_campos) {
                            mostrarErrores(data.errores_campos);
                        }

                        // Errores generales (ej: error de base de datos)
                        if (data.errores_generales) {
                            await Swal.fire({ // Usamos await aquí también para no chocar
                                icon: 'error',
                                title: 'Error',
                                text: data.errores_generales[0],
                            });
                        }
                    }
                })
                .catch(error => {
                    // 8. Errores de red, de parseo o errores fatales
                    console.error('Error de Fetch:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'Hubo un problema al intentar conectar con el servidor. Por favor, revise la consola para más detalles.',
                    });
                });
        });
    }
    const formLogin = document.getElementById('formLogin');

    if (formLogin) {
        formLogin.addEventListener('submit', async function (e) {
            e.preventDefault();

            // 1. Limpiar errores previos
            limpiarErrores();

            const formData = new FormData(formLogin);
            const url = formLogin.getAttribute('action');

            const options = {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };

            fetch(url, options)
                .then(response => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        return Promise.reject(new Error('Respuesta inesperada del servidor durante el login.'));
                    }
                })
                .then(async data => {
                    if (data.exito) {
                        // Login exitoso
                        await Swal.fire({
                            icon: 'success',
                            title: '¡Acceso Concedido!',
                            text: data.mensaje,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Redirigir al dashboard (la URL viene del controlador)
                        window.location.href = data.redireccionar;

                    } else {
                        // Error de credenciales o validación
                        if (data.errores_campos) {
                            mostrarErrores(data.errores_campos);
                            // Muestra un SweetAlert para el error de credenciales generales
                            if (data.errores_campos.password && data.errores_campos.password.includes('incorrectos')) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Credenciales Incorrectas',
                                    text: 'Verifique su email y contraseña.',
                                });
                            }
                        }
                        // Error general
                        if (data.errores_generales) {
                            await Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.errores_generales[0],
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error de Fetch en Login:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'No se pudo completar la solicitud de inicio de sesión.',
                    });
                });
        });
    }
    // --- Funciones Auxiliares ---

    // Función para mostrar errores debajo de los campos
    function mostrarErrores(errores) {
        let primerCampoErroneo = null;

        for (const campo in errores) {
            // 1. Encontrar el input por su nombre
            const input = document.querySelector(`input[name="${campo}"]`);
            if (input) {
                // 2. Agregar clase de error al input
                input.classList.add('is-invalid');

                // 3. Crear o encontrar el span de feedback
                let feedbackSpan = input.nextElementSibling;
                if (!feedbackSpan || !feedbackSpan.classList.contains('feedback-error')) {
                    feedbackSpan = document.createElement('span');
                    feedbackSpan.classList.add('feedback-error');
                    input.parentNode.insertBefore(feedbackSpan, input.nextSibling);
                }
                // 4. Poner el mensaje de error
                feedbackSpan.textContent = errores[campo];

                // 5. Guardar el primer campo para enfocarlo
                if (!primerCampoErroneo) {
                    primerCampoErroneo = input;
                }
            }
        }
        // Enfocar el primer campo con error para usabilidad
        if (primerCampoErroneo) {
            primerCampoErroneo.focus();
        }
    }

    // Función para limpiar todos los errores del formulario
    function limpiarErrores() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.feedback-error').forEach(el => {
            el.textContent = ''; // Limpiar el mensaje de error
        });
    }
    document.querySelectorAll('.menu-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.target;
            const el = document.getElementById(id);
            if (!el) return;
            const open = el.style.display === 'block';
            el.style.display = open ? 'none' : 'block';
            btn.classList.toggle('open', !open);
        });
    });

});