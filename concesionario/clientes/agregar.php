<?php
include '../includes/conexion.php';
include '../includes/header.php';
?>

<h2>Agregar Nuevo Cliente</h2>

<form class="formularios" action="../includes/registrar_cliente.php" method="POST">
    <div>
        <label for="tipo_cliente">Tipo de Cliente:</label>
        <select name="tipo_cliente" id="tipo_cliente" required>
            <option value="Persona">Persona</option>
            <option value="Empresa">Empresa</option>
        </select>
    </div>
    
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
    </div>
    
    <div>
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos">
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>
    
    <div>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required>
    </div>
    
    <div>
        <label for="direccion">Dirección:</label>
        <textarea name="direccion" id="direccion" rows="3"></textarea>
    </div>
    
    <div>
        <label for="licencia_conducir">Licencia de Conducir:</label>
        <input type="text" name="licencia_conducir" id="licencia_conducir">
    </div>
    
    <button type="submit" class="btn">Registrar Cliente</button>
</form>

<?php include '../includes/footer.php'; ?>