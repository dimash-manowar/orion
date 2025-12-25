<?php require RUTA_APP . '/vistas/inc/header.php'; ?>

<div class="container mt-4">
    <div class="card bg-dark text-white border-0 shadow-lg p-4" style="background: #161b22;">
        <h2 class="text-cyan mb-4"><i class="fas fa-plus-circle me-2"></i>Crear Nuevo Curso</h2>
        
        <form action="<?php echo RUTA_URL; ?>/cursos/guardar" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Título del Curso</label>
                        <input type="text" name="titulo" class="form-control bg-dark text-white border-secondary" placeholder="Ej: Master en Shaders para Unity" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control bg-dark text-white border-secondary" rows="10" placeholder="¿Qué aprenderán los alumnos?"></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select bg-dark text-white border-secondary">
                            <option value="unity">Unity (C#)</option>
                            <option value="blender">Blender (3D)</option>
                            <option value="web">Desarrollo Web</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nivel</label>
                        <select name="nivel" class="form-select bg-dark text-white border-secondary">
                            <option value="basico">Básico</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio ($)</label>
                        <input type="number" name="precio" class="form-control bg-dark text-white border-secondary" step="0.01" value="0.00">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Imagen de Portada (JPG/PNG)</label>
                        <input type="file" name="portada" class="form-control bg-dark text-white border-secondary" accept="image/*" required>
                    </div>

                    <hr class="border-secondary">
                    
                    <button type="submit" id="btn_publicar" class="btn btn-info w-100 fw-bold">
                        <i class="fas fa-save me-2"></i>PUBLICAR CURSO
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require RUTA_APP . '/vistas/inc/footer.php'; ?>