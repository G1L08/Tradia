<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id_a_eliminar = $_GET['id'];
    if (isset($_SESSION['carrito'][$id_a_eliminar])) {
        unset($_SESSION['carrito'][$id_a_eliminar]);
    }
    header("Location: carrito.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}

$total_carrito = 0;
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total_carrito += ($item['precio'] * $item['cantidad']);
    }
    $_SESSION['pago_total'] = $total_carrito; 
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEANS - Carrito de Compras</title>
    <link rel="stylesheet" href="../CSS/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="header">
        <h1>Carrito de Compras</h1>
        <button type="submit" class="btn-salir"><a href="../PHP/Panelusuario.php">Salir</a></button>
    </header>

    <div class="carrito-container">

        <div class="carrito-lista-wrapper">
            <h2>Resumen del Carrito</h2>

            <?php if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])): ?>
                <p>Tu carrito está vacío. ¡Añade algunos productos!</p>
            <?php else: ?>

                <div class="carrito-header carrito-item">
                    <div class="item-info">**Producto**</div>
                    <div class="item-qty">**Cantidad**</div>
                    <div class="item-price">**Precio Total**</div>
                    <div></div>
                </div>

                <?php foreach ($_SESSION['carrito'] as $id => $item): ?>
                    <div class="carrito-item">
                        <div class="item-info">
                            <?php echo htmlspecialchars($item['nombre']); ?>
                            (Precio Unitario: $<?php echo number_format($item['precio'], 2); ?>)
                        </div>
                        <div class="item-qty"><?php echo $item['cantidad']; ?></div>
                        <div class="item-price">$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></div>
                        <div>
                            <a href="carrito.php?action=remove&id=<?php echo $id; ?>" class="btn-remove">X</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
        <div class="carrito-resumen-columna">
            <?php if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
                <div class="carrito-total-box">

                    <div class="carrito-total">
                        Total a Pagar: **$<?php echo number_format($total_carrito, 2); ?>**
                    </div>

                    <div class="btn-actions">
                        <button class="btn-primary">
                            <a href="pago.php">Proceder al Pago</a>
                        </button>
                        <a href="carrito.php?action=clear" class="btn-secondary">Vaciar Carrito</a>
                    </div>
                </div> 
            <?php endif; ?>
        </div>
    </div>
    
   <img src="../Multimedia/ResgitroTradia.png" class="Video">

</body>

</html>