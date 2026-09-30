<?php
include('../BD/C.php');

$sql_listado = "
SELECT 
    p.nombre_producto, 
    p.marca,
    p.tipo,
    p.utilidad,
    p.presentacion,
    p.tamano,
    p.color,
    p.forma,
    p.cantidad_stock,
    p.precio_unitario,
    p.descripcion,
    d.porcentaje_descuento,
    (p.precio_unitario - (p.precio_unitario * IFNULL(d.porcentaje_descuento, 0) / 100)) AS precio_con_descuento
FROM 
    Productos p
LEFT JOIN 
    Descuentos d ON p.id_producto = d.id_producto AND d.activo = TRUE 
    AND CURDATE() BETWEEN d.fecha_inicio AND d.fecha_fin
ORDER BY 
    p.id_producto DESC;
";

$result_listado = $conn->query($sql_listado);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos Registrados</title>
    <link rel="stylesheet" href="../CSS/panelProducto.css">
</head>

<body>

    <h2>Inventario de Productos</h2>

    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alerta-exito">Operación realizada exitosamente.</div>
    <?php endif; ?>

    <?php
    if ($result_listado->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Utilidad</th>
                <th>Presentación</th>
                <th>Tamaño</th>
                <th>Color</th>
                <th>Forma</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Descripción</th>
              </tr></thead>";
        echo "<tbody>";


        while ($row = $result_listado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nombre_producto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['marca']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['utilidad']) . "</td>";
            echo "<td>" . htmlspecialchars($row['presentacion']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tamano']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['forma']) . "</td>";


            echo "<td>";
            if ($row['porcentaje_descuento'] > 0) {

                echo "<span class='precio-original'>$" . number_format($row['precio_unitario'], 2) . "</span>";
                echo "<span class='precio-oferta'>$" . number_format($row['precio_con_descuento'], 2) . "</span>";
            } else {

                echo "$" . number_format($row['precio_unitario'], 2);
            }
            echo "</td>";

            echo "<td>" . htmlspecialchars($row['cantidad_stock']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No se encontraron productos registrados.</p>";
    }


    $conn->close();
    ?>

</body>

</html>