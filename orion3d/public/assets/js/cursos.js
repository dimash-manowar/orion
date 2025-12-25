document.addEventListener('DOMContentLoaded', () => {
    const cursoForm = document.getElementById('curso-form');
    const cursosLista = document.getElementById('cursos-lista');

    // 1. Función para obtener y mostrar los cursos
    const obtenerCursos = async () => {
        try {
            const respuesta = await fetch('http://localhost:3000/cursos');
            const cursos = await respuesta.json();
            
            // Limpiamos la lista antes de agregar
            cursosLista.innerHTML = '';

            cursos.forEach(curso => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${curso.id}</td>
                    <td>${curso.nombre}</td>
                    <td>${curso.duracion}</td>
                    <td>
                        <button class="btn-eliminar" data-id="${curso.id}">Eliminar</button>
                    </td>
                `;
                cursosLista.appendChild(fila);
            });
        } catch (error) {
            console.error('Error al obtener cursos:', error);
        }
    };

    // 2. Evento para agregar un nuevo curso
    cursoForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nuevoCurso = {
            nombre: document.getElementById('nombre').value,
            duracion: document.getElementById('duracion').value
        };

        try {
            const respuesta = await fetch('http://localhost:3000/cursos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(nuevoCurso)
            });

            if (respuesta.ok) {
                cursoForm.reset(); // Limpiar formulario
                obtenerCursos();   // Recargar lista
            }
        } catch (error) {
            console.error('Error al agregar curso:', error);
        }
    });

    // 3. Evento para eliminar (Delegación de eventos)
    cursosLista.addEventListener('click', async (e) => {
        // Verificamos si el clic fue en un botón con la clase 'btn-eliminar'
        if (e.target.classList.contains('btn-eliminar')) {
            const id = e.target.getAttribute('data-id');
            const confirmar = confirm(`¿Estás seguro de eliminar el curso con ID ${id}?`);

            if (confirmar) {
                try {
                    const respuesta = await fetch(`http://localhost:3000/cursos/${id}`, {
                        method: 'DELETE'
                    });

                    if (respuesta.ok) {
                        obtenerCursos(); // Recargar la lista tras eliminar
                    }
                } catch (error) {
                    console.error('Error al eliminar curso:', error);
                }
            }
        }
    });

    // Carga inicial
    obtenerCursos();
});