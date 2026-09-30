<?php
// Incluimos la conexión a la base de datos
include('C.php'); 

// Recogemos los datos enviados por el método POST
$nombre   = $_POST['Nombre'];
$apellido = $_POST['Apellido'];
$correo   = $_POST['Correo'];
$numero   = $_POST['Telefono'];
$password = $_POST['Password'];
$confirm  = $_POST['confirmPassword'];

// 1. Validar que las contraseñas coincidan
if ($password !== $confirm) {
    echo '
        <script>
            alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
            location.href = "../PHP/RegistroU.php"; 
        </script>
    ';
    exit;
}

// 2. Validar que el teléfono sea numérico
if (!ctype_digit($numero)) {
    echo '
        <script>
            alert("El número de teléfono solo debe contener dígitos.");
            location.href = "../PHP/RegistroU.php"; 
        </script>
    ';
    exit;
}

// 3. Verificar si el correo ya existe para evitar duplicados
$stmt_verificacion = $conn->prepare("SELECT Correo FROM usuario WHERE Correo = ?");
$stmt_verificacion->bind_param("s", $correo);
$stmt_verificacion->execute();
$stmt_verificacion->store_result();

if ($stmt_verificacion->num_rows > 0) {
    echo '
        <script>
            alert("El correo que intenta registrar ya está en uso.");
            location.href = "../PHP/RegistroU.php"; 
        </script>
    ';
    $stmt_verificacion->close();
    exit;
}
$stmt_verificacion->close();

// 4. Encriptar la contraseña (seguridad ante todo)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 5. Insertar el nuevo usuario
$stmt_insertar = $conn->prepare("INSERT INTO usuario (Nombre, Apellido, Correo, Telefono, Password) VALUES (?, ?, ?, ?, ?)");
$stmt_insertar->bind_param("sssss", $nombre, $apellido, $correo, $numero, $hashedPassword);

if ($stmt_insertar->execute()) {
    echo '
        <script>
            alert("¡Usuario registrado exitosamente!");
            location.href = "../PHP/login.php"; 
        </script>
    ';
} else {
    echo '
        <script>
            alert("Error al registrar usuario: ' . $stmt_insertar->error . '");
            location.href = "../PHP/RegistroU.php"; 
        </script>
    ';
}

// Cerrar conexiones
$stmt_insertar->close();
mysqli_close($conn);
?>