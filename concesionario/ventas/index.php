<?php
session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: auth/login.php");
    exit;
}
include '../includes/conexion.php';
include '../includes/header.php';

// Procesar eliminación si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_venta'])) {
    $venta_id = $_POST['venta_id'];

    // Validar ID
    if (is_numeric($venta_id)) {
        // Iniciar transacción para eliminar registros relacionados
        $conexion->begin_transaction();

        try {
            // Eliminar financiamiento relacionado (si existe)
            $conexion->query("DELETE FROM Financiamiento WHERE venta_id = $venta_id");

            // Eliminar la venta
            $stmt = $conexion->prepare("DELETE FROM Venta WHERE venta_id = ?");
            $stmt->bind_param("i", $venta_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $conexion->commit();
                $_SESSION['mensaje'] = "Venta eliminada correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $conexion->rollback();
                $_SESSION['mensaje'] = "No se encontró la venta especificada";
                $_SESSION['tipo_mensaje'] = "warning";
            }

            $stmt->close();

            // Redirigir para evitar reenvío del formulario
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            $conexion->rollback();
            $_SESSION['mensaje'] = "Error al eliminar la venta: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
    }
}

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
<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?>">
        <?= $_SESSION['mensaje'] ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<a href="registrar.php" class="btn">Registrar Nueva Venta</a>
<div class="tabla-container">
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
                        <form method="POST" action="" style="display:inline;"
                            onsubmit="return confirm('¿Estás seguro de eliminar esta venta?');">
                            <input type="hidden" name="venta_id" value="<?= $row['venta_id'] ?>">
                            <button type="submit" name="eliminar_venta" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>