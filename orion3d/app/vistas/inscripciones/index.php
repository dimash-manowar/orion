<div class="container-fluid">
    <div class="mb-4">
        <h2 class="text-white">Control de <span class="text-cyan">Inscripciones</span></h2>
        <p class="text-muted small">Gestiona los alumnos que han accedido a tus cursos.</p>
    </div>

    <div class="card-orion-table">
        <div class="table-responsive">
            <table class="table table-dark-orion align-middle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th>Fecha</th>
                        <th>Estado de Pago</th>
                        <th>Progreso</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($datos['inscritos'] as $registro) : ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo RUTA_URL; ?>/public/assets/img/perfiles/<?php echo $registro->foto_perfil ?: 'default.png'; ?>" class="avatar-table me-2">
                                <div>
                                    <div class="text-white fw-bold"><?php echo $registro->nombre_usuario; ?></div>
                                    <small class="text-muted"><?php echo $registro->email; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-cyan"><?php echo $registro->nombre_curso; ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($registro->fecha_inscripcion)); ?></td>
                        <td>
                            <span class="badge-pago <?php echo $registro->estado_pago; ?>">
                                <?php echo $registro->monto_pagado; ?>€ (<?php echo ucfirst($registro->estado_pago); ?>)
                            </span>
                        </td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: <?php echo $registro->progreso; ?>%"></div>
                                <small><?php echo $registro->progreso; ?>%</small>
                            </div>
                        </td>
                        <td class="text-end">
                            <button class="btn-action-edit" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="btn-action-delete" title="Dar de baja"><i class="fas fa-user-minus"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>