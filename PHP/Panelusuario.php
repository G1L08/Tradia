<?php
session_start();
include('../BD/C.php'); 

if (isset($_POST['add_to_cart'])) {
   
    header("Location: Panelusuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRADIA - Avisos</title>
    <link rel="stylesheet" href="../CSS/Usuarui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="header">
        <button type="submit" class="btn-agregar"><a href="../PHP/producto.php">Agregar</a></button>
        <h1>Tradia tu tienda mayorista</h1>
        <button type="button" class="btn-carrito-view">
            <a href="carrito.php">
                <i class="fa-solid fa-cart-shopping"></i> Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)
            </a>
        </button>
        <button type="submit" class="btn-salir"><a href="../PHP/login.php">Salir</a></button>
    </header>

    <section class="banner">
        <div class="banner-content">
            <div class="banner-text">
                <h2>Avisos</h2>
                <button class="btn-primary">Comprar Ahora</button>
            </div>
            <div class="banner-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>
    </section>

    <main class="product-grid-container">

        <?php
        $sql = "
            SELECT 
                p.id_producto, 
                p.nombre_producto, 
                p.precio_unitario,
                p.ruta_archivo,
                p.descripcion,
                p.marca,
                p.cantidad_stock, 
                d.porcentaje_descuento 
            FROM 
                productos p
            LEFT JOIN
                descuentos d ON p.id_producto = d.id_producto
                AND d.activo = 1
                AND CURDATE() BETWEEN d.fecha_inicio AND d.fecha_fin
            ORDER BY 
                p.id_producto DESC
        ";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($producto = $result->fetch_assoc()) {
                $id_producto = $producto['id_producto'];
                $nombre = htmlspecialchars($producto['nombre_producto']);
                $marca = htmlspecialchars($producto['marca'] ?? 'Genérico');
                $descripcion = htmlspecialchars($producto['descripcion'] ?? 'Sin descripción detallada.');
                $stock = $producto['cantidad_stock'];
                $imagen_url = $producto['ruta_archivo'] ? $producto['ruta_archivo'] : '../multimedia/placeholder_image.png';
                $precio_base = (float)$producto['precio_unitario'];
                $porcentaje_descuento = (float)$producto['porcentaje_descuento'];
                $precio_final = $precio_base;
                $hay_descuento = false;
                $es_agotado = ($stock <= 0); 

                if ($porcentaje_descuento > 0) {
                    $precio_final = $precio_base * (1 - ($porcentaje_descuento / 100));
                    $precio_final = round($precio_final, 2);
                    $hay_descuento = true;
                }

                $precio_base_formato = number_format($precio_base, 2);
                $precio_final_formato = number_format($precio_final, 2);
        ?>

                <div class="product-card <?php echo $es_agotado ? 'agotado' : ''; ?>">
                    <div class="product-header">
                        <i class="far fa-heart favorite-icon"></i>
                    </div>

                    <div class="product-image-container" style="cursor: pointer;"
                        onclick='abrirModal({
                            id: "<?php echo $id_producto; ?>", nombre: "<?php echo $nombre; ?>",
                            marca: "<?php echo $marca; ?>",
                            imagen: "<?php echo $imagen_url; ?>",
                            descripcion: <?php echo json_encode($descripcion); ?>,
                            stock: "<?php echo $stock; ?>",
                            precioBase: "<?php echo $precio_base_formato; ?>",
                            precioFinal: "<?php echo $precio_final_formato; ?>",
                            hayDescuento: <?php echo $hay_descuento ? "true" : "false"; ?>,
                            descuento: "<?php echo (int)$porcentaje_descuento; ?>"
                        })'>
                        <a href="../PHP/detalles.php?id=<?php echo $id_producto; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>
                        <img src="<?php echo $imagen_url; ?>" alt="<?php echo $nombre; ?>" class="product-image">
                    </div>

                    <div class="product-info">
                        <p class="product-name"><?php echo $nombre; ?></p>
                        <div class="product-rating">
                            <span class="stars">★★★★★</span>
                            <span class="reviews">(0)</span>
                        </div>

                        <div class="product-price-container">
                            <?php if ($hay_descuento): ?>
                                <span class="precio-original"><del>$<?php echo $precio_base_formato; ?></del></span>
                                <span class="precio-descuento">$<?php echo $precio_final_formato; ?></span>
                                <span class="badge-descuento">-<?php echo (int)$porcentaje_descuento; ?>%</span>
                            <?php else: ?>
                                <span class="product-price">$<?php echo $precio_base_formato; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="display: flex; gap: 5px; margin-top: 10px;">
                        
                        <?php if (!$es_agotado): ?>
                            <form method="POST" action="Panelusuario.php" style="flex: 1;">
                                <input type="hidden" name="product_id" value="<?php echo $id_producto; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $nombre; ?>">
                                <input type="hidden" name="final_price" value="<?php echo $precio_final; ?>">
                                <button type="submit" name="add_to_cart" class="btn-secondary" style="margin-top:0;">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn-agotado" disabled style="flex: 1.5; margin-top:0;">
                                <i class="fa-solid fa-box-open"></i> Agotado
                            </button>
                        <?php endif; ?>

                        <button class="btn-secondary" style="flex: 3; margin-top:0;"
                            onclick='abrirModal({ /* ... */ })'>
                            <a href="../PHP/detalles.php?id=<?php echo $id_producto; ?>">Ver Detalles</a>
                        </button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty-message">Actualmente no hay productos cargados o la conexión falló.</p>';
        }

        if (isset($conn)) {
            $conn->close();
        }
        ?>

    </main>
</body>

</html>