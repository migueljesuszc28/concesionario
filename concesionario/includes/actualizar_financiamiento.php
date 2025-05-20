<?php
include 'conexion.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $financiamiento_id = intval($_POST['financiamiento_id'] ?? 0);
    $estado = in_array($_POST['estado'] ?? '', ['Aprobado', 'Rechazado']) ? $_POST['estado'] : '';
    
    try {
        if($financiamiento_id <= 0 || empty($estado)) {
            throw new Exception("Datos inválidos");
        }
        
        $conexion->begin_transaction();
        
        // 1. Actualizar financiamiento
        $stmt = $conexion->prepare("UPDATE Financiamiento SET estado = ? WHERE financiamiento_id = ?");
        $stmt->bind_param("si", $estado, $financiamiento_id);
        $stmt->execute();
        
        // 2. Si se rechaza, actualizar método de pago de la venta
        if($estado == 'Rechazado') {
            $conexion->query("UPDATE Venta SET metodo_pago = 'Efectivo' 
                             WHERE venta_id = (
                                 SELECT venta_id FROM Financiamiento 
                                 WHERE financiamiento_id = $financiamiento_id
                             )");
        }
        
        $conexion->commit();
        
        $response = [
            'success' => true,
            'message' => "Financiamiento marcado como $estado correctamente"
        ];
        
    } catch(Exception $e) {
        $conexion->rollback();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>