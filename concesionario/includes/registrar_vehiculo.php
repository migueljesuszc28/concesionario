<?php
include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = $_POST;
    
    try {
        // Validar VIN único
        $vin = $conexion->real_escape_string($datos['vin']);
        $check = $conexion->query("SELECT COUNT(*) as total FROM Vehiculo WHERE vin = '$vin'");
        if($check->fetch_assoc()['total'] > 0) {
            throw new Exception("El VIN ya existe en el sistema");
        }
        
        // Insertar vehículo
        $stmt = $conexion->prepare("INSERT INTO Vehiculo (
            vin, marca_id, modelo, anio, color, transmision, 
            tipo_combustible, kilometraje, precio, stock, estado, fecha_adquisicion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("issssssdissi", 
            $datos['vin'],
            $datos['marca_id'],
            $datos['modelo'],
            $datos['anio'],
            $datos['color'],
            $datos['transmision'],
            $datos['tipo_combustible'],
            $datos['kilometraje'],
            $datos['precio'],
            $datos['stock'],
            $datos['estado'],
            $datos['fecha_adquisicion']
        );
        
        if($stmt->execute()) {
            header("Location: ../vehiculos/index.php?success=Vehículo registrado correctamente");
        } else {
            throw new Exception("Error en la base de datos: " . $conexion->error);
        }
    } catch(Exception $e) {
        $error = urlencode($e->getMessage());
        header("Location: ../vehiculos/agregar.php?error=$error");
    }
} else {
    header("Location: ../vehiculos/index.php");
}
?>