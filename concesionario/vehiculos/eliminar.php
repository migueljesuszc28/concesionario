<?php
include '../../includes/conexion.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Verificar si el vehículo tiene ventas asociadas
        $check = $conexion->query("SELECT COUNT(*) as total FROM Venta WHERE vehiculo_id = $id");
        $result = $check->fetch_assoc();
        
        if($result['total'] > 0) {
            header("Location: ../index.php?error=No se puede eliminar el vehículo porque tiene ventas asociadas");
            exit;
        }

        // Eliminar el vehículo
        $stmt = $conexion->prepare("DELETE FROM Vehiculo WHERE vehiculo_id = ?");
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            header("Location: ../index.php?success=Vehículo eliminado correctamente");
        } else {
            header("Location: ../index.php?error=Error al eliminar el vehículo");
        }
    } catch(Exception $e) {
        header("Location: ../index.php?error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: ../index.php");
}
?>