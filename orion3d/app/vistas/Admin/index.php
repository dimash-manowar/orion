<div class="container-fluid">
    <h2 class="text-white mb-4">Panel de <span class="text-cyan">Control</span></h2>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="widget-orion">
                <div class="icon-box bg-cyan"><i class="fas fa-users"></i></div>
                <div class="data">
                    <span class="text-muted">Alumnos Totales</span>
                    <h3 class="text-white"><?php echo $datos['metricas']->total_alumnos; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-orion">
                <div class="icon-box bg-purple"><i class="fas fa-graduation-cap"></i></div>
                <div class="data">
                    <span class="text-muted">Cursos Activos</span>
                    <h3 class="text-white"><?php echo $datos['metricas']->total_cursos; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget-orion">
                <div class="icon-box bg-success"><i class="fas fa-wallet"></i></div>
                <div class="data">
                    <span class="text-muted">Ingresos Totales</span>
                    <h3 class="text-white"><?php echo $datos['metricas']->ingresos_totales; ?> €</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card-chart-orion">
                <h5 class="text-white mb-4">Rendimiento de Ventas</h5>
                <canvas id="graficaVentas" height="150"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-chart-orion">
                <h5 class="text-white mb-4">Distribución por Categoría</h5>
                <canvas id="graficaCategorias"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('graficaVentas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'], // Estos vendrían de tu PHP
            datasets: [{
                label: 'Ventas (€)',
                data: [120, 190, 300, 250, 400, 550],
                borderColor: '#00f2ff',
                backgroundColor: 'rgba(0, 242, 255, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: { legend: { labels: { color: 'white' } } },
            scales: {
                y: { ticks: { color: '#8b949e' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { ticks: { color: '#8b949e' }, grid: { display: false } }
            }
        }
    });
</script>