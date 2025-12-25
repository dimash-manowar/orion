

<section class="welcome-hero mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="text-white">
                <?php
                $hora = date('H');
                if ($hora < 12) echo "¡Buenos días, ";
                elseif ($hora < 20) echo "¡Buenas tardes, ";
                else echo "¡Buenas noches, ";
                echo explode(' ', $_SESSION['nombre_usuario'])[0];
                ?>! 🚀
            </h2>
            <p class="text-muted">
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    Panel Maestro activo. El sistema reporta un funcionamiento estable.
                <?php elseif ($_SESSION['rol'] === 'profesor'): ?>
                    Tienes lecciones pendientes de revisar. ¡Tus alumnos te esperan!
                <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-dark text-white border-0 shadow-lg"
                        style="background: linear-gradient(135deg, #05070a 0%, #1a1f2c 100%); overflow: hidden; position: relative;">

                        <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(0, 242, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>

                        <div class="card-body p-5">

                            <p class="lead mt-3" style="color: #e6edf3; letter-spacing: 1px;">
                                "El universo 3D está en tus manos. Diseña sin límites en la constelación de <span class="text-info font-weight-bold">Orion</span>."
                            </p>
                            <hr class="my-4" style="border-top: 1px solid rgba(255,255,255,0.1);">

                            <div class="xp-container p-3 card-orion mb-4">
                                <?php if (!empty($datos['usuario'])):
                                    $u = $datos['usuario']; // Simplificamos para no escribir [0] todo el rato
                                ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-white fw-bold">Nivel <?php echo $u['nivel_rango']; ?></span>
                                        <span class="text-cyan small"><?php echo obtenerNombreRango($u['nivel_rango']); ?></span>
                                    </div>

                                    <div class="progress-xp">
                                        <?php $progreso_nivel = ($u['xp'] % 1000) / 10; ?>
                                        <div class="progress-xp-fill" style="width: <?php echo $progreso_nivel; ?>%"></div>
                                    </div>

                                    <small class="text-muted">
                                        <?php echo ($u['xp'] % 1000); ?> / 1000 XP para el siguiente nivel
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </p>
        </div>

        <div class="col-md-4">
            <div class="quick-status-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 15px;">

                <div class="d-flex justify-content-around text-center">
                    <div>
                        <span class="d-block text-white fw-bold">Active</span>
                        <small class="text-muted small">Estado</small>
                    </div>
                    <div class="vr bg-secondary"></div>
                    <div>
                        <span class="d-block text-white fw-bold"><?php echo date('d/m'); ?></span>
                        <small class="text-muted small">Fecha</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>