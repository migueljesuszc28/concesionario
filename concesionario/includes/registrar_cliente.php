<?php
include 'conexion.php';

$datos = $_POST;

try {
    $stmt = $conexion->prepare("INSERT INTO Cliente (tipo_cliente, nombre, apellidos, email, telefono, 
                               direccion, licencia_conducir) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", 
        $datos['tipo_cliente'],
        $datos['nombre'],
        $datos['apellidos'],
        $datos['email'],
        $datos['telefono'],
        $datos['direccion'],
        $datos['licencia_conducir']
    );
    
    if ($stmt->execute()) {
        header("Location: ../clientes/index.php?success=1");
    } else {
        header("Location: ../clientes/agregar.php?error=" . urlencode($conexion->error));
    }
} catch (Exception $e) {
    header("Location: ../clientes/agregar.php?error=" . urlencode($e->getMessage()));
}
?>