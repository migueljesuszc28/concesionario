<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Obtener datos del cliente a editar
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Cliente WHERE cliente_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    
    if(!$cliente) {
        header("Location: index.php?error=Cliente no encontrado");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<h2>Editar Cliente</h2>

<form action="../includes/actualizar_cliente.php" method="POST">
    <input type="hidden" name="cliente_id" value="<?= $cliente['cliente_id'] ?>">
    
    <div>
        <label for="tipo_cliente">Tipo de Cliente:</label>
        <select name="tipo_cliente" id="tipo_cliente" required>
            <option value="Persona" <?= $cliente['tipo_cliente'] == 'Persona' ? 'selected' : '' ?>>Persona</option>
            <option value="Empresa" <?= $cliente['tipo_cliente'] == 'Empresa' ? 'selected' : '' ?>>Empresa</option>
        </select>
    </div>
    
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
    </div>
    
    <div>
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" value="<?= htmlspecialchars($cliente['apellidos']) ?>">
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
    </div>
    
    <div>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
    </div>
    
    <div>
        <label for="direccion">Dirección:</label>
        <textarea name="direccion" id="direccion" rows="3"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
    </div>
    
    <div>
        <label for="licencia_conducir">Licencia de Conducir:</label>
        <input type="text" name="licencia_conducir" id="licencia_conducir" value="<?= htmlspecialchars($cliente['licencia_conducir']) ?>">
    </div>
    
    <div>
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="1" <?= $cliente['estado'] ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= !$cliente['estado'] ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>
    
    <button type="submit" class="btn">Actualizar Cliente</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>