<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Obtener clientes, empleados y vehículos disponibles
$clientes = $conexion->query("SELECT cliente_id, CONCAT(nombre, ' ', apellidos) as nombre FROM Cliente WHERE estado = 1");
$empleados = $conexion->query("SELECT empleado_id, CONCAT(nombre, ' ', apellidos) as nombre FROM Empleado WHERE estado = 1");
$vehiculos = $conexion->query("SELECT v.vehiculo_id, CONCAT(m.nombre, ' ', v.modelo) as nombre, v.precio 
                              FROM Vehiculo v 
                              JOIN Marca m ON v.marca_id = m.marca_id 
                              WHERE v.stock > 0");
?>

<h2>Registrar Nueva Venta</h2>

<form class="formularios" id="form-venta" action="../includes/registrar_venta.php" method="POST">
    <div class="form-group">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" id="cliente_id" class="form-control" required>
            <option value="">Seleccione un cliente</option>
            <?php while($cliente = $clientes->fetch_assoc()): ?>
            <option value="<?= $cliente['cliente_id'] ?>"><?= $cliente['nombre'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="empleado_id">Vendedor:</label>
        <select name="empleado_id" id="empleado_id" class="form-control" required>
            <option value="">Seleccione un vendedor</option>
            <?php while($empleado = $empleados->fetch_assoc()): ?>
            <option value="<?= $empleado['empleado_id'] ?>"><?= $empleado['nombre'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="vehiculo_id">Vehículo:</label>
        <select name="vehiculo_id" id="vehiculo_id" class="form-control" required>
            <option value="">Seleccione un vehículo</option>
            <?php while($vehiculo = $vehiculos->fetch_assoc()): ?>
            <option value="<?= $vehiculo['vehiculo_id'] ?>" data-precio="<?= $vehiculo['precio'] ?>">
                <?= $vehiculo['nombre'] ?> - $<?= number_format($vehiculo['precio'], 2) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="precio_base">Precio Base:</label>
        <input type="number" name="precio_base" id="precio_base" class="form-control" readonly>
    </div>
    
    <div class="form-group">
        <label for="descuento">Descuento ($):</label>
        <input type="number" name="descuento" id="descuento" class="form-control" value="0" min="0" step="0.01">
    </div>
    
    <div class="form-group">
        <label for="precio_final">Precio Final:</label>
        <input type="number" name="precio_final" id="precio_final" class="form-control" readonly>
    </div>
    
    <div class="form-group">
        <label for="metodo_pago">Método de Pago:</label>
        <select name="metodo_pago" id="metodo_pago" class="form-control" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Tarjeta">Tarjeta</option>
            <option value="Financiamiento">Financiamiento</option>
        </select>
    </div>
    <div id="financiamiento-section" style="display: none;">
    <div class="card mt-3">
        <div class="card-header">
            <h4>Datos del Financiamiento</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="monto_financiado">Monto Financiado ($)</label>
                        <input type="number" class="form-control" name="monto_financiado" id="monto_financiado" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="plazo_meses">Plazo (meses)</label>
                        <input type="number" class="form-control" name="plazo_meses" id="plazo_meses" min="1" value="12">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tasa_interes">Tasa de Interés (%)</label>
                        <input type="number" class="form-control" name="tasa_interes" id="tasa_interes" step="0.01" min="0" value="8.5">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="institucion_financiera">Institución Financiera</label>
                <input type="text" class="form-control" name="institucion_financiera" id="institucion_financiera">
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar sección de financiamiento
document.getElementById('metodo_pago').addEventListener('change', function() {
    document.getElementById('financiamiento-section').style.display = 
        (this.value === 'Financiamiento') ? 'block' : 'none';
});
</script>
    
    <button type="submit" class="btn btn-primary">Registrar Venta</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<script>
// Actualizar precios al seleccionar vehículo
document.getElementById('vehiculo_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const precioBase = selectedOption.getAttribute('data-precio') || 0;
    
    document.getElementById('precio_base').value = precioBase;
    calcularPrecioFinal();
});

// Calcular precio final al cambiar descuento
document.getElementById('descuento').addEventListener('input', calcularPrecioFinal);

function calcularPrecioFinal() {
    const precioBase = parseFloat(document.getElementById('precio_base').value) || 0;
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const precioFinal = Math.max(0, precioBase - descuento);
    
    document.getElementById('precio_final').value = precioFinal.toFixed(2);
}

// Validar formulario antes de enviar
document.getElementById('form-venta').addEventListener('submit', function(e) {
    const precioFinal = parseFloat(document.getElementById('precio_final').value);
    if(precioFinal <= 0) {
        e.preventDefault();
        alert('El precio final debe ser mayor que cero');
    }
});
</script>

<?php include '../includes/footer.php'; ?>