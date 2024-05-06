<?php 
    session_start(); // Iniciar la sesión

    require_once '../../../../backend/utils/conection.php'; 
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

                            // if (isset($_GET['id'])) {
                            //     $userId = $_GET['id'];
                            //     $userData = base::getUserDataById($userId);
                            //     if ($userData) {
                            //         echo '<h3>' . $userData['Nombre'] . '</h3>';
                            //         echo '<h4>' . $userData['Telefono'] . '</h4>';
                            //         // echo 'Skill Level: ' . $userData['NivelHabilidad'] . '<br>';
                            //     } else {
                            //         echo 'No se encontraron datos para este usuario.';
                            //     }
                            // } else {
                            //     echo 'ID de usuario no proporcionado en la URL.';
                            // }
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
                        <div class="name-profile">
                            <h2>Amigos</h2>
                        </div>
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
                                        echo '<ul>';
                                        foreach ($friends as $friend) {
                                            // Obtener los datos del amigo utilizando su ID de amigo
                                            $friendId = $friend['ID'];
                                            $friendData = base::getUserDataById($friendId);
                                            if ($friendData) {
                                                echo '<li>' . $friendData['Nombre'] . '</li>';
                                            }
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '<p>Aún no tienes amigos.</p>';
                                    }
                                } else {
                                    echo '<p>Error al recuperar la lista de amigos.</p>';
                                }
                            } else {
                                echo 'Usuario no autenticado';
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
</html>