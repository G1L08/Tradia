<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEANS - Registrar Producto</title>
    <link rel="stylesheet" href="../CSS/produxto.css">
</head>

<body>
    <div class="container">
        <div class="logo-header">
            <div class="logo">
                <span class="logo-icon">M</span>
                <span class="logo-text">MEANS</span>
            </div>
            <div class="user-info">Registro de Producto</div>
        </div>

        <div class="producto-wrapper">
            <div class="producto-container">
                <h2>Registro de Producto</h2>

                <form id="productoForm" action="../BD/registroproducto.php" method="POST" class="productoForm" enctype="multipart/form-data">

                    <div class="producto-grid">
                        <div class="photo-section">
                            <label class="upload-area" for="fileInput">
                                <input type="file" id="fileInput" name="imagen_producto" multiple accept="image/*">
                                <p class="upload-text">Haz clic para subir fotografías del producto</p>
                                <p class="upload-hint">Puedes seleccionar múltiples imágenes</p>
                            </label>
                            <div class="preview-images" id="previewImages"></div>
                        </div>

                        <div class="info-section">
                            <div class="form-group">
                                <label data-required="*">Producto:</label>
                                <input type="text" id="nombre_producto" name="nombre_producto" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios.">
                            </div>

                            <div class="form-group">
                                <label data-required="*">Marca:</label>
                                <input type="text" id="marca" name="marca" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios.">
                            </div>

                            <div class="form-row form-row-2-col">
                                <div class="form-group">
                                    <label data-required="*">Tipo:</label>
                                    <select id="tipo" name="tipo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="ferreteria">Ferretería</option>
                                        <option value="electronica">Electrónica</option>
                                        <option value="textiles">Textiles</option>
                                        <option value="restaurante">Alimentos y Restaurante</option>
                                        <option value="hogar">Artículos para el Hogar</option>
                                        <option value="juguetes">Juguetes</option>
                                        <option value="salud_belleza">Salud y Belleza</option>
                                        <option value="deportes">Deportes y Fitness</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label data-required="*">Cantidad Minima para Compra:</label>
                                    <input type="text" id="utilidad" name="utilidad" required>
                                </div>
                            </div>

                            <div class="form-row form-row-2-col">
                                <div class="form-group">
                                    <label data-required="*">Cantidad en Stock:</label>
                                    <input type="number" id="cantidad" name="cantidad" min="0" step="1" required title="Solo números enteros.">
                                </div>
                                <div class="form-group">
                                    <label data-required="*">Precio:</label>
                                    <input type="number" id="precio" name="precio" step="0.01" min="0" required title="Solo números y punto decimal.">
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
                                <input type="number" id="presentacion_cantidad" name="presentacion_cantidad" min="0" step="1" placeholder="Cantidad (ej: 500)" style="width: 60%;">
                                <select id="presentacion_unidad" name="presentacion_unidad" style="width: 40%;">
                                    <option value="">Unidad...</option>
                                    <option value="mililitros">Mililitros (ml)</option>
                                    <option value="gramos">Gramos (gr)</option>
                                    <option value="piezas">Piezas (pz)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tamaño (Largo/Ancho/Diámetro):</label>
                            <div class="form-row-nested">
                                <input type="number" id="tamano_valor" name="tamano_valor" min="0" step="any" placeholder="Valor numérico" style="width: 60%;">
                                <select id="tamano_unidad" name="tamano_unidad" style="width: 40%;">
                                    <option value="">Unidad...</option>
                                    <option value="centimetros">Centímetros (cm)</option>
                                    <option value="metros">Metros (m)</option>
                                    <option value="pulgadas">Pulgadas (in)</option>
                                    <option value="pies">Pies (ft)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Forma:</label>
                            <input type="text" id="forma" name="forma" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios.">
                        </div>

                        <div class="form-group">
                            <label>Color:</label>
                            <input type="text" id="color" name="color" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios.">
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
                            <input type="date" id="fechaIngreso" name="fechaIngreso" required>
                        </div>

                        <div class="form-group">
                            <label>Fecha de Elaboración:</label>
                            <input type="date" id="fechaElaboracion" name="fechaElaboracion">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Caducidad:</label>
                            <input type="date" id="fechaCaducidad" name="fechaCaducidad">
                        </div>
                    </div>

                    <div class="section-divider">
                        <h3>Descuento (Opcional)</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Porcentaje de Descuento (%):</label>
                            <input type="number" id="porcentaje_descuento" name="porcentaje_descuento" min="0" max="100" step="0.01">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Inicio:</label>
                            <input type="date" id="fecha_inicio_descuento" name="fecha_inicio_descuento">
                        </div>

                        <div class="form-group">
                            <label>Fecha de Conclusión:</label>
                            <input type="date" id="fecha_fin_descuento" name="fecha_fin_descuento">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe las características del producto..."></textarea>
                    </div>

                    <div class="form-actions">
                        <div class="btn-group-secondary">
                            <button type="button" class="btn-secondary" onclick="window.history.back()">Cancelar</button>
                            <button type="reset" class="btn-secondary">Limpiar</button>
                        </div>
                        <button type="submit" class="btn-success">Guardar Producto</button>
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