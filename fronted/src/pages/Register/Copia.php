<?php
// Conexión a la base de datos (asegúrate de colocar los datos correctos)
$servername = "localhost";
$username = "root";
$password = "administrador";
$dbname = "app_padel";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los datos del formulario
$username = $_POST['username'];
$email = $_POST['email'];
$skill_level = $_POST['skill_level'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña antes de guardarla

// Preparar la consulta SQL para insertar datos en la tabla users
$sql = "INSERT INTO users (username, email, skill_level, password) VALUES ('$username', '$email', '$skill_level', '$hashed_password')";

// Ejecutar la consulta y verificar si se ha realizado con éxito
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión con la base de datos
$conn->close();
?>
