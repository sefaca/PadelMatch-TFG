<?php
    session_start(); // Iniciar sesión

    require_once '../../../../backend/utils/conection.php'; 

    // Consultar las notificaciones para el usuario actual
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $con = base::conect();
        $sql = "SELECT * FROM Notificaciones WHERE Usuario_ID = :userId ORDER BY created_at DESC";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        if ($stmt->execute()) {
            $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Error al recuperar las notificaciones.";
        }
    } else {
        $error_message = "Usuario no autenticado.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../components/Header/Header.css">
    <link rel="stylesheet" href="./style1.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Bandeja de entrada</title>
</head>
<body>
    <header>
        <nav class="nav">
            <img class="logo" src="../../../../backend/utils/posibleLogo1.png" />
            <div class="links">
                <a href="../CreateMatch/CreateMatch.php">Create Match</a>
                <a href="../SearchMatch/SearchMatch.php">Search Match</a>
                <a href="../Contactos/Contacts.php">Add Contacts</a>
                <a class="icon-nav-profile" href="../Profile/Profile.php">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="inbox">
            <div class="inbox-header">
                <h1>Inbox</h1>
            </div>
            <div class="inbox-content">
                <?php
                if (isset($error_message)) {
                    echo '<p>' . $error_message . '</p>';
                } elseif (isset($notificaciones)) {
                    if (count($notificaciones) > 0) {
                        foreach ($notificaciones as $notificacion) {
                            echo '<div class="notification">';
                            echo '<a href="ReservarPartido.php?reserva_id=' . $notificacion['ID'] . '">Reservar Partido</a>';
                            echo '<p>' . $notificacion['Message'] . '</p>';
                            echo '<span>' . $notificacion['created_at'] . '</span>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="notification">';
                        echo '<p>You have no notifications.</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <a href="../Logout/Logout.php" style="padding-left: 250px;">Logout</a>
</body>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Links</h3>
            <ul>
                <li><a href="../Home/Home.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Address: Seville</p>
            <p>Email: sefaca24@gmail.com</p>
        </div>
        <div class="footer-section">
            <h3>Follow us</h3>
            <ul class="social-icons">
                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 PadelMatch. Todos los derechos reservados.</p>
    </div>
</footer>
</html>
