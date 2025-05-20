<?php
include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = $_POST;
    
    try {
        // Actualizar vehículo
        $stmt = $conexion->prepare("UPDATE Vehiculo SET 
            marca_id = ?,
            modelo = ?,
            anio = ?,
            color = ?,
            transmision = ?,
            tipo_combustible = ?,
            kilometraje = ?,
            precio = ?,
            stock = ?,
            estado = ?,
            fecha_adquisicion = ?
            WHERE vehiculo_id = ?");
        
        $stmt->bind_param("issssssdissi",
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
            $datos['fecha_adquisicion'],
            $datos['vehiculo_id']
        );
        
        if($stmt->execute()) {
            header("Location: ../vehiculos/index.php?success=Vehículo actualizado correctamente");
        } else {
            throw new Exception($conexion->error);
        }
    } catch(Exception $e) {
        header("Location: ../vehiculos/editar.php?id=".$datos['vehiculo_id']."&error=".urlencode($e->getMessage()));
    }
} else {
    header("Location: ../vehiculos/index.php");
}
?>