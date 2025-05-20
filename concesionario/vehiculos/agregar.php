<?php
include '../includes/conexion.php';
include '../includes/header.php';

// Obtener marcas para el select
$marcas = $conexion->query("SELECT * FROM Marca ORDER BY nombre");
?>

<h2>Agregar Nuevo Vehículo</h2>

<form action="../includes/registrar_vehiculo.php" method="POST">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="vin">VIN (Número de Chasis):</label>
                <input type="text" class="form-control" name="vin" id="vin" required 
                       pattern="[A-HJ-NPR-Z0-9]{17}" title="17 caracteres alfanuméricos" 
                       placeholder="Ej: JM1BL1W45B1234567">
                <small class="form-text text-muted">17 caracteres alfanuméricos (sin I, O, Q)</small>
            </div>
            
            <div class="form-group">
                <label for="marca_id">Marca:</label>
                <select class="form-control" name="marca_id" id="marca_id" required>
                    <option value="">Seleccione una marca</option>
                    <?php while($marca = $marcas->fetch_assoc()): ?>
                    <option value="<?= $marca['marca_id'] ?>"><?= $marca['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" name="modelo" id="modelo" required 
                       placeholder="Ej: Corolla, Civic, Mustang">
            </div>
            
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" class="form-control" name="anio" id="anio" 
                       min="1900" max="<?= date('Y')+1 ?>" required 
                       value="<?= date('Y') ?>">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" name="color" id="color" required 
                       placeholder="Ej: Rojo, Azul Marino">
            </div>
            
            <div class="form-group">
                <label for="precio">Precio ($):</label>
                <input type="number" class="form-control" name="precio" id="precio" 
                       step="0.01" min="0" required placeholder="25000.00">
            </div>
            
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" name="stock" id="stock" 
                       min="1" value="1" required>
            </div>
            
            <div class="form-group">
                <label for="fecha_adquisicion">Fecha de Adquisición:</label>
                <input type="date" class="form-control" name="fecha_adquisicion" id="fecha_adquisicion" 
                       required value="<?= date('Y-m-d') ?>">
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="transmision">Transmisión:</label>
                <select class="form-control" name="transmision" id="transmision" required>
                    <option value="Automática">Automática</option>
                    <option value="Manual">Manual</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="tipo_combustible">Combustible:</label>
                <select class="form-control" name="tipo_combustible" id="tipo_combustible" required>
                    <option value="Gasolina">Gasolina</option>
                    <option value="Diésel">Diésel</option>
                    <option value="Híbrido">Híbrido</option>
                    <option value="Eléctrico">Eléctrico</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" name="estado" id="estado" required>
                    <option value="Nuevo">Nuevo</option>
                    <option value="Usado">Usado</option>
                    <option value="Certificado">Certificado</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="kilometraje">Kilometraje (km):</label>
        <input type="number" class="form-control" name="kilometraje" id="kilometraje" 
               min="0" value="0">
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar Vehículo</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>