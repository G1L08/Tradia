<?php
session_start();


if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$subtotal = 0;

foreach ($_SESSION['carrito'] as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}

$costo_envio = 100.00;
$total_a_pagar = $subtotal + $costo_envio;


if ($subtotal <= 0) {
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medios de Pago - Means</title>
    <link rel="stylesheet" href="../CSS/pago.css">
</head>

<body>
    <img src="../Multimedia/ResgitroTradia.png" class="Video">

    <div class="checkout-container">
        <h2 class="titulo-pago">Finalizar Pedido</h2>

        <div class="contenido-pago">

            <div class="seccion-formulario">
                <h3>Información de la Tarjeta</h3>
                <form id="formulario-pago">
                    <div class="campo">
                        <label for="nombre-tarjeta">Nombre en la Tarjeta</label>
                        <input
                            type="text"
                            id="nombre-tarjeta"
                            placeholder="Juan Pérez"
                            pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{3,}"
                            title="Solo letras y espacios. Mínimo 3 caracteres."
                            required>
                    </div>
                    <div class="campo">
                        <label for="numero-tarjeta">Número de Tarjeta</label>
                        <input
                            type="tel"
                            id="numero-tarjeta"
                            placeholder="XXXX XXXX XXXX XXXX"
                            pattern="[0-9]{13,16}"
                            title="Ingrese entre 13 y 16 dígitos numéricos, sin espacios."
                            maxlength="16"
                            required>
                    </div>
                    <div class="campos-dobles">
                        <div class="campo">
                            <label for="expiracion">Fecha de Expiración (MM/DD)</label>
                            <input
                                type="text"
                                id="expiracion"
                                placeholder="MM/DD"
                                pattern="(0[1-9]|1[0-2])\/?(0[1-9]|[12]\d|3[01])"
                                title="Formato MM/DD (ej: 12/31). Mes de 01-12, Día de 01-31."
                                maxlength="5"
                                required>
                        </div>
                        <div class="campo">
                            <label for="cvv">CVV</label>
                            <input
                                type="password"
                                id="cvv"
                                placeholder="***"
                                pattern="[0-9]{3,4}"
                                title="Ingrese 3 o 4 dígitos."
                                maxlength="3"
                                required>
                        </div>
                    </div>

                    <h3>Datos de Facturación y Envío</h3>
                    <div class="campo">
                        <label for="pais">País / Región</label>
                        <select id="pais" required>
                            <option value="">Seleccione un país</option>
                            <option value="MX">México</option>
                            <option value="US">Estados Unidos</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="direccion">Dirección</label>
                        <input
                            type="text"
                            id="direccion"
                            placeholder="Calle, Número, Colonia"
                            pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ#\s-]{10,}"
                            title="Mínimo 10 caracteres. Incluya calle y número."
                            required>
                    </div>
                </form>
            </div>

            <div class="seccion-resumen">
                <h3>Resumen del Pedido</h3>

                <div class="detalle-productos">
                    <h4>Productos:</h4>
                    <?php foreach ($_SESSION['carrito'] as $item):
                        $precio_linea = $item['precio'] * $item['cantidad'];
                    ?>
                        <div class="producto-linea">
                            <span><?php echo htmlspecialchars($item['nombre']); ?> (x<?php echo $item['cantidad']; ?>)</span>
                            <span>$<?php echo number_format($precio_linea, 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="detalle-linea">
                    <span>Subtotal del Carrito</span>
                    <span>**$<?php echo number_format($subtotal, 2); ?>**</span>
                </div>

                <div class="detalle-linea">
                    <span>Costo de Envío</span>
                    <span>$<?php echo number_format($costo_envio, 2); ?></span>
                </div>

                <div class="detalle-total">
                    <span>Total a Pagar</span>
                    <span>**$<?php echo number_format($total_a_pagar, 2); ?>**</span>
                </div>

                <button type="submit" form="formulario-pago" class="boton-confirmar">
                    Pagar y Confirmar Pedido
                </button>

                <p class="nota-seguridad">
                    Transacción Segura (Cifrado SSL)
                </p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formularioPago = document.getElementById('formulario-pago');
            const botonConfirmar = document.querySelector('.boton-confirmar');
            const expiracionInput = document.getElementById('expiracion');

            expiracionInput.addEventListener('input', function(e) {
                let input = e.target.value;


                input = input.replace(/\D/g, '');


                if (input.length > 2) {

                    if (input.indexOf('/') === -1) {

                        input = input.slice(0, 2) + '/' + input.slice(2, 4);
                    }
                }

                e.target.value = input.slice(0, 5);
            });


            formularioPago.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!formularioPago.checkValidity()) {

                    return;
                }


                botonConfirmar.disabled = true;
                botonConfirmar.textContent = 'Procesando Pago...';


                setTimeout(() => {


                    fetch('../BD/procesopago.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'confirmacion=true'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {

                                alert('Pago Confirmado. ¡Gracias por tu compra!');

                                window.location.href = 'confirmacion.php';
                            } else {

                                alert('Error al procesar el pedido: ' + data.message);
                                botonConfirmar.disabled = false;
                                botonConfirmar.textContent = 'Pagar y Confirmar Pedido';
                            }
                        })
                        .catch(error => {

                            console.error('Error de conexión:', error);
                            alert('Error de conexión con el servidor. Intente de nuevo.');
                            botonConfirmar.disabled = false;
                            botonConfirmar.textContent = 'Pagar y Confirmar Pedido';
                        });
                }, 2000);
            });
        });
    </script>
</body>

</html>