<?php 
    session_start(); // Iniciar la sesión

    require_once '../../../../backend/utils/conection.php'; 
    
    if (isset($_POST['submit_button'])) {
        $phone = $_POST['phone'];
        $skill_level = $_POST['skill_level'];
        $play_side = $_POST['play_side'];
        $dominant_hand = $_POST['dominant_hand'];

        $userId = $_SESSION['user_id'];

        $con = base::conect();
        $sql = "UPDATE Usuario SET Telefono = :phone, NivelHabilidad = :skill_level, LadoJuego = :play_side, ManoDominante = :dominant_hand WHERE id = :user_id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':skill_level', $skill_level);
        $stmt->bindParam(':play_side', $play_side);
        $stmt->bindParam(':dominant_hand', $dominant_hand);
        $stmt->bindParam(':user_id', $userId);


        if ($stmt->execute()) {
            $message = "Changes saved";
        } else {
            $message = "Sorry, there was an error updating the profile";
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
    <div class="overlay" id="overlay">
        <div class="loader">
            Guardando cambios...
        </div>
    </div>
        <h1>Edit Profile</h1>
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <div class="test">    
            <label for="name">Name:</label>
            <input class="checkbox" type="text" id="name" name="name" ><br><br>
            </div>

            <div class="test">
            <label for="phone">Phone name:</label>
            <input class="checkbox" type="number" id="phone" name="phone" required><br><br>
            </div>

            <div class="test">
            <label for="skill_level">Skill Level (1-5):</label>
            <input class="checkbox" type="number" id="skill_level" name="skill_level" min="1" max="5" required>
            </div>

            <div class="test">
            <label for="play_side">Playing position:</label>
            <select class="options_form" id="play_side" name="play_side" >
                <option value="Drive">Drive</option>
                <option value="Back">Back</option>
                <option value="Both sides">Both sides</option>
            </select><br><br>
            </div>

            <div class="test">
            <label for="dominant_hand">Dominant hand:</label>
            <select class="options_form" id="dominant_hand" name="dominant_hand" >
                <option value="right_handed">Right-handed</option>
                <option value="left_handed">Left-handed</option>
            </select><br><br>
            </div>

            <input class="submit" type="submit" value="Save changes" name="submit_button">
        </form>
        <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
        <?php endif; ?>
        <script>
            document.getElementById("profileForm").addEventListener("submit", function() {
                document.getElementById("overlay").style.display = "block"; // Mostrar la pantalla de carga
            });
        </script>
        <?php
            if (!empty($message)) {
                echo "<script>window.location.href = '../Profile/Profile.php';</script>";
            }
        ?>
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