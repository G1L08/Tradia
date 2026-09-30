<?php
// Aseguramos que la conexión esté bien referenciada.
// Si C.php está en la misma carpeta: include('C.php');
// Si está en una carpeta BD arriba: include('../BD/C.php');
include('../BD/C.php'); 

session_start(); // Es buena práctica iniciar sesión para guardar datos del usuario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        $error_message = "Por favor, ingresa tu correo y contraseña.";
    } else {

        $sql = "SELECT ID, Correo, Password FROM usuario WHERE Correo = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $error_message = "Error interno del sistema.";
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $hashed_password = $user['Password']; 

                if (password_verify($password, $hashed_password)) {
                    // ¡ÉXITO! Guardamos datos y redirigimos.
                    $_SESSION['user_id'] = $user['ID'];
                    // Ajusta esta ruta si Panelusuario.php está en otra carpeta
                    header('Location: Panelusuario.php');
                    exit;
                    
                } else {
                    $error_message = "Contraseña incorrecta.";
                }

            } else {
                $error_message = "El correo electrónico no existe";
            }

            $stmt->close();
        }
    }
    
    if (isset($conn)) {
        $conn->close();
    }
    
    // Alerta de error limpia
    if (isset($error_message)) {
        echo '<script>alert("' . $error_message . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradia - Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="login-card">
            
            <div class="sidebar">
                <div class="logo">Tradia</div>
                <div class="sidebar-info">
                    <h2>Identifícate</h2>
                    <p>Accede a tu panel de control para gestionar tus proyectos.</p>
                </div>
            </div>

            <div class="form-content">
                <form action="login.php" method="POST"> 
                    <h3>Iniciar Sesión</h3>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn-submit">ENTRAR</button>
                    
                    <p class="footer-text">
                        ¿Eres nuevo? <a href="RegistroU.php">Regístrate aquí</a>
                    </p>
                </form> </div>
            
        </div>
    </div>
</body>
</html>