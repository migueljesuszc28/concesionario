<?php
include '../includes/conexion.php';
include '../includes/header.php';

$query = "SELECT v.*, m.nombre as marca_nombre FROM Vehiculo v JOIN Marca m ON v.marca_id = m.marca_id";
// En vehiculos/index.php
$query = "SELECT v.*, m.nombre as marca_nombre 
          FROM Vehiculo v 
          JOIN Marca m ON v.marca_id = m.marca_id
          ORDER BY v.vehiculo_id DESC";
$result = $conexion->query($query);
?>

<div class="container">
    <h2>Vehículos en Inventario</h2>
    <a href="agregar.php" class="btn">Agregar Vehículo</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['vehiculo_id'] ?></td>
                    <td><?= $row['marca_nombre'] ?></td>
                    <td><?= $row['modelo'] ?></td>
                    <td><?= $row['anio'] ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <!-- En la tabla de vehículos -->
                    <td>
                        <a href="editar.php?id=<?= $row['vehiculo_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <button onclick="confirmarEliminacion(<?= $row['vehiculo_id'] ?>, 'vehiculo')"
                            class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>