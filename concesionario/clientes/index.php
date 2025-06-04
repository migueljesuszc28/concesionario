<?php
session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: auth/login.php");
    exit;
}
include '../includes/conexion.php';
include '../includes/header.php';

$query = "SELECT * FROM Cliente ORDER BY fecha_registro DESC";
$result = $conexion->query($query);
?>

<h2>Clientes Registrados</h2>
<a href="agregar.php" class="btn">Agregar Cliente</a>

<div class="tabla-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha Registro</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['cliente_id'] ?></td>
                    <td><?= $row['nombre'] ?>     <?= $row['apellidos'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['telefono'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['fecha_registro'])) ?></td>
                    <td><?= $row['tipo_cliente'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $row['cliente_id'] ?>" class="btn">Editar</a>
                        <button onclick="confirmarEliminacion(<?= $row['cliente_id'] ?>, 'cliente')"
                            class="btn btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>