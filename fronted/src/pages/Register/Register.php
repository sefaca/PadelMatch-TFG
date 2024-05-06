<?php
    require('../../../../backend/utils/conection.php');

    $message = "";

    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['skill_level']) && !empty($_POST['profile_pic'])) {
        $con = base::conect();
        $sql = "INSERT INTO Usuario(Nombre, CorreoElectronico, Contrasena, NivelHabilidad, FotoPerfil) VALUES (:nombre, :email, :contrasena, :skill_level, :profile_pic)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':nombre', $_POST['name']);
        $stmt->bindParam(':email', $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':contrasena', $password);
        $stmt->bindParam(':skill_level', $_POST['skill_level']);
        $stmt->bindParam(':profile_pic', $_POST['profile_pic']);

        if ($stmt->execute()) {
            $message = "Successfully created new user";
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

    <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <div>
        <h1>Enter your information</h1>
    </div>
    <div>
        <form class="form" action="" method="post">
            <div class="test">
            <label for="username">Name:</label>
            <input class="checkbox" type="text" id="name" name="name" required>
            </div>

            <div class="test">
            <label for="email">Email:</label>
            <input class="checkbox" type="email" id="email" name="email" required>
            </div>

            <div class="test">
            <label for="skill_level">Skill Level (1-5):</label>
            <input class="checkbox" type="number" id="skill_level" name="skill_level" min="1" max="5" required>
            </div>

            <div class="test">
            <label for="password">Password:</label>
            <input class="checkbox" type="password" id="password" name="password" required>
            </div>

            <div class="test">
            <label for="confirm_password">Confirm Password:</label>
            <input class="checkbox" type="password" id="confirm_password" name="confirm_password" require>
            </div>

            <div class="test">
            <label for="profile_pic">Profile picture:</label>
            <input class="checkbox" type="file" id="profile_pic" name="profile_pic" accept="image/*"><br><br>
            </div>

            <div>
                <input class="submit" type="submit" value="Sign In" name="submit_button">
                <a href="../Login/Login.php">Or Login</a>
            </div>
            
        </form>
    </div>
    
</body>
</html>