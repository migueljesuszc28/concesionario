<?php
header('Content-Type: application/json');
include 'conexion.php';

$response = [
    'totalVehiculos' => 0,
    'totalClientes' => 0,
    'ventasMes' => 0,
    'ultimasVentas' => []
];

// Contar vehículos disponibles
$query = "SELECT COUNT(*) as total FROM Vehiculo WHERE stock > 0";
$result = $conexion->query($query);
$response['totalVehiculos'] = $result->fetch_assoc()['total'];

// Contar clientes activos
$query = "SELECT COUNT(*) as total FROM Cliente WHERE estado = 1";
$result = $conexion->query($query);
$response['totalClientes'] = $result->fetch_assoc()['total'];

// Ventas del mes actual
$query = "SELECT COUNT(*) as total FROM Venta 
          WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE()) 
          AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())";
$result = $conexion->query($query);
$response['ventasMes'] = $result->fetch_assoc()['total'];

// Últimas 5 ventas
$query = "SELECT v.fecha_venta, c.nombre as cliente, ve.modelo as vehiculo, v.precio_final as total
          FROM Venta v
          JOIN Cliente c ON v.cliente_id = c.cliente_id
          JOIN Vehiculo ve ON v.vehiculo_id = ve.vehiculo_id
          ORDER BY v.fecha_venta DESC LIMIT 5";
$result = $conexion->query($query);

while($row = $result->fetch_assoc()) {
    $response['ultimasVentas'][] = [
        'fecha' => date('d/m/Y', strtotime($row['fecha_venta'])),
        'cliente' => $row['cliente'],
        'vehiculo' => $row['vehiculo'],
        'total' => $row['total']
    ];
}

echo json_encode($response);
?>