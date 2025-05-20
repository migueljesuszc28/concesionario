<?php include 'includes/header.php'; ?>

<div class="container">
    <h1 class="main-title">Sistema de Gestión de Concesionario</h1>
    
    <div class="dashboard-grid">
        <!-- Tarjeta de Vehículos -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-car"></i>
            </div>
            <div class="card-content">
                <h3>Vehículos Disponibles</h3>
                <p id="total-vehiculos">Cargando...</p>
                <a href="vehiculos/index.php" class="btn btn-card">Ver Todos</a>
            </div>
        </div>
        
        <!-- Tarjeta de Clientes -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3>Clientes Registrados</h3>
                <p id="total-clientes">Cargando...</p>
                <a href="clientes/index.php" class="btn btn-card">Ver Todos</a>
            </div>
        </div>
        
        <!-- Tarjeta de Ventas -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3>Ventas del Mes</h3>
                <p id="ventas-mes">Cargando...</p>
                <a href="ventas/index.php" class="btn btn-card">Ver Reporte</a>
            </div>
        </div>
        
        <!-- Tarjeta de Últimas Ventas -->
        <div class="dashboard-card wide-card">
            <h3>Últimas Ventas</h3>
            <div class="recent-sales">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="ultimas-ventas">
                        <!-- Datos cargados por AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas
    fetch('includes/estadisticas.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-vehiculos').textContent = data.totalVehiculos;
            document.getElementById('total-clientes').textContent = data.totalClientes;
            document.getElementById('ventas-mes').textContent = data.ventasMes + ' ventas';
            
            // Cargar últimas ventas
            let ventasHTML = '';
            data.ultimasVentas.forEach(venta => {
                ventasHTML += `
                    <tr>
                        <td>${venta.fecha}</td>
                        <td>${venta.cliente}</td>
                        <td>${venta.vehiculo}</td>
                        <td>$${venta.total.toLocaleString()}</td>
                    </tr>
                `;
            });
            document.getElementById('ultimas-ventas').innerHTML = ventasHTML;
        });
});
</script>

<?php include 'includes/footer.php'; ?>