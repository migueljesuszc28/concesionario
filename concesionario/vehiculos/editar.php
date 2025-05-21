<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Obtener datos del vehículo a editar
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM Vehiculo WHERE vehiculo_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehiculo = $result->fetch_assoc();
    
    if(!$vehiculo) {
        header("Location: index.php?error=Vehículo no encontrado");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

// Obtener marcas para el select
$marcas = $conexion->query("SELECT * FROM Marca ORDER BY nombre");
?>

<h2>Editar Vehículo</h2>

<form class="formularios" action="../includes/actualizar_vehiculo.php" method="POST">
    <input type="hidden" name="vehiculo_id" value="<?= $vehiculo['vehiculo_id'] ?>">
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="vin">VIN:</label>
                <input type="text" class="form-control" name="vin" id="vin" 
                       value="<?= htmlspecialchars($vehiculo['vin']) ?>" required readonly>
            </div>
            
            <div class="form-group">
                <label for="marca_id">Marca:</label>
                <select class="form-control" name="marca_id" id="marca_id" required>
                    <option value="">Seleccione una marca</option>
                    <?php while($marca = $marcas->fetch_assoc()): ?>
                    <option value="<?= $marca['marca_id'] ?>" 
                        <?= $marca['marca_id'] == $vehiculo['marca_id'] ? 'selected' : '' ?>>
                        <?= $marca['nombre'] ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" name="modelo" id="modelo" 
                       value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" class="form-control" name="anio" id="anio" 
                       value="<?= $vehiculo['anio'] ?>" min="1900" max="<?= date('Y')+1 ?>" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" name="color" id="color" 
                       value="<?= htmlspecialchars($vehiculo['color']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio ($):</label>
                <input type="number" class="form-control" name="precio" id="precio" 
                       value="<?= $vehiculo['precio'] ?>" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" name="stock" id="stock" 
                       value="<?= $vehiculo['stock'] ?>" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="fecha_adquisicion">Fecha Adquisición:</label>
                <input type="date" class="form-control" name="fecha_adquisicion" id="fecha_adquisicion" 
                       value="<?= $vehiculo['fecha_adquisicion'] ?>" required>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="transmision">Transmisión:</label>
                <select class="form-control" name="transmision" id="transmision" required>
                    <option value="Automática" <?= $vehiculo['transmision'] == 'Automática' ? 'selected' : '' ?>>Automática</option>
                    <option value="Manual" <?= $vehiculo['transmision'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="tipo_combustible">Combustible:</label>
                <select class="form-control" name="tipo_combustible" id="tipo_combustible" required>
                    <option value="Gasolina" <?= $vehiculo['tipo_combustible'] == 'Gasolina' ? 'selected' : '' ?>>Gasolina</option>
                    <option value="Diésel" <?= $vehiculo['tipo_combustible'] == 'Diésel' ? 'selected' : '' ?>>Diésel</option>
                    <option value="Híbrido" <?= $vehiculo['tipo_combustible'] == 'Híbrido' ? 'selected' : '' ?>>Híbrido</option>
                    <option value="Eléctrico" <?= $vehiculo['tipo_combustible'] == 'Eléctrico' ? 'selected' : '' ?>>Eléctrico</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" name="estado" id="estado" required>
                    <option value="Nuevo" <?= $vehiculo['estado'] == 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
                    <option value="Usado" <?= $vehiculo['estado'] == 'Usado' ? 'selected' : '' ?>>Usado</option>
                    <option value="Certificado" <?= $vehiculo['estado'] == 'Certificado' ? 'selected' : '' ?>>Certificado</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="kilometraje">Kilometraje (km):</label>
        <input type="number" class="form-control" name="kilometraje" id="kilometraje" 
               value="<?= $vehiculo['kilometraje'] ?>" min="0">
    </div>
    
    <button type="submit" class="btn btn-primary">Actualizar Vehículo</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>