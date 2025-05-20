<?php
include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = $_POST;
    
    try {
        $stmt = $conexion->prepare("UPDATE Cliente SET 
            tipo_cliente = ?,
            nombre = ?,
            apellidos = ?,
            email = ?,
            telefono = ?,
            direccion = ?,
            licencia_conducir = ?,
            estado = ?
            WHERE cliente_id = ?");
            
        $stmt->bind_param("sssssssii",
            $datos['tipo_cliente'],
            $datos['nombre'],
            $datos['apellidos'],
            $datos['email'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['licencia_conducir'],
            $datos['estado'],
            $datos['cliente_id']
        );
        
        if($stmt->execute()) {
            header("Location: ../clientes/index.php?success=Cliente actualizado correctamente");
        } else {
            header("Location: ../clientes/editar.php?id=".$datos['cliente_id']."&error=".urlencode($conexion->error));
        }
    } catch(Exception $e) {
        header("Location: ../clientes/editar.php?id=".$datos['cliente_id']."&error=".urlencode($e->getMessage()));
    }
} else {
    header("Location: ../clientes/index.php");
}
?>