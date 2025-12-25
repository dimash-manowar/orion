<div class="mensajeria-container">
    <div class="chat-sidebar">
        <div class="chat-search">
            <input type="text" placeholder="Buscar mensaje o alumno..." class="input-orion">
        </div>
        <div class="chat-list">
            <div class="chat-item active">
                <img src="ruta/al/avatar.jpg" alt="Alumno">
                <div class="chat-info">
                    <div class="chat-header">
                        <span class="user-name">Luna Garcia</span>
                        <span class="chat-time">10:45 AM</span>
                    </div>
                    <p class="last-message">Profe, ¿cómo exporto el modelo en .OBJ?</p>
                </div>
                <span class="unread-dot"></span>
            </div>
            </div>
    </div>

    <div class="chat-main">
        <div class="chat-main-header">
            <div class="d-flex align-items-center">
                <img src="ruta/al/avatar.jpg" class="avatar-min">
                <div>
                    <h5 class="m-0">Luna Garcia</h5>
                    <small class="text-cyan">En línea ahora</small>
                </div>
            </div>
            <div class="chat-actions">
                <button title="Archivar"><i class="fas fa-archive"></i></button>
                <button title="Eliminar"><i class="fas fa-trash"></i></button>
            </div>
        </div>

        <div class="chat-messages" id="chatWindow">
            <div class="msg-group">
                <div class="msg-bubble alumno">
                    Hola profe, tengo una duda con la lección 3 sobre texturas.
                    <span class="msg-time">10:42 AM</span>
                </div>
            </div>

            <div class="msg-group profe">
                <div class="msg-bubble profe">
                    ¡Hola Luna! Claro, dime qué parte no te queda clara.
                    <span class="msg-time">10:44 AM</span>
                </div>
            </div>
        </div>

        <div class="chat-input-area">
            <div class="input-toolbar">
                <button type="button" class="btn-attach" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-paperclip"></i>
                </button>
                <input type="file" id="fileInput" style="display:none;" multiple>
            </div>
            
            <div class="editor-wrapper">
                <textarea id="editor-mensajeria" placeholder="Escribe tu respuesta aquí..."></textarea>
            </div>
            
            <button class="btn-send-orion">
                <i class="fas fa-paper-plane"></i> Enviar
            </button>
        </div>
    </div>
</div>
<button class="btn-nuevo-mensaje" onclick="abrirModalNuevo()">
    <i class="fas fa-edit"></i> Nuevo Mensaje
</button>

<div id="modalNuevoMensaje" class="modal-orion">
    <div class="modal-content-orion">
        <div class="modal-header-orion">
            <h5><i class="fas fa-paper-plane text-cyan"></i> Iniciar Conversación</h5>
            <button onclick="cerrarModalNuevo()" class="btn-close-modal">&times;</button>
        </div>
        
        <form action="<?php echo RUTA_URL; ?>/mensajeria/enviarMensaje" method="POST" enctype="multipart/form-data">
            <div class="form-group-orion">
                <label>Seleccionar Alumno:</label>
                <select name="id_destinatario" class="input-orion" required>
                    <option value="">-- Elige un alumno --</option>
                    <?php foreach($datos['alumnos'] as $alumno): ?>
                        <option value="<?php echo $alumno->id_usuario; ?>">
                            <?php echo $alumno->nombre_usuario; ?> (<?php echo $alumno->rol; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group-orion">
                <label>Asunto:</label>
                <input type="text" name="asunto" class="input-orion" placeholder="Ej: Duda sobre el modelo 3D">
            </div>

            <div class="form-group-orion">
                <label>Mensaje:</label>
                <textarea name="contenido" class="input-orion" rows="5" required placeholder="Escribe tu mensaje..."></textarea>
            </div>

            <div class="form-group-orion">
                <label class="btn-adjuntar">
                    <i class="fas fa-paperclip"></i> Adjuntar archivo
                    <input type="file" name="adjunto" hidden>
                </label>
            </div>

            <div class="modal-footer-orion">
                <button type="button" onclick="cerrarModalNuevo()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-enviar">Enviar Mensaje</button>
            </div>
        </form>
    </div>
</div>