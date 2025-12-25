<div class="certificado-container">
    <div class="certificado-border">
        <div class="certificado-content text-center">
            <img src="logo-orion.png" width="150" class="mb-4">
            <h4 class="text-uppercase text-muted">Certificado de Finalización</h4>
            <p class="mt-5">Se otorga a:</p>
            <h1 class="alumno-nombre text-cyan"><?php echo $datos['usuario']->nombre; ?></h1>
            <p>Por haber completado con éxito el curso de:</p>
            <h2 class="curso-nombre text-white"><?php echo $datos['curso']->titulo; ?></h2>
            <div class="footer-certificado d-flex justify-content-between mt-5">
                <div class="firma">
                    <hr class="border-white">
                    <small>Instructor Jefe - ORION3D</small>
                </div>
                <div class="qr-code">
                    <i class="fas fa-qrcode fa-4x text-cyan"></i>
                </div>
            </div>
        </div>
    </div>
</div>