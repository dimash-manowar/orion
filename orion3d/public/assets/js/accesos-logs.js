(() => {
    const cfg = window.ORION_LOGS;
    if (!cfg?.endpoint) return;

    const $ = (id) => document.getElementById(id);

    let state = {
        page: 1,
        perPage: 15,
        total: 0,
        pages: 1
    };

    function paramsFromUI() {
        return {
            q: $('logQ').value.trim(),
            accion: $('logAccion').value,
            actor_id: $('logActor').value,
            desde: $('logDesde').value,
            hasta: $('logHasta').value,
            perPage: $('logPerPage').value,
            page: state.page
        };
    }

    function buildUrl(base, params) {
        const u = new URL(base);
        Object.entries(params).forEach(([k, v]) => {
            if (v !== null && v !== undefined && String(v) !== '' && String(v) !== '0') u.searchParams.set(k, v);
            // actor_id = 0 lo tratamos como “todos”
            if (k === 'actor_id' && String(v) === '0') u.searchParams.delete(k);
            if (k === 'perPage') u.searchParams.set(k, v);
            if (k === 'page') u.searchParams.set(k, v);
            if (k === 'q' && String(v) === '') u.searchParams.delete(k);
            if ((k === 'desde' || k === 'hasta') && String(v) === '') u.searchParams.delete(k);
            if (k === 'accion' && String(v) === '') u.searchParams.delete(k);
        });
        return u.toString();
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", "&#039;");
    }

    function shortDetail(detalle) {
        try {
            const obj = typeof detalle === 'string' ? JSON.parse(detalle) : detalle;
            if (!obj) return '';
            if (obj.antes && obj.despues) return `Antes: ${obj.antes.length} · Después: ${obj.despues.length}`;
            return 'Ver detalle';
        } catch {
            return 'Ver detalle';
        }
    }

    function renderRows(rows) {
        const body = $('logsBody');
        if (!rows?.length) {
            body.innerHTML = `<div class="tabla__row logs-row"><div class="muted">Sin resultados</div></div>`;
            return;
        }

        body.innerHTML = rows.map(r => {
            const objetivo = `${escapeHtml(r.objetivo_tipo)}${r.objetivo_id ? ' #' + r.objetivo_id : ''}`;
            const actor = `${escapeHtml(r.actor_nombre)}<div class="muted" style="font-size:12px">${escapeHtml(r.actor_email)}</div>`;
            const det = shortDetail(r.detalle);

            return `
        <div class="tabla__row logs-row" data-detalle="${escapeHtml(r.detalle ?? '')}" data-id="${r.id}">
          <div>${escapeHtml(r.creado_en)}</div>
          <div>${actor}</div>
          <div class="strong">${escapeHtml(r.accion)}</div>
          <div>${escapeHtml(objetivo)}</div>
          <div class="muted">${escapeHtml(r.ip ?? '')}</div>
          <div class="right">
            <button class="accesos-btn accesos-btn--mini btnDetalle" type="button">Detalle</button>
          </div>
        </div>
      `;
        }).join('');

        // bind detalle
        body.querySelectorAll('.btnDetalle').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const row = e.target.closest('.tabla__row');
                const raw = row?.dataset?.detalle || '';
                mostrarDetalle(raw, row?.dataset?.id);
            });
        });
    }

    function mostrarDetalle(raw, id) {
        let html = `<pre style="text-align:left; white-space:pre-wrap; font-size:12px;">${escapeHtml(raw || '')}</pre>`;

        // intenta pretty JSON
        try {
            const obj = JSON.parse(raw);
            html = `<pre style="text-align:left; white-space:pre-wrap; font-size:12px;">${escapeHtml(JSON.stringify(obj, null, 2))}</pre>`;
        } catch { }

        Swal.fire({
            title: `Detalle log #${id ?? ''}`,
            html,
            width: 800,
            showCloseButton: true
        });
    }

    function renderMeta(meta) {
        $('logsMeta').textContent = `Total: ${meta.total} · Página ${meta.page} / ${meta.pages} · ${meta.perPage} por página`;
        $('pageInfo').textContent = `Página ${meta.page} / ${meta.pages}`;
        state.total = meta.total;
        state.pages = meta.pages;
        state.perPage = meta.perPage;
        state.page = meta.page;
    }

    async function load() {
        const url = buildUrl(cfg.endpoint, paramsFromUI());

        $('btnBuscar').disabled = true;
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'fetch' } });
            const json = await res.json();

            if (!json.ok) throw new Error('Respuesta inválida');

            renderMeta(json.meta);
            renderRows(json.data);
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar la auditoría.' });
        } finally {
            $('btnBuscar').disabled = false;
        }
    }

    // events
    $('btnBuscar').addEventListener('click', () => { state.page = 1; load(); });
    $('logAccion').addEventListener('change', () => { state.page = 1; load(); });
    $('logActor').addEventListener('change', () => { state.page = 1; load(); });
    $('logDesde').addEventListener('change', () => { state.page = 1; load(); });
    $('logHasta').addEventListener('change', () => { state.page = 1; load(); });
    $('logPerPage').addEventListener('change', () => { state.page = 1; load(); });

    $('btnPrev').addEventListener('click', () => {
        if (state.page > 1) { state.page--; load(); }
    });

    $('btnNext').addEventListener('click', () => {
        if (state.page < state.pages) { state.page++; load(); }
    });

    // Enter en búsqueda
    $('logQ').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') { e.preventDefault(); state.page = 1; load(); }
    });

    // init
    load();
})();
