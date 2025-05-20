<?php
session_start();
include '../includes/conexion.php';
include '../includes/header.php';

// Verificar si se proporcionó un ID de venta
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=ID de venta no válido");
    exit;
}

$venta_id = intval($_GET['id']);

// Obtener información detallada de la venta
$query = "SELECT 
            v.venta_id,
            v.fecha_venta,
            v.precio_final,
            v.metodo_pago,
            v.descuento,
            v.estado,
            c.cliente_id,
            CONCAT(c.nombre, ' ', c.apellidos) as cliente_nombre,
            c.email as cliente_email,
            c.telefono as cliente_telefono,
            e.empleado_id,
            CONCAT(e.nombre, ' ', e.apellidos) as empleado_nombre,
            ve.vehiculo_id,
            CONCAT(m.nombre, ' ', ve.modelo) as vehiculo_nombre,
            ve.precio as vehiculo_precio,
            ve.color as vehiculo_color,
            ve.anio as vehiculo_anio,
            f.monto_financiado,
            f.plazo_meses,
            f.tasa_interes,
            f.institucion_financiera
          FROM Venta v
          JOIN Cliente c ON v.cliente_id = c.cliente_id
          JOIN Empleado e ON v.empleado_id = e.empleado_id
          JOIN Vehiculo ve ON v.vehiculo_id = ve.vehiculo_id
          JOIN Marca m ON ve.marca_id = m.marca_id
          LEFT JOIN Financiamiento f ON v.venta_id = f.venta_id
          WHERE v.venta_id = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$result = $stmt->get_result();
$venta = $result->fetch_assoc();

if (!$venta) {
    header("Location: index.php?error=Venta no encontrada");
    exit;
}
?>

<div class="container">
    <h2>Detalles de Venta #<?= $venta['venta_id'] ?></h2>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Información General</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></p>
                    <p><strong>Estado:</strong> <span
                            class="badge badge-<?= $venta['estado'] == 'Completado' ? 'success' : ($venta['estado'] == 'Cancelado' ? 'danger' : 'warning') ?>">
                            <?= $venta['estado'] ?>
                        </span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Método de Pago:</strong> <?= $venta['metodo_pago'] ?></p>
                    <p><strong>Descuento:</strong> $<?= number_format($venta['descuento'], 2) ?></p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <p><strong>Total:</strong> <span class="h4">$<?= number_format($venta['precio_final'], 2) ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Cliente</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?= $venta['cliente_nombre'] ?></p>
                    <p><strong>Teléfono:</strong> <?= $venta['cliente_telefono'] ?></p>
                    <p><strong>Email:</strong> <?= $venta['cliente_email'] ?></p>
                    <a href="../clientes/editar.php?id=<?= $venta['cliente_id'] ?>"
                        class="btn btn-sm btn-outline-primary">Ver Cliente</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Vendedor</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?= $venta['empleado_nombre'] ?></p>
                    <a href="../empleados/editar.php?id=<?= $venta['empleado_id'] ?>"
                        class="btn btn-sm btn-outline-primary">Ver Vendedor</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Vehículo</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Modelo:</strong> <?= $venta['vehiculo_nombre'] ?></p>
                    <p><strong>Año:</strong> <?= $venta['vehiculo_anio'] ?></p>
                    <p><strong>Color:</strong> <?= $venta['vehiculo_color'] ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Precio Original:</strong> $<?= number_format($venta['vehiculo_precio'], 2) ?></p>
                    <a href="../vehiculos/editar.php?id=<?= $venta['vehiculo_id'] ?>"
                        class="btn btn-sm btn-outline-primary">Ver Vehículo</a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($venta['metodo_pago'] == 'Financiamiento'): ?>
        <?php
        $financiamiento = $conexion->query("SELECT * FROM Financiamiento WHERE venta_id = {$venta['venta_id']}")->fetch_assoc();
        ?>
        <div class="card mt-4">
            <div class="card-header">
                <h3>Detalles del Financiamiento</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Monto Financiado:</strong> $<?= number_format($financiamiento['monto_financiado'], 2) ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Plazo:</strong> <?= $financiamiento['plazo_meses'] ?> meses</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Tasa de Interés:</strong> <?= $financiamiento['tasa_interes'] ?>%</p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <p><strong>Institución:</strong> <?= $financiamiento['institucion_financiera'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado:</strong>
                            <span class="badge badge-<?= $financiamiento['estado'] == 'Aprobado' ? 'success' :
                                ($financiamiento['estado'] == 'Rechazado' ? 'danger' : 'warning') ?>">
                                <?= $financiamiento['estado'] ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($venta['metodo_pago'] == 'Financiamiento'): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h4>Acciones Administrativas</h4>
            </div>
            <div class="card-body">
                <div class="btn-group">
                    <?php if ($financiamiento['estado'] == 'En proceso'): ?>
                        <button onclick="cambiarEstado(<?= $financiamiento['financiamiento_id'] ?>, 'Aprobado')"
                            class="btn btn-success">
                            Aprobar Financiamiento
                        </button>
                        <button onclick="cambiarEstado(<?= $financiamiento['financiamiento_id'] ?>, 'Rechazado')"
                            class="btn btn-danger">
                            Rechazar Financiamiento
                        </button>
                    <?php else: ?>
                        <p class="text-muted">Financiamiento ya <?= strtolower($financiamiento['estado']) ?></p>
                        <button onclick="cambiarEstado(<?= $financiamiento['financiamiento_id'] ?>, 'En proceso')"
                            class="btn btn-warning">
                            Revertir a Pendiente
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            function cambiarEstado(id, estado) {
                if (confirm(`¿Cambiar estado del financiamiento a "${estado}"?`)) {
                    fetch('../includes/actualizar_financiamiento.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `financiamiento_id=${id}&estado=${estado}`
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                window.location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        });
                }
            }
        </script>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">Volver a Ventas</a>
        <button onclick="window.print()" class="btn btn-primary ml-2">Imprimir</button>
    </div>
</div>

<?php include '../includes/footer.php'; ?>