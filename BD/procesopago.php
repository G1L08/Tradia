<?php
session_start();
include('C.php'); 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida o carrito vacío.']);
    exit();
}

$productos_en_carrito = $_SESSION['carrito'];
$errores = [];

$conn->begin_transaction(); 

try {
    foreach ($productos_en_carrito as $id_producto => $item) {
        $cantidad_comprada = (int)$item['cantidad'];
       
        $sql_select = "SELECT cantidad_stock FROM productos WHERE id_producto = ? FOR UPDATE";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id_producto);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $stock_actual = (int)$row['cantidad_stock'];
            $nuevo_stock = $stock_actual - $cantidad_comprada;
            
            if ($nuevo_stock < 0) {
                $errores[] = "Stock insuficiente para {$item['nombre']}.";
                throw new Exception("Stock insuficiente."); 
            }
            
          
            $sql_update = "UPDATE productos SET cantidad_stock = ? WHERE id_producto = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $nuevo_stock, $id_producto);
            $stmt_update->execute();
            
            if ($stmt_update->affected_rows === 0) {
                $errores[] = "Error al actualizar stock para ID: {$id_producto}.";
                throw new Exception("Error al actualizar stock.");
            }
            $stmt_update->close();
        } else {
            $errores[] = "Producto ID: {$id_producto} no encontrado.";
            throw new Exception("Producto no encontrado."); 
        }
        $stmt_select->close();
    }
    
   
    $conn->commit(); 
    
 
    unset($_SESSION['carrito']);
    unset($_SESSION['pago_total']); 
    
    echo json_encode(['success' => true, 'message' => 'Pago procesado correctamente.']);

} catch (Exception $e) {
    
    $conn->rollback(); 
    
    $error_msg = !empty($errores) ? implode('; ', $errores) : 'Fallo en la transacción.';
    
    echo json_encode(['success' => false, 'message' => 'Error al procesar el pedido. Revertido. Detalles: ' . $error_msg]);
}

if (isset($conn)) {
    $conn->close();
}
?>