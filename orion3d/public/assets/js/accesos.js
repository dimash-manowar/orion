(function () {
    const cfg = window.ORION_ACCESOS || {};
    if (!cfg.page) return;
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');


    const toast = (icon, title) => {
        if (!window.Swal) return;
        Swal.fire({
            toast: true,
            position: 'top-end',
            timer: 2200,
            showConfirmButton: false,
            icon, title
        });
    };

    // Mensaje ok/error por query
    if (cfg.ok === 1) toast('success', 'Guardado correctamente');

    if (cfg.ok === 0) {
        const mapMsg = {
            ultimo_admin: 'No puedes quitar el último admin del sistema',
            auto_admin: 'No puedes quitarte el rol admin a ti mismo',
            db_error: 'Error de base de datos al guardar',
            bad_request: 'Datos inválidos'
        };
        toast('error', mapMsg[msg] || 'No se pudo guardar');
    }

    // ===== PAGE: ASIGNACIONES =====
    if (cfg.page === "asignaciones") {
        const MAP = cfg.map || {};
        const rolSelect = document.getElementById('rolSelect');
        const rolIdInput = document.getElementById('rol_id');
        const filtro = document.getElementById('filtroPermisos');
        const btnGuardar = document.getElementById('btnGuardarAsignaciones');
        const form = document.getElementById('formGuardarAsignaciones');
        const permById = cfg.permById || {};
        const checks = () => Array.from(document.querySelectorAll('.chkPermiso'));
        const rows = () => Array.from(document.querySelectorAll('.permiso-row'));

        function pintarChecks(rolId) {
            rolIdInput.value = rolId;
            const set = MAP[rolId] || {};
            checks().forEach(chk => {
                chk.checked = !!set[chk.value];
            });
        }

        // init
        if (rolSelect) pintarChecks(rolSelect.value);

        rolSelect?.addEventListener('change', () => pintarChecks(rolSelect.value));

        // filtro
        filtro?.addEventListener('input', () => {
            const q = (filtro.value || '').trim().toLowerCase();
            rows().forEach(row => {
                const txt = (row.dataset.text || '').toLowerCase();
                row.style.display = txt.includes(q) ? '' : 'none';
            });
        });

        // acciones globales
        document.querySelector('[data-action="marcar-todo"]')?.addEventListener('click', () => {
            checks().forEach(c => c.checked = true);
        });
        document.querySelector('[data-action="desmarcar-todo"]')?.addEventListener('click', () => {
            checks().forEach(c => c.checked = false);
        });

        // acciones por módulo
        document.querySelectorAll('.modulo').forEach(mod => {
            mod.querySelector('[data-action="modulo-marcar"]')?.addEventListener('click', () => {
                mod.querySelectorAll('.chkPermiso').forEach(c => c.checked = true);
            });
            mod.querySelector('[data-action="modulo-desmarcar"]')?.addEventListener('click', () => {
                mod.querySelectorAll('.chkPermiso').forEach(c => c.checked = false);
            });
        });

        // chips: solo ver / solo gestionar
        document.querySelector('[data-action="solo-ver"]')?.addEventListener('click', () => {
            checks().forEach(c => c.checked = false);
            rows().forEach(row => {
                const clave = row.querySelector('.permiso-clave')?.textContent || '';
                if (clave.includes('.ver')) row.querySelector('.chkPermiso').checked = true;
            });
        });

        document.querySelector('[data-action="solo-gestionar"]')?.addEventListener('click', () => {
            checks().forEach(c => c.checked = false);
            rows().forEach(row => {
                const clave = row.querySelector('.permiso-clave')?.textContent || '';
                if (clave.includes('.gestionar') || clave.includes('.editar')) row.querySelector('.chkPermiso').checked = true;
            });
        });

        document.querySelector('[data-action="limpiar-filtro"]')?.addEventListener('click', () => {
            if (!filtro) return;
            filtro.value = '';
            filtro.dispatchEvent(new Event('input'));
        });

        // guardar con confirmación
        btnGuardar?.addEventListener('click', async () => {
            const total = checks().length;
            const marcados = checks().filter(c => c.checked).length;

            const res = await Swal.fire({
                title: '¿Guardar permisos?',
                html: `<div style="text-align:left">
                <p>Rol: <b>${rolSelect?.selectedOptions?.[0]?.text || ''}</b></p>
                <p>Marcados: <b>${marcados}</b> / ${total}</p>
               </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });

            if (res.isConfirmed) form.submit();
        });
        function setByPredicate(fn, value = true) {
            checks().forEach(chk => {
                const clave = permById[String(chk.value)] || "";
                if (fn(clave)) chk.checked = value;
            });
        }

        function aplicarPlantilla(name) {
            // primero limpia
            checks().forEach(c => c.checked = false);

            if (name === "solo_lectura") {
                // todo lo que termine en .ver
                setByPredicate(clave => clave.endsWith(".ver"), true);
                // extras útiles (ajusta si quieres)
                setByPredicate(clave => clave === "dashboard.ver", true);
                setByPredicate(clave => clave === "mensajeria.usar", true);
            }

            if (name === "panel_basico") {
                // panel + ver cursos/lecciones + mensajería
                setByPredicate(clave => ["dashboard.ver", "cursos.ver", "lecciones.ver", "mensajeria.usar"].includes(clave), true);
            }

            if (name === "profesor_completo") {
                // lo típico de profesor
                setByPredicate(clave => clave === "dashboard.ver", true);
                setByPredicate(clave => clave.startsWith("cursos."), true);     // ver + gestionar
                setByPredicate(clave => clave.startsWith("lecciones."), true);  // ver + gestionar
                setByPredicate(clave => clave === "mensajeria.usar", true);

                // pagos solo ver (si quieres)
                setByPredicate(clave => clave === "pagos.ver", true);
            }
        }

        // listeners
        document.querySelectorAll('[data-template]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const name = btn.dataset.template;

                const res = await Swal.fire({
                    title: 'Aplicar plantilla',
                    text: 'Esto reemplazará la selección actual de permisos (puedes ajustar después).',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aplicar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                });

                if (res.isConfirmed) aplicarPlantilla(name);
            });
        });
    }

    // ===== PAGE: USUARIOS =====
    if (cfg.page === "usuarios") {
        document.querySelectorAll('.btnGuardarUsuario').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const form = e.target.closest('form');
                const usuario = form?.dataset?.usuario || 'usuario';
                const marcados = form ? form.querySelectorAll('input[type="checkbox"]:checked').length : 0;

                const res = await Swal.fire({
                    title: 'Guardar roles',
                    html: `<p>Usuario: <b>${usuario}</b></p><p>Roles marcados: <b>${marcados}</b></p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                });

                if (res.isConfirmed) form.submit();
            });
        });
    }
})();
