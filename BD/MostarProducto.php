<?php

include('C.php');

if (isset($_POST['registrar'])) {


    $nombre_producto    = $_POST['nombre_producto'];
    $marca              = $_POST['marca'];
    $tipo               = $_POST['tipo'] ?? null;
    $utilidad           = $_POST['utilidad'] ?? null;
    $presentacion       = $_POST['presentacion'] ?? null;
    $tamano             = $_POST['tamano'] ?? null;
    $color              = $_POST['color'] ?? null;
    $forma              = $_POST['forma'] ?? null;
    $cantidad_stock     = (int)$_POST['cantidad_stock'];
    $precio_unitario    = (float)$_POST['precio_unitario'];
    $fecha_elaboracion  = !empty($_POST['fecha_elaboracion']) ? $_POST['fecha_elaboracion'] : null;
    $fecha_caducidad    = !empty($_POST['fecha_caducidad']) ? $_POST['fecha_caducidad'] : null;
    $descripcion        = $_POST['descripcion'] ?? null;
    $usuario_registro   = $_POST['usuario_registro'] ?? 'Sistema';

    $sql_insert = "
        INSERT INTO Productos (
            nombre_producto, marca, tipo, utilidad, presentacion, tamano, color, forma, 
            cantidad_stock, precio_unitario, fecha_ingreso, fecha_elaboracion, fecha_caducidad, 
            descripcion, usuario_registro
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, CURDATE(), ?, ?, 
            ?, ?
        )
    ";


    if ($stmt = $conn->prepare($sql_insert)) {


        $stmt->bind_param(
            "ssssssssidsddss",
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
            $fecha_elaboracion,
            $fecha_caducidad,
            $descripcion,
            $usuario_registro
        );


        if ($stmt->execute()) {

            header("Location: productos.php?status=success");
            exit();
        } else {

            header("Location: productos.php?status=error&msg=" . urlencode($stmt->error));
            exit();
        }


        $stmt->close();
    } else {

        header("Location: productos.php?status=error&msg=" . urlencode($conn->error));
        exit();
    }
}
