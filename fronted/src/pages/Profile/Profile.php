<?php 
session_start(); // Iniciar la sesión

require_once '../../../../backend/utils/conection.php'; 

// Manejar la creación del grupo si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_grupo'])) {
    // Verificar si el nombre del grupo no está vacío
    if (!empty($_POST['nombre_grupo'])) {
        // Obtener el nombre del grupo enviado desde el formulario
        $nombre_grupo = $_POST['nombre_grupo'];

        if (isset($_POST['selected_friends'])) {
            $userId = $_SESSION['user_id'];
            $selectedFriends = $_POST['selected_friends'];
        
            // Insertar el grupo en la base de datos
            $con = base::conect();
            $sql = "INSERT INTO Grupo (ID, Nombre) VALUES (:userId, :nombre_grupo)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':nombre_grupo', $nombre_grupo);
            if ($stmt->execute()) {
                $groupId = $con->lastInsertId();
        
                // Insertar los amigos seleccionados en el grupo
                foreach ($selectedFriends as $friendId) {
                    $sql = "INSERT INTO GrupoAmigo (Grupo_ID, Amigo_ID) VALUES (:groupId, :friendId)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':groupId', $groupId);
                    $stmt->bindParam(':friendId', $friendId);
                    $stmt->execute();
                }
        
                echo "Group successfully created";
            } else {
                echo "Error creating the group: " . $stmt->errorInfo()[2]; // Mostrar el mensaje de error específico
            }
        } else {
            echo "No selected friends were received to add to the group.";
        }
    } else {
        // Mostrar un mensaje de error si el nombre del grupo está vacío
        $error_message = "The group name cannot be empty.";
    }
}

// Verificar si el formulario de reservas ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_reservas']) && isset($_POST['selected_group'])) {
    // Obtener el ID del grupo seleccionado y las reservas seleccionadas
    $selectedGroup = $_POST['selected_group'];
    $selectedReservas = $_POST['selected_reservas'];

    // Obtener los IDs de los usuarios dentro del grupo seleccionado
    $con = base::conect();
    $sql = "SELECT Amigo_ID FROM GrupoAmigo WHERE Grupo_ID = :selectedGroup";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':selectedGroup', $selectedGroup);
    if ($stmt->execute()) {
        $usuariosGrupo = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
    // Insertar una notificación para cada usuario del grupo
    foreach ($usuariosGrupo as $usuario) {
        foreach ($selectedReservas as $reserva) {
            // Obtener la fecha de la reserva
            $con = base::conect();
            $sql_fecha_reserva = "SELECT Fecha, Hora, Usuario_ID FROM Reserva WHERE ID = :reservaId";
            $stmt_fecha_reserva = $con->prepare($sql_fecha_reserva);
            $stmt_fecha_reserva->bindParam(':reservaId', $reserva);
            if ($stmt_fecha_reserva->execute()) {
                $reservaData = $stmt_fecha_reserva->fetch(PDO::FETCH_ASSOC);
                $fecha_reserva = $reservaData['Fecha'];
                $hora_reserva = $reservaData['Hora'];
                $userId = $reservaData['Usuario_ID'];
    
                // Obtener el nombre del usuario que envió la reserva
                $sql_nombre_usuario = "SELECT Nombre FROM Usuario WHERE ID = :userId";
                $stmt_nombre_usuario = $con->prepare($sql_nombre_usuario);
                $stmt_nombre_usuario->bindParam(':userId', $userId);
                if ($stmt_nombre_usuario->execute()) {
                    $nombre_usuario = $stmt_nombre_usuario->fetchColumn();
                    $mensaje = "Has recibido una invitación para jugar la fecha: $fecha_reserva a las: $hora_reserva por el usuario $nombre_usuario";
                    $sql_insert_notificacion = "INSERT INTO Notificaciones (Usuario_ID, Message) VALUES (:usuario, :mensaje)";
                    $stmt_insert_notificacion = $con->prepare($sql_insert_notificacion);
                    $stmt_insert_notificacion->bindParam(':usuario', $usuario);
                    $stmt_insert_notificacion->bindParam(':mensaje', $mensaje);
                    $stmt_insert_notificacion->execute();
                } else {
                    echo "Error getting the user's name.";
                }
            } else {
                echo "Error in obtaining the date of the reservation.";
            }
        }
    } echo "Reservations successfully sent.";
    }    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styleProfile2.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <header>
        <nav class="nav">
            <img class="logo" src="../../../../backend/utils/posibleLogo1.png" />
            <div class="links">
                <a href="../CreateMatch/CreateMatch.php">Create Match</a>
                <a href="../SearchMatch/SearchMatch.php">Search Match</a>
                <a href="../Contactos/Contacts.php">Add Contacts</a>
                
            </div>
            <div class="icons">
                    <a class="icon-nav-profile" href="../Profile/Profile.php">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <a class="icon-message-profile" href="../Inbox/Inbox.php">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
            </div>
        </nav>
    </header>
    <div class="profile">
        <div>
            <div class="profile-banner">
                <img class="image-banner" src="../../../../backend/utils/img-banner.png" alt="">
                <div class="profile-data-banner">
                    <?php
                        if (isset($_SESSION['user_id'])) {
                            $userId = $_SESSION['user_id'];
                            // Obtener la ruta de la imagen de perfil del usuario desde la base de datos
                            $userData = base::getUserDataById($userId);
                            if ($userData) {
                                // Mostrar la imagen de perfil
                                if (!empty($userData['FotoPerfil'])) {
                                    echo '<img class="image-profile" src="../../../../backend/utils/uploads/' . $userData['FotoPerfil'] . '" alt="">';
                                } else {
                                    // Si está vacío, mostrar una imagen por defecto o un mensaje indicando que no hay imagen
                                    echo '<img class="image-profile" src="../../../../backend/utils/user2.jpg" alt="Default Image">';
                                }
                            
                            } else {
                                echo 'No data found for this user.';
                            }
                        } else {
                            echo 'Unauthenticated user.';
                        }
                    ?>
                </div>
            </div>
            <div class="main-content">
                <div id="Profile-data" class="profileContent">
                    <div class="menu-profile">
                        <div class="profile-data"  onclick="data(event, 'Profile-data')">
                            <i class="fa-solid fa-user" style="padding-right: 1rem"></i><h5>Profile</h5>
                        </div>
                        <div class="friends-data" onclick="data(event, 'Profile-data-2')">
                            <i class="fa-solid fa-user-group" style="padding-right: 1rem"></i><h5>Friends</h5>
                        </div>
                        <div class="reservas-data" onclick="data(event, 'Profile-data-3')">
                            <i class="fa-solid fa-calendar" style="padding-right: 1rem"></i><h5>Reservations</h5>
                        </div>
                    </div>
                    <?php
                        require_once '../../../../backend/utils/conection.php'; 

                        if (isset($_SESSION['user_id'])) {
                            $userId = $_SESSION['user_id'];
                            $userData = base::getUserDataById($userId);
                            if ($userData) {
                                echo '<h3>Nombre:</h3>' . $userData['Nombre'] . '</h3>';
                                echo '<h4>Correo Electrónico:</h4>' . $userData['CorreoElectronico'] . '</h4>';
                                echo '<h4>Skill Level:</h4>' . $userData['NivelHabilidad'] . '<br>';
                                echo '<img class="image-profile" src="' . $userData['FotoPerfil'] . '" alt="">';
                                echo '<h4>Phone:</h4>' . $userData['Telefono'] . '<br>';
                                echo '<h4>Playing position:</h4>' . $userData['LadoJuego'] . '<br>';
                                echo '<h4>Dominant hand:</h4>' . $userData['ManoDominante'] . '<br>';
                                echo '<img class="image-profile" src="' . $userData['FotoPerfil'] . '" alt="">';

                                // Mostrar el botón "Panel Admin" solo si el usuario es administrador
                                if ($userData['Rol'] === 'administrador') {
                                    echo '<a href="../Admin/admin.php" class="buttons">Panel Admin</a>';
                                }

                            } else {
                                echo 'No data found for this user.';
                            }
                        } else {
                            echo 'Unauthenticated user';
                        }
                    ?>
                </div>

                <div id="Profile-data-2" class="profileContent">
                    <div class="menu-profile">
                    <div class="profile-data"  onclick="data(event, 'Profile-data')">
                            <i class="fa-solid fa-user" style="padding-right: 1rem"></i><h5>Profile</h5>
                        </div>
                        <div class="friends-data" onclick="data(event, 'Profile-data-2')">
                            <i class="fa-solid fa-user-group" style="padding-right: 1rem"></i><h5>Friends</h5>
                        </div>
                        <div class="reservas-data" onclick="data(event, 'Profile-data-3')">
                            <i class="fa-solid fa-calendar" style="padding-right: 1rem"></i><h5>Reservations</h5>
                        </div>
                    </div>
                    <div class="name-profile">
                        <h3>Friends:</h3>
                    </div>
                    <form method="post" action="">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Skill Level</th>
                                    <th>Play Side</th>
                                    <th>Dominant Hand</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php
                                require_once '../../../../backend/utils/conection.php';

                                if (isset($_SESSION['user_id'])) {
                                    $userId = $_SESSION['user_id'];
                                    $con = base::conect();
                                    $sql = "SELECT * FROM Amigo WHERE Usuario_ID = :userId";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bindParam(':userId', $userId);
                                    if ($stmt->execute()) {
                                        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if ($friends) {
                                            foreach ($friends as $friend) {
                                                // Obtener los datos del amigo utilizando su ID de amigo
                                                $friendId = $friend['ID'];
                                                $friendData = base::getUserDataById($friendId);
                                                if ($friendData) {
                                                    echo '<tr>';
                                                    echo '<td>' . $friendData['Nombre'] . '</td>';
                                                    echo '<td>' . $friendData['NivelHabilidad'] . '</td>';
                                                    echo '<td>' . $friendData['LadoJuego'] . '</td>';
                                                    echo '<td>' . $friendData['ManoDominante'] . '</td>';
                                                    echo '<td><input type="checkbox" name="selected_friends[]" value="' . $friendId . '"></td>'; // Aquí se asigna el ID real del amigo como valor
                                                    echo '</tr>';
                                                }
                                            }
                                            
                                        } else {
                                            echo '<tr><td colspan="5">You still have no friends.</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">Error retrieving the list of friends.</td></tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5">Unauthenticated user</td></tr>';
                                }
                            ?>
                        </tbody>
                        </table>
                        <label for="nombre_grupo">Name of the Group:</label>
                        <input type="text" id="nombre_grupo" name="nombre_grupo" required>
                        <button type="submit">Create Group</button> 
                    </form>
                    <?php 
                        // Mostrar mensaje de error si hubo un problema al crear el grupo
                        if (isset($error_message)) {
                            echo '<p>' . $error_message . '</p>';
                        }
                    ?>
                    
                </div>

                <div id="Profile-data-3" class="profileContent" style="display: none;">
                    <div class="menu-profile">
                        <div class="profile-data"  onclick="data(event, 'Profile-data')">
                                <i class="fa-solid fa-user" style="padding-right: 1rem"></i><h5>Profile</h5>
                            </div>
                            <div class="friends-data" onclick="data(event, 'Profile-data-2')">
                                <i class="fa-solid fa-user-group" style="padding-right: 1rem"></i><h5>Friends</h5>
                            </div>
                            <div class="reservas-data" onclick="data(event, 'Profile-data-3')">
                                <i class="fa-solid fa-calendar" style="padding-right: 1rem"></i><h5>Reservations</h5>
                            </div>
                        </div>
                        <div class="name-profile">
                    <div class="name-profile">
                            <h3>Reservations:</h3>
                        </div>
                        <form method="post" action="">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Hour</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Consulta SQL para obtener las reservas del usuario
                                        $sql_reservas = "SELECT ID, Fecha, Hora FROM Reserva WHERE Usuario_ID = :userId";
                                        $stmt_reservas = $con->prepare($sql_reservas);
                                        $stmt_reservas->bindParam(':userId', $userId);

                                        if ($stmt_reservas->execute()) {
                                            $reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);
                                            if ($reservas) {
                                                foreach ($reservas as $reserva) {
                                                    echo '<tr>';
                                                    echo '<td>' . $reserva['Fecha'] . '</td>';
                                                    echo '<td>' . $reserva['Hora'] . '</td>';
                                                    echo '<td><input type="checkbox" name="selected_reservas[]" value="' . $reserva['ID'] . '"></td>'; // Aquí se asigna el ID real de la reserva como valor
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3">You have no reservations.</td></tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="3">Error when recovering reserves.</td></tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
    
                            <div class="grupos">
                                <h3>Groups:</h3>
                                <select name="selected_group">
                                    <?php
                                        // Consulta SQL para obtener los grupos del usuario
                                        $sql_grupos = "SELECT ID, Nombre FROM Grupo WHERE ID = :userId";
                                        $stmt_grupos = $con->prepare($sql_grupos);
                                        $stmt_grupos->bindParam(':userId', $userId);
                                        if ($stmt_grupos->execute()) {
                                            $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);
                                            if ($grupos) {
                                                foreach ($grupos as $grupo) {
                                                    echo '<option value="' . $grupo['ID'] . '">' . $grupo['Nombre'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No groups were found.</option>';
                                            }
                                        } else {
                                            echo '<option value="">Error when retrieving groups.</option>';
                                        }
                                    ?>
                                </select>
                            </div>
    
                            <button type="submit" name="submit">Send Reservation</button>
                        </form>
                    </div>
                </div>

                <script src="./Profile.js"></script>
                <div class="buttons-container">
                    <a class="buttons" href="../CompleteProfile/CompleteProfile.php">Complete or Modify your Profile</a>
                    <a class="buttons" href="../CreateMatch/CreateMatch.php">Create Match</a>
                    <a class="buttons" href="../SearchMatch/SearchMatch.php">Search Match</a>
                    <a class="buttons" href="../Contactos/Contacts.php">Add Contacts</a>
                </div>
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
