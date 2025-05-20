<?php
include 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = $_POST;
    
    try {
        $conexion->begin_transaction();
        
        // 1. Registrar la venta
        $stmt = $conexion->prepare("INSERT INTO Venta (
            cliente_id, empleado_id, vehiculo_id, 
            precio_final, metodo_pago, descuento
        ) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("iiidsd", 
            $datos['cliente_id'],
            $datos['empleado_id'],
            $datos['vehiculo_id'],
            $datos['precio_final'],
            $datos['metodo_pago'],
            $datos['descuento']
        );
        
        $stmt->execute();
        $venta_id = $conexion->insert_id;
        
        // 2. Registrar financiamiento si aplica
        if($datos['metodo_pago'] == 'Financiamiento') {
            $stmt = $conexion->prepare("INSERT INTO Financiamiento (
                venta_id, monto_financiado, plazo_meses, 
                tasa_interes, fecha_aprobacion, institucion_financiera
            ) VALUES (?, ?, ?, ?, CURDATE(), ?)");
            
            $stmt->bind_param("idids",
                $venta_id,
                $datos['monto_financiado'],
                $datos['plazo_meses'],
                $datos['tasa_interes'],
                $datos['institucion_financiera']
            );
            
            $stmt->execute();
        }
        
        // 3. Actualizar stock
        $conexion->query("UPDATE Vehiculo SET stock = stock - 1 WHERE vehiculo_id = {$datos['vehiculo_id']}");
        
        $conexion->commit();
        header("Location: ../ventas/index.php?success=Venta registrada correctamente&venta_id=$venta_id");
    } catch(Exception $e) {
        $conexion->rollback();
        header("Location: ../ventas/registrar.php?error=".urlencode($e->getMessage()));
    }
} else {
    header("Location: ../ventas/index.php");
}
?>