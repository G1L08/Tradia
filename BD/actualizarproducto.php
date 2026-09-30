<?php
include('BD/C.php'); 

$target_dir = "../uploads/Usados/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $ruta_archivo = isset($_POST['ruta_archivo_actual']) ? $_POST['ruta_archivo_actual'] : '../img/placeholder.png'; 
    $ruta_anterior = $ruta_archivo; 

    if ($id_producto <= 0) { 
        echo '<script>alert("Error: ID de producto no proporcionado o inválido."); location.href = "../PHP/Panelusuario.php";</script>';
        exit;
    }

    
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        $nombre_original = basename($_FILES["imagen_producto"]["name"]);
        $nombre_seguro = uniqid() . "_" . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $nombre_original);
        $ruta_destino = $target_dir . $nombre_seguro;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["imagen_producto"]["tmp_name"], $ruta_destino)) {
            $ruta_archivo = $ruta_destino;
            
            
            $placeholder_path = '../img/placeholder.png'; 
            if ($ruta_anterior != $placeholder_path && file_exists($ruta_anterior)) {
                @unlink($ruta_anterior);
            }
        }
    }

    
    $nombre_producto = isset($_POST['nombre_producto']) ? trim($_POST['nombre_producto']) : '';
    $marca           = isset($_POST['marca']) ? trim($_POST['marca']) : '';
    $tipo            = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    
    
    $utilidad        = isset($_POST['utilidad']) ? (int)$_POST['utilidad'] : 1;  
    $pres_cant       = isset($_POST['presentacion_cantidad']) ? $_POST['presentacion_cantidad'] : '';
    $pres_unid       = isset($_POST['presentacion_unidad']) ? $_POST['presentacion_unidad'] : '';
    $presentacion    = trim($pres_cant . ' ' . $pres_unid);
    
    
    $tam_val         = isset($_POST['tamano_valor']) ? $_POST['tamano_valor'] : '';
    $tam_unid        = isset($_POST['tamano_unidad']) ? $_POST['tamano_unidad'] : '';
    $tamano          = trim($tam_val . ' ' . $tam_unid);
    
    $color           = isset($_POST['color']) ? $_POST['color'] : '';
    $forma           = isset($_POST['forma']) ? $_POST['forma'] : '';
    $cantidad_stock  = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
    $precio_unitario = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.0;
    $fecha_ingreso   = !empty($_POST['fechaIngreso']) ? $_POST['fechaIngreso'] : date('Y-m-d');
    $fecha_elaboracion = !empty($_POST['fechaElaboracion']) ? $_POST['fechaElaboracion'] : null;
    $fecha_caducidad = !empty($_POST['fechaCaducidad']) ? $_POST['fechaCaducidad'] : null;
    $descripcion     = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    
    
    $porcentaje_descuento = isset($_POST['porcentaje_descuento']) ? (float)$_POST['porcentaje_descuento'] : 0.0;
    $fecha_inicio_desc    = !empty($_POST['fecha_inicio_descuento']) ? $_POST['fecha_inicio_descuento'] : null;
    $fecha_fin_desc       = !empty($_POST['fecha_fin_descuento']) ? $_POST['fecha_fin_descuento'] : null;

    $activo_descuento = ($porcentaje_descuento > 0 && $fecha_inicio_desc && $fecha_fin_desc) ? 1 : 0;

    if ($utilidad > $cantidad_stock) {
        echo '<script>alert("Error: La Cantidad Mínima para Compra no puede ser mayor que la Cantidad en Stock."); location.href = "../PHP/editarproducto.php?id=' . $id_producto . '";</script>';
        exit;
    }
 
    if (empty($nombre_producto) || empty($marca) || $cantidad_stock < 0 || $precio_unitario < 0 || $utilidad < 1) {
        
        if ($utilidad < 1) {
            echo '<script>alert("Error: La cantidad mínima para compra debe ser al menos 1."); location.href = "../PHP/editarproducto.php?id=' . $id_producto . '";</script>';
        } else {
            echo '<script>alert("Error: Faltan datos obligatorios o son inválidos (stock/precio)."); location.href = "../PHP/editarproducto.php?id=' . $id_producto . '";</script>';
        }
        exit;
    }

    $sql_update = "UPDATE Productos SET
        ruta_archivo = ?, nombre_producto = ?, marca = ?, tipo = ?, utilidad = ?, presentacion = ?, tamano = ?, color = ?, forma = ?, cantidad_stock = ?, precio_unitario = ?, fecha_ingreso = ?, fecha_elaboracion = ?, fecha_caducidad = ?, descripcion = ?
        WHERE id_producto = ?";

    $stmt = $conn->prepare($sql_update);

    if (!$stmt) {
        die("Error preparando la consulta de actualización: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssidssssi",
        $ruta_archivo, $nombre_producto, $marca, $tipo, $utilidad, $presentacion, $tamano, $color, $forma, $cantidad_stock, $precio_unitario, $fecha_ingreso, $fecha_elaboracion, $fecha_caducidad, $descripcion, $id_producto
    );

    try {
        if ($stmt->execute()) {
            $mensaje = "Producto actualizado correctamente.";

        
            $stmt_deactivate = $conn->prepare("UPDATE descuentos SET activo = 0 WHERE id_producto = ?");
            $stmt_deactivate->bind_param("i", $id_producto);
            $stmt_deactivate->execute();
            $stmt_deactivate->close();
            
            if ($activo_descuento) {
                $stmt_desc = $conn->prepare("INSERT INTO descuentos (id_producto, porcentaje_descuento, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?, ?)");
                $stmt_desc->bind_param("idssi", $id_producto, $porcentaje_descuento, $fecha_inicio_desc, $fecha_fin_desc, $activo_descuento);

                if ($stmt_desc->execute()) {
                    $mensaje .= " El descuento ha sido actualizado/añadido.";
                } else {
                    $mensaje .= " Advertencia: No se pudo actualizar el descuento.";
                }
                $stmt_desc->close();
            } else {
            $mensaje .= " Descuento desactivado.";
            }

        
            $redirect_url = "../PHP/detalles.php?id=" . $id_producto; 
            echo '<script>alert("' . $mensaje . '"); location.href = "' . $redirect_url . '";</script>';

        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {

        echo '<script>alert("Error al actualizar en la base de datos: ' . addslashes($e->getMessage()) . '"); location.href = "../PHP/editarproducto.php?id=' . $id_producto . '";</script>';
    }

    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?>