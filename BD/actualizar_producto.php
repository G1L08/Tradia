<?php
require_once 'C.php';

function getDBConnection()
{
    $conn = new mysqli('localhost', 'root', '', 'tradia');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDBConnection();
    $conn->autocommit(FALSE);

    $id_producto = $_POST['id_producto'] ?? 0;
    $nombre_producto = $_POST['nombre_producto'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $utilidad = $_POST['utilidad'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;
    $precio = $_POST['precio'] ?? 0.00;
    $presentacion_cantidad = $_POST['presentacion_cantidad'] ?? null;
    $presentacion_unidad = $_POST['presentacion_unidad'] ?? null;
    $forma = $_POST['forma'] ?? null;
    $color = $_POST['color'] ?? null;
    $fechaIngreso = $_POST['fechaIngreso'] ?? date('Y-m-d');
    $fechaElaboracion = empty($_POST['fechaElaboracion']) ? null : $_POST['fechaElaboracion'];
    $fechaCaducidad = empty($_POST['fechaCaducidad']) ? null : $_POST['fechaCaducidad'];
    $descripcion = $_POST['descripcion'] ?? '';

    $sql_update_producto = "UPDATE productos SET
    nombre_producto = ?, marca = ?, tipo = ?, utilidad = ?, presentacion = ?,
    tamano = ?, color = ?, forma = ?, cantidad_stock = ?, precio_unitario = ?,
    fecha_ingreso = ?, fecha_elaboracion = ?, fecha_caducidad = ?, descripcion = ?
    WHERE id_producto = ?";
   

    $stmt = $conn->prepare($sql_update_producto);
    if ($stmt === false) {
        $conn->rollback();
        echo "Error al preparar la consulta de producto: " . $conn->error;
        exit;
    }
    $stmt = $conn->prepare($sql_update_producto);
    if ($stmt === false) {
        $conn->rollback();
        echo "Error al preparar la consulta de producto: " . $conn->error;
        exit;
    }

    $stmt->bind_param(
        "ssssidssssssssi",
        $nombre_producto,
        $marca,
        $tipo,
        $utilidad,
        $cantidad,
        $precio,
        $presentacion_cantidad,
        $presentacion_unidad,
        $forma,
        $color,
        $fechaIngreso,
        $fechaElaboracion,
        $fechaCaducidad,
        $descripcion,
        $id_producto
    );

    $producto_exitoso = $stmt->execute();
    $stmt->close();

    $porcentaje_descuento = $_POST['porcentaje_descuento'] ?? null;
    $fecha_inicio_descuento = empty($_POST['fecha_inicio_descuento']) ? null : $_POST['fecha_inicio_descuento'];
    $fecha_fin_descuento = empty($_POST['fecha_fin_descuento']) ? null : $_POST['fecha_fin_descuento'];
    $descuento_exitoso = true;

    if ($porcentaje_descuento > 0 && !empty($fecha_inicio_descuento) && !empty($fecha_fin_descuento)) {
        $sql_update_descuento = "UPDATE descuentos SET
            porcentaje_descuento = ?, fecha_inicio = ?, fecha_fin = ?
            WHERE id_producto = ?";

        $stmt_desc_upd = $conn->prepare($sql_update_descuento);
        if ($stmt_desc_upd) {
            $stmt_desc_upd->bind_param("dssi", $porcentaje_descuento, $fecha_inicio_descuento, $fecha_fin_descuento, $id_producto);
            $stmt_desc_upd->execute();

            if ($stmt_desc_upd->affected_rows === 0) {
                $sql_insert_descuento = "INSERT INTO descuentos (id_producto, porcentaje_descuento, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
                $stmt_desc_ins = $conn->prepare($sql_insert_descuento);

                if ($stmt_desc_ins) {
                    $stmt_desc_ins->bind_param("idss", $id_producto, $porcentaje_descuento, $fecha_inicio_descuento, $fecha_fin_descuento);
                    $descuento_exitoso = $stmt_desc_ins->execute();
                    $stmt_desc_ins->close();
                } else {
                    $descuento_exitoso = false;
                }
            }
            $stmt_desc_upd->close();
        } else {
            $descuento_exitoso = false;
        }
    } else {
        $sql_delete_descuento = "DELETE FROM Descuentos WHERE id_producto = ?";
        $stmt_desc_del = $conn->prepare($sql_delete_descuento);
        if ($stmt_desc_del) {
            $stmt_desc_del->bind_param("i", $id_producto);
            $stmt_desc_del->execute();
            $stmt_desc_del->close();
        }
    }

    if ($producto_exitoso && $descuento_exitoso) {
        $conn->commit();
        header("Location: ../panel_productos.php?status=success_update");
        exit();
    } else {
        $conn->rollback();
        header("Location: ../editar_producto.php?id=" . $id_producto . "&status=error");
        exit();
    }

    $conn->close();
} else {
    header("Location: ../panel_productos.php");
    exit();
}
