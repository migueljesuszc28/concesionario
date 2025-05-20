<?php
header('Content-Type: application/json');
include 'conexion.php';

$response = ['success' => false, 'message' => 'Método no permitido'];

if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $tipo = $_GET['tipo'] ?? '';
    $id = intval($_GET['id'] ?? 0);
    
    if(!in_array($tipo, ['vehiculo', 'cliente']) || $id <= 0) {
        $response['message'] = 'Parámetros inválidos';
        echo json_encode($response);
        exit;
    }

    try {
        $conexion->begin_transaction();
        
        // Verificar si el vehículo tiene ventas asociadas
        if($tipo == 'vehiculo') {
            $check = $conexion->query("SELECT COUNT(*) as total FROM Venta WHERE vehiculo_id = $id");
            $result = $check->fetch_assoc();
            
            if($result['total'] > 0) {
                throw new Exception('No se puede eliminar, el vehículo tiene ventas asociadas');
            }
            
        }
        
        $tabla = ucfirst($tipo); // Convierte a Vehiculo o Cliente
        $stmt = $conexion->prepare("DELETE FROM $tabla WHERE {$tipo}_id = ?");
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            
            $conexion->commit();
            $response = [
                'success' => true,
                'message' => ucfirst($tipo) . ' eliminado correctamente'
            ];
        } else {
            throw new Exception($conexion->error);
        }
    } catch(Exception $e) {
        $conexion->rollback();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>