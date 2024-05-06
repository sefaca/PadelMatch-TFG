<?php
    require('../../../../backend/utils/conection.php');

    $message = "";

    if (!empty($_POST['date']) && !empty($_POST['hour']) && !empty($_POST['location']) && !empty($_POST['level'])) {
        $con = base::conect();
        $sql = "INSERT INTO Partido(Fecha, Hora, Ubicacion, NivelHabilidadRequerido) VALUES (:fecha, :hora, :ubicacion, :match_skill)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':fecha', $_POST['date']);
        $stmt->bindParam(':hora', $_POST['hour']);
        $stmt->bindParam(':ubicacion', $_POST['location']);
        $stmt->bindParam(':match_skill', $_POST['level']);

        if ($stmt->execute()) {
            $message = "Successfully created new match";

            // Obtener el ID del partido recién creado
            $matchId = $con->lastInsertId();

            // Insertar 4 opciones de reserva en el partido
            $sql = "INSERT INTO Reserva(Fecha, Hora, Partido_ID) VALUES (:fecha, :hora, :matchId)";
            $stmt = $con->prepare($sql);

            // Repetir la inserción 4 veces para crear 4 opciones de reserva
            for ($i = 0; $i < 4; $i++) {
                $stmt->bindParam(':fecha', $_POST['date']);
                $stmt->bindParam(':hora', $_POST['hour']);
                $stmt->bindParam(':matchId', $matchId);
                $stmt->execute();
            }
            
        } else {
            $message = "Sorry imputs not corrects";
        }
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
    <title>Document</title>
</head>
<body>
    <header>
        <nav class="links">
            <a href="../SearchMatch/SearchMatch.php">Search Match</a>
            <a href="../Contactos/Contacts.php">Add Contacts</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
    <div>
        <h1>Match Create</h1>
    </div>

    <?php if(!empty($message)): ?>
        <p><?php echo $message ?></p>
    <?php endif; ?>



    <form class="form" action="" method="post">
        <label for="title">Match data</label>
        <label for="name">Date:</label>
        <input class="date-time" type="date" id="date" name="date" required>
        <label for="hour">Hour:</label>
        <input class="date-hour" type="time" id="hour" name="hour" required>
        <label for="location">Location:</label>
        <input class="date-location" type="text" id="location" name="location" required>
        <label for="level">Level (1-5):</label>
        <input class="checkbox" type="number" id="level" name="level" min="1" max="5" required>

        <input class="submit" type="submit" value="Create Match" name="create_match_button">
    </form>
</body>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Enlaces</h3>
            <ul>
                <li><a href="../Home/Home.php">Inicio</a></li>
                <li><a href="#">Acerca de</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <p>Dirección: Sevilla</p>
            <p>Email: sefaca24@gmail.com</p>
        </div>
        <div class="footer-section">
            <h3>Síguenos</h3>
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