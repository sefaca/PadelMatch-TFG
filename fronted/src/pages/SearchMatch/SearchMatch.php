<?php
session_start(); // Iniciar la sesión

require('../../../../backend/utils/conection.php');

$message = "";
$matches = array();
$reservations = array(); // Obtener reservas disponibles

// Fecha y hora actual
$currentDateTime = date("Y-m-d H:i:s");

// Consulta inicial para obtener todos los partidos disponibles que estén por delante de la fecha y hora actual
$con = base::conect();
$sql = "SELECT * FROM Partido WHERE CONCAT(Fecha, ' ', Hora) > :currentDateTime";
$stmt = $con->prepare($sql);
$stmt->bindParam(':currentDateTime', $currentDateTime);
if ($stmt->execute()) {
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener reservas disponibles para cada partido encontrado
    foreach ($matches as &$match) {
        $matchId = $match['ID'];
        $sql = "SELECT * FROM Reserva WHERE Partido_ID = :matchId AND Usuario_ID IS NULL";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':matchId', $matchId);
        if ($stmt->execute()) {
            $reservations[$matchId] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $message = "Error retrieving reservations";
        }
    }
} else {
    $message = "Error retrieving matches";
}

// Verificar si se ha enviado el formulario de búsqueda
if (isset($_POST['search_match_button'])) {
    if (!empty($_POST['date']) && !empty($_POST['hour']) && !empty($_POST['location']) && !empty($_POST['level'])) {
        $sql = "SELECT * FROM Partido WHERE Fecha = :fecha AND Hora = :hora AND Ubicacion = :ubicacion AND NivelHabilidadRequerido = :match_skill";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':fecha', $_POST['date']);
        $stmt->bindParam(':hora', $_POST['hour']);
        $stmt->bindParam(':ubicacion', $_POST['location']);
        $stmt->bindParam(':match_skill', $_POST['level']);

        if ($stmt->execute()) {
            $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($matches) > 0) {
                $message = "Matches found";
                
                // Obtener reservas disponibles para cada partido encontrado
                foreach ($matches as $match) {
                    $matchId = $match['ID'];
                    $sql = "SELECT * FROM Reserva WHERE Partido_ID = :matchId AND Usuario_ID IS NULL";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':matchId', $matchId);
                    if ($stmt->execute()) {
                        $reservations[$matchId] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $message = "Error retrieving reservations";
                    }
                }
            } else {
                $message = "No matches found with the specified criteria";
            }
        } else {
            $message = "Error executing the query";
        }
    } else {
        $message = "Please fill in all fields";
    }
}

// Reservar una opción si se selecciona
if (isset($_POST['reserve_button'])) {
    if (isset($_POST['match_id'])) {
        $matchId = $_POST['match_id'];
        $userId = $_SESSION['user_id']; 
        $sql = "SELECT ID FROM Reserva WHERE Partido_ID = :matchId AND Usuario_ID IS NULL LIMIT 1"; // Buscar una reserva disponible
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':matchId', $matchId);
        if ($stmt->execute()) {
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($reservation) {
                $reservationId = $reservation['ID']; // Obtener el ID de la reserva disponible
                $sql = "UPDATE Reserva SET Usuario_ID = :userId WHERE ID = :reservationId"; // Actualizar la reserva con el ID del usuario
                $stmt = $con->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':reservationId', $reservationId);
                if ($stmt->execute()) {
                    $message = "Reservation successful";
                } else {
                    $message = "Error reserving the option";
                }
            } else {
                $message = "No available reservations for this match";
            }
        } else {
            $message = "Error retrieving reservations";
        }
    } else {
        $message = "Match ID not provided";
    }
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
            <a href="../Contactos/Contacts.php">Add Contacts</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
    <div>
        <h1>Search Matches</h1>
    </div>


    <div class="main-container">

        <form class="form" action="" method="post">
                <label class="title-form" for="title">Filters</label>
                <label for="name">Date:</label>
                <input class="date-time" type="date" id="date" name="date" required>
                <label for="hour">Hour:</label>
                <input class="date-hour" type="time" id="hour" name="hour" required>
                <label for="location">Location:</label>
                <input class="date-location" type="text" id="location" name="location" required>
                <label for="level">Level (1-5):</label>
                <input class="checkbox" type="number" id="level" name="level" min="1" max="5" required>

                <input class="submit" type="submit" value="Search Match" name="search_match_button">
            </form>

        
            <div class="match-found">

                <?php if(count($matches) > 0): ?>
                <h2>Matches Found</h2>
                <ul>
                    <?php foreach ($matches as $match): ?>
                        <li>
                            <?php echo $match['Fecha'] . ' ' . $match['Hora'] . ' ' . $match['Ubicacion'] . ' ' . $match['NivelHabilidadRequerido'] . ' ' . $match['EstadoPartido']; ?>
                            <form action="" method="post">
                                <input type="hidden" name="match_id" value="<?php echo $match['ID']; ?>">
                                <?php
                                    // Contar el número de reservas disponibles para el partido
                                    $numReservations = isset($reservations[$match['ID']]) ? count($reservations[$match['ID']]) : 0;
                                    for ($i = 1; $i <= $numReservations; $i++) {
                                        $positionClass = '';
                                        switch ($i) {
                                            case 1:
                                                $positionClass = 'top-left';
                                                break;
                                            case 2:
                                                $positionClass = 'top-right';
                                                break;
                                            case 3:
                                                $positionClass = 'bottom-left';
                                                break;
                                            case 4:
                                                $positionClass = 'bottom-right';
                                                break;
                                        }
                                        echo '<input class="reserve-button ' . $positionClass . '" type="submit" name="reserve_button" value="Player ' . $i . '">';
                                    }
                                ?>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="pista">
                <img class="img-pista" src="../../../../backend/utils/PistaPadel.png" alt="">
            </div>
    </div>
</body>
</html>

