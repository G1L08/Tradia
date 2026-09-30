<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - MEANS</title>
    <link rel="stylesheet" href="../CSS/confirmacion.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
   <img src="../Multimedia/ResgitroTradia.png" class="Video">

    <div class="checkout-container">
        <div class="confirmation-box">
            <i class="fas fa-check-circle"></i>

            <h2>¡Pedido Confirmado!</h2>

            <p>
                Tu compra ha sido procesada con éxito. 
                Recibirás una confirmación por correo electrónico y el seguimiento del pedido en breve.
            </p>
            
            <p>
                **Número de Pedido:** **#<?php echo rand(100000, 999999); ?>** </p>

            <a href="Panelusuario.php" class="btn-volver">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>