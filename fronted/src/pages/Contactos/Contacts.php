<?php
session_start(); // Iniciar la sesión
require('../../../../backend/utils/conection.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_users'])) {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        $message = "Error: User not logged in.";
    } else {
        try {
            // Conexión a la base de datos
            $con = base::conect();
            // ID y nombre del usuario que selecciona como amigo
            $userId = $_SESSION['user_id'];
            // $userName = $_SESSION['user_name'];
            
            // Iterar sobre los usuarios seleccionados como amigos
            foreach ($_POST['selected_users'] as $friendId) {
                // Insertar la relación de amistad en la tabla Amigo
                $sql = "INSERT INTO Amigo (Usuario_ID, Nombre, ID) VALUES (:userId, :userName, :friendId)";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':userName', $userName);
                $stmt->bindParam(':friendId', $friendId);
                $stmt->execute();
            }
            $message = "Friends added successfully.";
        } catch(PDOException $e) {
            // Manejo de errores de la base de datos
            $message = "Error adding friends: " . $e->getMessage();
        }
    }
}

// Obtener lista de usuarios
$users = array();
try {
    // Conexión a la base de datos
    $con = base::conect();
    // Consulta para obtener todos los usuarios ordenados por nombre
    $sql = "SELECT * FROM Usuario ORDER BY Nombre";
    $stmt = $con->query($sql);
    // Obtener los resultados de la consulta
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Manejo de errores de la base de datos
    $message = "Error fetching users: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../components/Header/Header.css">
    <link rel="stylesheet" href="./style2.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <header>
        <nav class="links">
            <a href="../CreateMatch/CreateMatch.php">Create Match</a>
            <a href="../SearchMatch/SearchMatch.php">Search Match</a>
            <a href="../Contactos/Contacts.php">Add Contacts</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
    <div>
        <h1>Contacts</h1>
    </div>

    <?php if(!empty($message)): ?>
        <p><?php echo $message ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Skill Level</th>
                    <th>Dominant Hand</th>
                    <th>Preferred Side</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['Nombre']; ?></td>
                        <td><?php echo $user['NivelHabilidad']; ?></td>
                        <td><?php echo $user['ManoDominante']; ?></td>
                        <td><?php echo $user['LadoJuego']; ?></td>
                        <td><input type="checkbox" name="selected_users[]" value="<?php echo $user['ID']; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit">Submit</button>
    </form>

</body>
</html>
