<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRADIA - Registro</title>
    <link rel="stylesheet" href="../CSS/registro.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="registro-card">
            <div class="sidebar">
                <div class="logo"><h1>Tradia</h1></div>
                <div class="sidebar-info">
                    <h2>Bienvenido</h2>
                    <p>Crea tu cuenta para empezar a trabajar con nosotros.</p>
                </div>
            </div>

            <div class="form-content">
                <form id="registroForm" action="../BD/registra.php" method="POST">
                    <h3>Registro</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="Apellido" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="Correo" required>
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="Telefono" required inputmode="numeric">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" name="Password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirmar</label>
                            <input type="password" name="confirmPassword" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">CREAR CUENTA</button>
                    
                    <p class="footer-text">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>