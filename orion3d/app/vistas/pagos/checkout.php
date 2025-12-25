<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-pago-orion">
                <h3 class="text-white mb-4">Finalizar <span class="text-cyan">Inscripción</span></h3>
                
                <div class="resumen-curso mb-4">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted"><?php echo $datos['curso']->titulo; ?></span>
                        <span class="text-white fw-bold"><?php echo $datos['curso']->precio; ?>€</span>
                    </div>
                </div>

                <label class="text-muted mb-3 d-block">Selecciona tu método de pago:</label>
                
                <div class="metodos-pago">
                    <div class="metodo-item" onclick="seleccionarMetodo('paypal')">
                        <input type="radio" name="metodo" id="paypal" hidden>
                        <i class="fab fa-paypal"></i> PayPal
                    </div>
                    
                    <div class="metodo-item" onclick="seleccionarMetodo('tarjeta')">
                        <input type="radio" name="metodo" id="tarjeta" hidden>
                        <i class="fas fa-credit-card"></i> Tarjeta Bancaria
                    </div>

                    <div class="metodo-item" onclick="seleccionarMetodo('bizum')">
                        <input type="radio" name="metodo" id="bizum" hidden>
                        <img src="ruta/al/logo-bizum.png" width="60"> Bizum
                    </div>
                </div>

                <div id="paypal-button-container" class="mt-4" style="display:none;"></div>

                <button id="btn-pagar-redsys" class="btn btn-orion-cyan w-100 mt-4" style="display:none;">
                    Pagar ahora con Redsys
                </button>
            </div>
        </div>
    </div>
</div>