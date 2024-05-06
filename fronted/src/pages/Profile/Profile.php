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
                $sql = "INSERT INTO Grupo (Usuario_ID, Nombre) VALUES (:userId, :nombre_grupo)";
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
            
                    echo "Grupo creado exitosamente.";
                } else {
                    echo "Error al crear el grupo.";
                }
            } else {
                echo "No se recibieron datos para crear el grupo.";
            }
        } else {
            // Mostrar un mensaje de error si el nombre del grupo está vacío
            $error_message = "El nombre del grupo no puede estar vacío.";
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
            <a href="../SearchMatch/SearchMatch.php">Search Match</a>
            <a href="../Contactos/Contacts.php">Add Contacts</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
        <div class="profile">
            <div>
                <div class="profile-banner">
                    <img class="image-banner" src="../../../../fronted/public/images/istockphoto-1514191101-1024x1024.jpg" alt="">
                    <div class="profile-data-banner">
                        <?php
                            if (isset($_SESSION['user_id'])) {
                                $userId = $_SESSION['user_id'];
                                $userData = base::getUserDataById($userId);
                                if ($userData) {
                                    // Verificar si el campo de imagen de perfil está vacío
                                    if (!empty($userData['FotoPerfil'])) {
                                        echo '<img class="image-profile" src="' . $userData['FotoPerfil'] . '" alt="">';
                                    } else {
                                        // Si está vacío, mostrar una imagen por defecto
                                        echo '<img class="image-profile" src="../../../../backend/utils/user2.jpg" alt="Default Image">';
                                    }
                                } else {
                                    echo 'No se encontraron datos para este usuario.';
                                }
                            } else {
                                echo 'Usuario no autenticado';
                            }
                        ?>
                        <?php
                            // require_once '../../../../backend/utils/conection.php'; 

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


                            } else {
                                echo 'No se encontraron datos para este usuario.';
                            }
                        } else {
                            echo 'Usuario no autenticado';
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
                        </div>
                        <div class="name-profile">
                            <h3>Amigos:</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Skill Level</th>
                                    <th>Lado de Juego</th>
                                    <th>Mano Dominante</th>
                                    <th>Seleccionar</th>
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
                                                        echo '<td><input type="checkbox" name="selected_friends[]" value="' . $friendData['ID'] . '"></td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                            } else {
                                                echo '<tr><td colspan="5">Aún no tienes amigos.</td></tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5">Error al recuperar la lista de amigos.</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">Usuario no autenticado</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                        <form method="post" action="">
                            <label for="nombre_grupo">Nombre del Grupo:</label>
                            <input type="text" id="nombre_grupo" name="nombre_grupo" required>
                            <button type="submit">Crear Grupo</button>
                        </form>

                        <?php 
                            // Mostrar mensaje de error si hubo un problema al crear el grupo
                            if (isset($error_message)) {
                                echo '<p>' . $error_message . '</p>';
                            }
                        ?>
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
