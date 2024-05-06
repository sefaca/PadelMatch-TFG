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
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <header>
        <nav class="links">
            <a href="#">Enlace 1</a>
            <a href="#">Enlace 2</a>
            <a href="#">Enlace 3</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
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
            <input class="checkbox" type="number" id="skill_level" name="skill_level" min="1" max="5" >
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
</html>