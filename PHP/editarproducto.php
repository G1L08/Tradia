<?php
include('../BD/C.php');

$id_producto_editar = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$producto = null;
$descuento = [
    'porcentaje_descuento' => 0.0,
    'fecha_inicio' => '',
    'fecha_fin' => ''
];

if ($id_producto_editar > 0) {
    $stmt = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto_editar);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $producto = $resultado->fetch_assoc();
        $stmt_desc = $conn->prepare("SELECT porcentaje_descuento, fecha_inicio, fecha_fin FROM descuentos WHERE id_producto = ? AND activo = 1 ORDER BY id_descuento DESC LIMIT 1");
        $stmt_desc->bind_param("i", $id_producto_editar);
        $stmt_desc->execute();
        $res_desc = $stmt_desc->get_result();

        if ($res_desc->num_rows === 1) {
            $descuento_data = $res_desc->fetch_assoc();
            $descuento['porcentaje_descuento'] = $descuento_data['porcentaje_descuento'];
            $descuento['fecha_inicio'] = $descuento_data['fecha_inicio'];
            $descuento['fecha_fin'] = $descuento_data['fecha_fin'];
        }
        $stmt_desc->close();

        list($pres_cant, $pres_unid) = explode(' ', $producto['presentacion'], 2) + ['', ''];
        list($tam_val, $tam_unid) = explode(' ', $producto['tamano'], 2) + ['', ''];
    } else {
        echo '<script>alert("Producto no encontrado."); location.href = "../PHP/Panelusuario.php";</script>';
        exit;
    }
    $stmt->close();
} else {
    echo '<script>alert("ID de producto no válido."); location.href = "../PHP/Panelusuario.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRADIA - Editar Producto</title>
    <link rel="stylesheet" href="../CSS/produxto.css">
</head>

<body>
    <div class="container">
        <div class="logo-header">
            <div class="logo">
                <span class="logo-text">TRADIA</span>
            </div>
            <div class="user-info">Edición de Producto</div>
        </div>

        <div class="producto-wrapper">
            <div class="producto-container">
                <h2>Editar Producto</h2>

                <form id="productoForm" action="../BD/actualizarproducto.php" method="POST" class="productoForm" enctype="multipart/form-data">

                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                    <input type="hidden" name="ruta_archivo_actual" value="<?php echo htmlspecialchars($producto['ruta_archivo']); ?>">

                    <div class="producto-grid">
                        <div class="photo-section">
                            <label class="upload-area" for="fileInput">
                                <input type="file" id="fileInput" name="imagen_producto" multiple accept="image/*">
                                <div class="upload-icon">📷</div>
                                <p class="upload-text">Haz clic para subir **nueva** fotografía</p>
                                <p class="upload-hint">Dejar vacío para mantener la imagen actual</p>
                            </label>
                            <div class="preview-images" id="previewImages">
                            
                            </div>
                        </div>

                        <div class="info-section">
                            <div class="form-group">
                                <label data-required="*">Producto:</label>
                                <input type="text" id="nombre_producto" name="nombre_producto" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios." value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                            </div>

                            <div class="form-group">
                                <label data-required="*">Marca:</label>
                                <input type="text" id="marca" name="marca" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios." value="<?php echo htmlspecialchars($producto['marca']); ?>">
                            </div>

                            <div class="form-row form-row-2-col">
                                <div class="form-group">
                                    <label data-required="*">Tipo:</label>
                                    <select id="tipo" name="tipo" required>
                                        <option value="ferreteria" <?php if ($producto['tipo'] == 'ferreteria') echo 'selected'; ?>>Ferretería</option>
                                        <option value="electronica" <?php if ($producto['tipo'] == 'electronica') echo 'selected'; ?>>Electrónica</option>
                                        <option value="textiles" <?php if ($producto['tipo'] == 'textiles') echo 'selected'; ?>>Textiles</option>
                                        <option value="restaurante" <?php if ($producto['tipo'] == 'restaurante') echo 'selected'; ?>>Alimentos y Restaurante</option>
                                        <option value="hogar" <?php if ($producto['tipo'] == 'hogar') echo 'selected'; ?>>Artículos para el Hogar</option>
                                        <option value="juguetes" <?php if ($producto['tipo'] == 'juguetes') echo 'selected'; ?>>Juguetes</option>
                                        <option value="salud_belleza" <?php if ($producto['tipo'] == 'salud_belleza') echo 'selected'; ?>>Salud y Belleza</option>
                                        <option value="deportes" <?php if ($producto['tipo'] == 'deportes') echo 'selected'; ?>>Deportes y Fitness</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label data-required="*">Cantidad Minima para Compra:</label>
                                    <input type="number" id="utilidad" name="utilidad" min="1" step="1" required value="<?php echo htmlspecialchars($producto['utilidad']); ?>">
                                </div>
                            </div>

                            <div class="form-row form-row-2-col">
                                <div class="form-group">
                                    <label data-required="*">Cantidad en Stock:</label>
                                    <input type="number" id="cantidad" name="cantidad" min="0" step="1" required title="Solo números enteros." value="<?php echo htmlspecialchars($producto['cantidad_stock']); ?>">
                                </div>
                                <div class="form-group">
                                    <label data-required="*">Precio:</label>
                                    <input type="number" id="precio" name="precio" step="0.01" min="0" required title="Solo números y punto decimal." value="<?php echo htmlspecialchars($producto['precio_unitario']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider">
                        <h3>Detalles del Producto (Dimensiones y Características)</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Unidad de Medida:</label>
                            <div class="form-row-nested">
                                <input type="number" id="presentacion_cantidad" name="presentacion_cantidad" min="0" step="1" placeholder="Cantidad (ej: 500)" style="width: 60%;" value="<?php echo htmlspecialchars($pres_cant); ?>">
                                <select id="presentacion_unidad" name="presentacion_unidad" style="width: 40%;">
                                    <option value="">Unidad...</option>
                                    <option value="mililitros" <?php if ($pres_unid == 'mililitros') echo 'selected'; ?>>Mililitros (ml)</option>
                                    <option value="gramos" <?php if ($pres_unid == 'gramos') echo 'selected'; ?>>Gramos (gr)</option>
                                    <option value="piezas" <?php if ($pres_unid == 'piezas') echo 'selected'; ?>>Piezas (pz)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tamaño (Largo/Ancho/Diámetro):</label>
                            <div class="form-row-nested">
                                <input type="number" id="tamano_valor" name="tamano_valor" min="0" step="any" placeholder="Valor numérico" style="width: 60%;" value="<?php echo htmlspecialchars($tam_val); ?>">
                                <select id="tamano_unidad" name="tamano_unidad" style="width: 40%;">
                                    <option value="">Unidad...</option>
                                    <option value="centimetros" <?php if ($tam_unid == 'centimetros') echo 'selected'; ?>>Centímetros (cm)</option>
                                    <option value="metros" <?php if ($tam_unid == 'metros') echo 'selected'; ?>>Metros (m)</option>
                                    <option value="pulgadas" <?php if ($tam_unid == 'pulgadas') echo 'selected'; ?>>Pulgadas (in)</option>
                                    <option value="pies" <?php if ($tam_unid == 'pies') echo 'selected'; ?>>Pies (ft)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Forma:</label>
                            <input type="text" id="forma" name="forma" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios." value="<?php echo htmlspecialchars($producto['forma']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Color:</label>
                            <input type="text" id="color" name="color" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios." value="<?php echo htmlspecialchars($producto['color']); ?>">
                        </div>

                        <div class="form-group"></div>
                        <div class="form-group"></div>
                    </div>

                    <div class="section-divider">
                        <h3>Fechas</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label data-required="*">Fecha de Ingreso:</label>
                            <input type="date" id="fechaIngreso" name="fechaIngreso" required value="<?php echo htmlspecialchars($producto['fecha_ingreso']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Elaboración:</label>
                            <input type="date" id="fechaElaboracion" name="fechaElaboracion" value="<?php echo htmlspecialchars($producto['fecha_elaboracion']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Caducidad:</label>
                            <input type="date" id="fechaCaducidad" name="fechaCaducidad" value="<?php echo htmlspecialchars($producto['fecha_caducidad']); ?>">
                        </div>
                    </div>

                    <div class="section-divider">
                        <h3>Descuento (Opcional)</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Porcentaje de Descuento (%):</label>
                            <input type="number" id="porcentaje_descuento" name="porcentaje_descuento" min="0" max="100" step="0.01" value="<?php echo htmlspecialchars($descuento['porcentaje_descuento']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Inicio:</label>
                            <input type="date" id="fecha_inicio_descuento" name="fecha_inicio_descuento" value="<?php echo htmlspecialchars($descuento['fecha_inicio']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Conclusión:</label>
                            <input type="date" id="fecha_fin_descuento" name="fecha_fin_descuento" value="<?php echo htmlspecialchars($descuento['fecha_fin']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe las características del producto..."><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <div class="btn-group-secondary">
                            <button type="button" class="btn-secondary" onclick="window.history.back()">Cancelar</button>
                            <button type="reset" class="btn-secondary">Restablecer</button>
                        </div>
                        <button type="submit" class="btn-success">Guardar Cambios</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); 
            const day = String(now.getDate()).padStart(2, '0'); 
            const today = `${year}-${month}-${day}`; 

            const fechaIngresoInput = document.getElementById('fechaIngreso');
            const fechaElaboracionInput = document.getElementById('fechaElaboracion');
            const fechaCaducidadInput = document.getElementById('fechaCaducidad');
            const fechaInicioDescuentoInput = document.getElementById('fecha_inicio_descuento');
            const fechaFinDescuentoInput = document.getElementById('fecha_fin_descuento');
            const porcentajeDescuentoInput = document.getElementById('porcentaje_descuento');
            const utilidadInput = document.getElementById('utilidad'); 
            const cantidadInput = document.getElementById('cantidad'); 
            const form = document.getElementById('productoForm');

            fechaIngresoInput.setAttribute('min', today);
            fechaInicioDescuentoInput.setAttribute('min', today);

            utilidadInput.addEventListener('keypress', function(e) {
                const charCode = (e.which) ? e.which : e.keyCode;

                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    e.preventDefault();
                }
            });

            fechaElaboracionInput.addEventListener('change', function() {
                if (this.value) {
                    fechaCaducidadInput.setAttribute('min', this.value);
                    if (fechaCaducidadInput.value && fechaCaducidadInput.value < this.value) {
                        fechaCaducidadInput.value = '';
                    }
                } else {
                    fechaCaducidadInput.removeAttribute('min');
                }
            });

            fechaInicioDescuentoInput.addEventListener('change', function() {
                if (this.value) {
                    fechaFinDescuentoInput.setAttribute('min', this.value);
                    if (fechaFinDescuentoInput.value && fechaFinDescuentoInput.value < this.value) {
                        fechaFinDescuentoInput.value = '';
                    }
                } else {
                    fechaFinDescuentoInput.removeAttribute('min');
                }
            });


            form.addEventListener('submit', function(event) {
                const porcentaje = parseFloat(porcentajeDescuentoInput.value);
                const fechaInicio = fechaInicioDescuentoInput.value;
                const fechaFin = fechaFinDescuentoInput.value;
                const fechaElaboracion = fechaElaboracionInput.value;
                const fechaCaducidad = fechaCaducidadInput.value;

                const utilidad = parseInt(utilidadInput.value);
                const cantidadStock = parseInt(cantidadInput.value);

                if (utilidad > cantidadStock) {
                    alert('ERROR: La Cantidad Mínima para Compra (' + utilidad + ') no puede ser mayor que la Cantidad en Stock actual (' + cantidadStock + ').');
                    event.preventDefault();
                    return;
                }

                if (porcentaje > 0) {
                    if (!fechaInicio || !fechaFin) {
                        alert('Si especificas un Porcentaje de Descuento, debes llenar la Fecha de Inicio y la Fecha de Conclusión.');
                        event.preventDefault();
                        return;
                    }
                    if (fechaFin < fechaInicio) {
                        alert('La Fecha de Conclusión del Descuento no puede ser anterior a la Fecha de Inicio.');
                        event.preventDefault();
                        return;
                    }
                }

                if (fechaElaboracion && fechaCaducidad) {
                    if (fechaCaducidad < fechaElaboracion) {
                        alert('La Fecha de Caducidad no puede ser anterior a la Fecha de Elaboración.');
                        event.preventDefault();
                        return;
                    }
                }

            });

            porcentajeDescuentoInput.addEventListener('keypress', function(e) {
                const charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>