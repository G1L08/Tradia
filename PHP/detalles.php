<?php
session_start();
include('../BD/C.php'); 

$id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$producto = null;

if ($id_producto > 0) {
   
    $sql = "
        SELECT 
            p.id_producto, p.nombre_producto, p.precio_unitario, p.ruta_archivo,
            p.descripcion, p.marca, p.cantidad_stock, p.tamano, 
            p.color, p.presentacion, p.utilidad, d.porcentaje_descuento 
        FROM 
            productos p
        LEFT JOIN
            descuentos d ON p.id_producto = d.id_producto
            AND d.activo = 1
            AND CURDATE() BETWEEN d.fecha_inicio AND d.fecha_fin
        WHERE
            p.id_producto = ?
        LIMIT 1
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $producto = $result->fetch_assoc();
            $precio_base = (float)$producto['precio_unitario'];
            $porcentaje_descuento = (float)($producto['porcentaje_descuento'] ?? 0);
            $precio_final = $precio_base;
            $hay_descuento = false;
            $minima_compra = (int)$producto['utilidad'] > 0 ? (int)$producto['utilidad'] : 1; 

            if ($porcentaje_descuento > 0) {
                $precio_final = $precio_base * (1 - ($porcentaje_descuento / 100));
                $precio_final = round($precio_final, 2);
                $hay_descuento = true;
            }

            $precio_base_formato = number_format($precio_base, 2);
            $precio_final_formato = number_format($precio_final, 2);
        } else {
            die("Error: Producto no encontrado.");
        }
        $stmt->close();
    } else {
        die("Error de preparación de consulta: " . $conn->error);
    }
} else {
    die("Error: ID de producto no especificado o inválido.");
}

if (isset($_POST['add_to_cart_detail']) && $producto) {
    $id_producto_a_agregar = $producto['id_producto'];
    $cantidad_a_agregar = (int)($_POST['cantidad'] ?? 1);
    $stock_disponible = (int)$producto['cantidad_stock'];
    
    if ($cantidad_a_agregar < $minima_compra) {
        $error_mensaje = "Error: Debes comprar al menos " . $minima_compra . " unidades.";
        echo '<script>alert("' . $error_mensaje . '"); window.history.back();</script>';
        exit();
    }
    
    if ($cantidad_a_agregar > $stock_disponible) {
        $error_mensaje = "Error: Stock insuficiente. Solo quedan " . $stock_disponible . " unidades.";
        echo '<script>alert("' . $error_mensaje . '"); window.history.back();</script>';
        exit();
    }

    $nombre_producto_a_agregar = htmlspecialchars($producto['nombre_producto']);
    $precio_final_a_agregar = $precio_final;
    
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    if (array_key_exists($id_producto_a_agregar, $_SESSION['carrito'])) {
        $cantidad_en_carrito_actual = $_SESSION['carrito'][$id_producto_a_agregar]['cantidad'];
        $nueva_cantidad_total = $cantidad_en_carrito_actual + $cantidad_a_agregar;
        
        $cantidad_final_a_guardar = min($nueva_cantidad_total, $stock_disponible);
        
        $_SESSION['carrito'][$id_producto_a_agregar]['cantidad'] = $cantidad_final_a_guardar;
    } else {
        $_SESSION['carrito'][$id_producto_a_agregar] = array(
            'id' => $id_producto_a_agregar,
            'nombre' => $nombre_producto_a_agregar,
            'precio' => $precio_final_a_agregar,
            'cantidad' => $cantidad_a_agregar
        );
    }

    echo '<script>alert("Producto agregado al carrito con éxito."); location.href = "carrito.php";</script>';
    exit();
}

if (isset($conn)) {
    $conn->close();
}

$nombre = htmlspecialchars($producto['nombre_producto']);
$descripcion_full = htmlspecialchars($producto['descripcion'] ?? 'Sin descripción detallada.');
$stock = (int)$producto['cantidad_stock'];
$imagen_url = $producto['ruta_archivo'] ? $producto['ruta_archivo'] : '../Multimedia/placeholder_image.png';
$medidas = htmlspecialchars($producto['tamano'] ?? 'N/A');
$marca_producto = htmlspecialchars($producto['marca'] ?? 'N/A'); 
$color_producto = htmlspecialchars($producto['color'] ?? 'N/A');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEANS - Detalle de <?php echo $nombre; ?></title>
    <link rel="stylesheet" href="../CSS/detalles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="header">
        <a href="Panelusuario.php" class="logo-link">
            <h1>TRADIA</h1>
            <h2>tu tienda mayorista</h2>
        </a>
        <nav class="nav-menu">
            <a href="carrito.php" class="btn-carrito-view">
                <i class="fa-solid fa-cart-shopping"></i> Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)
            </a>
            <a href="../PHP/Panelusuario.php" class="btn-salir">Salir</a>
        </nav>
    </header>

    <main class="product-detail-container">
        <div class="product-presentation-box">

            <div class="product-image-section">
                <img src="<?php echo $imagen_url; ?>" alt="<?php echo $nombre; ?>" class="main-product-image">
            </div>

            <div class="product-info-section">

                <div class="product-header-detail">
                    <h2 class="product-title"><?php echo $nombre; ?></h2>
                    <span class="product-price-display">
                        $
                        <?php if ($hay_descuento): ?>
                            <span class="final-price"><?php echo $precio_final_formato; ?></span>
                            <span class="original-price"><del>$<?php echo $precio_base_formato; ?></del></span>
                            <span class="discount-badge">-<?php echo (int)$porcentaje_descuento; ?>%</span>
                        <?php else: ?>
                            <span class="final-price"><?php echo $precio_base_formato; ?></span>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="color-options">
                    <p class="label">COLORES DISPONIBLES:</p>
                    <span class="color-swatch beige-selected" title="<?php echo $color_producto; ?>"></span>
                    <span class="color-swatch dark-grey" title="Gris Oscuro"></span>
                </div>

                <p class="product-description-short">
                    <?php echo $descripcion_full; ?>
                </p>

                <h3 class="info-title">Información General</h3>
                <div class="info-tabs">
                    <button class="tab-button active" onclick="showTab('producto')">Producto</button>
                    <button class="tab-button" onclick="showTab('stock')">Stock</button>
                </div>

                <div class="tab-content active" id="tab-producto">
                    <table>
                        <tr>
                            <td>Marca</td> <td><?php echo $marca_producto; ?></td>
                        </tr>
                        <tr>
                            <td>Medidas</td>
                            <td><?php echo $medidas; ?></td>
                        </tr>
                        <tr>
                            <td>Cantidad Mínima </td> <td><?php echo $minima_compra; ?> pieza(s)</td>
                        </tr>
                        </table>
                </div>

                <div class="tab-content" id="tab-stock" style="display:none;">
                    <p>
                        <strong>Unidades en Stock:</strong>
                        <span class="stock-count <?php echo ($stock > 10) ? 'high-stock' : (($stock > 0) ? 'low-stock' : 'out-of-stock'); ?>">
                            <?php echo $stock > 0 ? $stock . ' unidades' : 'Agotado'; ?>
                        </span>
                    </p>
                    <?php if ($minima_compra > 1): ?>
                    <p class="nota-minima">
                        * Cantidad mínima de compra: 
                        <strong><?php echo $minima_compra; ?></strong> unidades.
                    </p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="detalles.php?id=<?php echo $id_producto; ?>" class="purchase-actions" id="form-agregar-carrito">
                    <input type="hidden" name="product_id" value="<?php echo $id_producto; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $nombre; ?>">
                    <input type="hidden" name="final_price" value="<?php echo $precio_final; ?>">
                    
                    <div class="cart-controls">
                        <input type="number" 
                               name="cantidad" 
                               id="cantidad_a_comprar"
                               value="<?php echo $minima_compra; ?>" 
                               min="<?php echo $minima_compra; ?>" 
                               max="<?php echo $stock; ?>" 
                               class="quantity-input" 
                               data-minima-requerida="<?php echo $minima_compra; ?>"
                               <?php echo $stock < $minima_compra ? 'disabled' : ''; ?>>
                               
                        <button type="submit" name="add_to_cart_detail" class="btn-agregar-carrito" <?php echo $stock < $minima_compra ? 'disabled' : ''; ?>>
                            Agregar a carrito
                        </button>
                    </div>
                </form>
                
                <div class="edit-action">
                    <a href="editarproducto.php?id=<?php echo $id_producto; ?>" class="btn-editar-producto">
                        <i class="fa-solid fa-pen-to-square"></i> Modificar Producto
                    </a>
                </div>

            </div>
        </div>
    </main>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            document.getElementById('tab-' + tabId).style.display = 'block';
            document.querySelector(`.info-tabs .tab-button[onclick*="'${tabId}'"]`).classList.add('active');
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            showTab('producto');
            
            const form = document.getElementById('form-agregar-carrito');
            const cantidadInput = document.getElementById('cantidad_a_comprar');
            
            if (form && cantidadInput) {
                const minimaRequerida = parseInt(cantidadInput.getAttribute('data-minima-requerida'), 10);
                const stockMax = parseInt(cantidadInput.getAttribute('max'), 10);

                form.addEventListener('submit', function(event) {
                    let cantidadComprar = parseInt(cantidadInput.value, 10);
                    
                    if (isNaN(cantidadComprar) || cantidadComprar < 1) {
                         cantidadComprar = minimaRequerida;
                    }
                    
              
                    if (cantidadComprar < minimaRequerida) {
                        event.preventDefault();
                        alert(`Error: La cantidad mínima para comprar este producto es de ${minimaRequerida}.`);
                        cantidadInput.value = minimaRequerida;
                        return;
                    }
                    
                 
                    if (cantidadComprar > stockMax) {
                        event.preventDefault(); 
                        alert(`Error: Solo hay ${stockMax} unidades en stock.`);
                        cantidadInput.value = stockMax;
                        return;
                    }

                    cantidadInput.value = cantidadComprar;
                });
            }
        });
    </script>
</body>

</html>