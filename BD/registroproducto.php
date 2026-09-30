<?php
include('C.php'); 

$target_dir = "../uploads/Usados/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ruta_archivo = '../img/placeholder.png';
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        $nombre_original = basename($_FILES["imagen_producto"]["name"]);
        $nombre_seguro = uniqid() . "_" . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $nombre_original);
        $ruta_destino = $target_dir . $nombre_seguro;

        if (move_uploaded_file($_FILES["imagen_producto"]["tmp_name"], $ruta_destino)) {
            $ruta_archivo = $ruta_destino;
        }
    }
   
    $nombre_producto = isset($_POST['nombre_producto']) ? trim($_POST['nombre_producto']) : '';
    $marca           = isset($_POST['marca']) ? trim($_POST['marca']) : '';
    $tipo            = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    
    $utilidad        = isset($_POST['utilidad']) ? (int)$_POST['utilidad'] : 1; 
    $utilidad        = $utilidad < 1 ? 1 : $utilidad;

    
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
    

    if (empty($nombre_producto) || empty($marca) || $cantidad_stock < 0 || $precio_unitario < 0 || $utilidad < 1) {
        
        $alert_message = ($utilidad < 1) 
                         ? "Error: La cantidad mínima para compra debe ser al menos 1." 
                         : "Error: Faltan datos obligatorios.";
        
        echo '<script>alert("' . $alert_message . '"); location.href = "../PHP/producto.php";</script>';
        exit;
    }
    
    
    $stmt_check = $conn->prepare("SELECT id_producto FROM Productos WHERE nombre_producto = ? AND marca = ?");
    $stmt_check->bind_param("ss", $nombre_producto, $marca);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo '<script>alert("El producto ya existe."); location.href = "../PHP/producto.php";</script>';
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();
    
    
    $sql_insert = "INSERT INTO Productos (
        ruta_archivo, nombre_producto, marca, tipo, utilidad, 
        presentacion, tamano, color, forma, cantidad_stock, 
        precio_unitario, fecha_ingreso, fecha_elaboracion, fecha_caducidad, descripcion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);

    if (!$stmt) {
        die("Error preparando la consulta: " . $conn->error);
    }
    
   
    $stmt->bind_param(
        "ssssisssssdssss", 
        $ruta_archivo,
        $nombre_producto,
        $marca,
        $tipo,
        $utilidad, 
        $presentacion,
        $tamano,
        $color,
        $forma,
        $cantidad_stock,
        $precio_unitario,
        $fecha_ingreso,
        $fecha_elaboracion,
        $fecha_caducidad,
        $descripcion
    );
    
    try {
        if ($stmt->execute()) {
            $ultimo_id = $conn->insert_id;
            $mensaje = "Producto registrado correctamente.";
            
          
            if ($activo_descuento) {
                $stmt_desc = $conn->prepare("INSERT INTO descuentos (id_producto, porcentaje_descuento, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?, ?)");
                $stmt_desc->bind_param("idssi", $ultimo_id, $porcentaje_descuento, $fecha_inicio_desc, $fecha_fin_desc, $activo_descuento);

                if ($stmt_desc->execute()) {
                    $mensaje .= " Con descuento añadido.";
                }
                $stmt_desc->close();
            }

            echo '<script>alert("' . $mensaje . '"); location.href = "../PHP/Panelusuario.php";</script>';
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        echo '<script>alert("Error al guardar en la base de datos: ' . addslashes($e->getMessage()) . '"); location.href = "../PHP/producto.php";</script>';
    }

    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?>