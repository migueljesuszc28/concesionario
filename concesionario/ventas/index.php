<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Consulta para obtener todas las ventas con información relacionada
$query = "SELECT v.venta_id, c.nombre as cliente, e.nombre as empleado, 
          ve.modelo as vehiculo, v.fecha_venta, v.precio_final, v.metodo_pago
          FROM Venta v
          JOIN Cliente c ON v.cliente_id = c.cliente_id
          JOIN Empleado e ON v.empleado_id = e.empleado_id
          JOIN Vehiculo ve ON v.vehiculo_id = ve.vehiculo_id
          ORDER BY v.fecha_venta DESC";
$result = $conexion->query($query);
?>

<h2>Registro de Ventas</h2>
<a href="registrar.php" class="btn">Registrar Nueva Venta</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Empleado</th>
            <th>Vehículo</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Método Pago</th>
            <th>Estado Financiamiento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['venta_id'] ?></td>
                <td><?= $row['cliente'] ?></td>
                <td><?= $row['empleado'] ?></td>
                <td><?= $row['vehiculo'] ?></td>
                <td><?= date('d/m/Y', strtotime($row['fecha_venta'])) ?></td>
                <td>$<?= number_format($row['precio_final'], 2) ?></td>
                <td><?= $row['metodo_pago'] ?></td>
                <td>
                    <?php if ($row['metodo_pago'] == 'Financiamiento'): ?>
                        <?php
                        $estado = $conexion->query("SELECT estado FROM Financiamiento WHERE venta_id = {$row['venta_id']}")->fetch_assoc()['estado'];
                        $color = ($estado == 'Aprobado') ? 'success' : (($estado == 'Rechazado') ? 'danger' : 'warning');
                        ?>
                        <span class="badge badge-<?= $color ?>"><?= $estado ?></span>
                    <?php else: ?>
                        <span class="badge badge-secondary">N/A</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="detalles.php?id=<?= $row['venta_id'] ?>" class="btn btn-sm btn-info">Detalles</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>