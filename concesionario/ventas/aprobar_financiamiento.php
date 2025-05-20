<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Verificar permisos (ejemplo básico)
session_start();
if($_SESSION['rol'] != 'admin') {
    header("Location: ../index.php?error=Acceso no autorizado");
    exit;
}

// Obtener financiamientos pendientes
$query = "SELECT f.*, v.venta_id, v.precio_final, 
          CONCAT(c.nombre, ' ', c.apellidos) as cliente_nombre,
          CONCAT(m.nombre, ' ', ve.modelo) as vehiculo
          FROM Financiamiento f
          JOIN Venta v ON f.venta_id = v.venta_id
          JOIN Cliente c ON v.cliente_id = c.cliente_id
          JOIN Vehiculo ve ON v.vehiculo_id = ve.vehiculo_id
          JOIN Marca m ON ve.marca_id = m.marca_id
          WHERE f.estado = 'En proceso'
          ORDER BY f.fecha_aprobacion DESC";

$financiamientos = $conexion->query($query);
?>

<div class="container">
    <h2>Aprobar/Rechazar Financiamientos</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Monto</th>
                    <th>Plazo</th>
                    <th>Tasa</th>
                    <th>Institución</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fin = $financiamientos->fetch_assoc()): ?>
                <tr>
                    <td><?= $fin['venta_id'] ?></td>
                    <td><?= $fin['cliente_nombre'] ?></td>
                    <td><?= $fin['vehiculo'] ?></td>
                    <td>$<?= number_format($fin['monto_financiado'], 2) ?></td>
                    <td><?= $fin['plazo_meses'] ?> meses</td>
                    <td><?= $fin['tasa_interes'] ?>%</td>
                    <td><?= $fin['institucion_financiera'] ?></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-success" 
                                    onclick="cambiarEstado(<?= $fin['financiamiento_id'] ?>, 'Aprobado')">
                                Aprobar
                            </button>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="cambiarEstado(<?= $fin['financiamiento_id'] ?>, 'Rechazado')">
                                Rechazar
                            </button>
                            <a href="detalles.php?id=<?= $fin['venta_id'] ?>" 
                               class="btn btn-sm btn-info">
                                Detalles
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function cambiarEstado(id, estado) {
    if(confirm(`¿Está seguro de marcar este financiamiento como "${estado}"?`)) {
        fetch('../includes/actualizar_financiamiento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `financiamiento_id=${id}&estado=${estado}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error en la solicitud');
        });
    }
}
</script>

<?php include '../includes/footer.php'; ?>