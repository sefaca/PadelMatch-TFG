<?php
    require('../../../../backend/utils/conection.php');

    $message = "";

    if (isset($_POST['submit_button'])) {
        if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['skill_level']) && isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
            // Verificar que la contraseña y la confirmación de la contraseña coincidan
            if ($_POST['password'] === $_POST['confirm_password']) {
                $con = base::conect();
                
                // Establecer el rol del usuario como "usuario normal" por defecto
                $role = 'usuario normal';

                // Procesar la imagen subida
                $profile_pic = $_FILES['profile_pic']['name'];
                $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
                $upload_dir = '../../../../backend/utils/uploads/';
                move_uploaded_file($profile_pic_tmp, $upload_dir.$profile_pic);

                // Después de mover la imagen al directorio de carga
                $upload_dir = '../../../../backend/utils/uploads/';
                $profile_pic_path = $upload_dir.$profile_pic;

                
                $sql = "INSERT INTO Usuario (Nombre, CorreoElectronico, Contrasena, NivelHabilidad, FotoPerfil, Rol) VALUES (:nombre, :email, :contrasena, :skill_level, :profile_pic, :role)";
                $stmt = $con->prepare($sql);
                // Guardar la ruta completa de la imagen en la base de datos
                $stmt->bindParam(':profile_pic', $profile_pic_path);
                $stmt->bindParam(':nombre', $_POST['name']);
                $stmt->bindParam(':email', $_POST['email']);
                
                // Cifrar la contraseña antes de almacenarla en la base de datos
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt->bindParam(':contrasena', $password);
                
                $stmt->bindParam(':skill_level', $_POST['skill_level']);
                $stmt->bindParam(':profile_pic', $profile_pic);
                $stmt->bindParam(':role', $role);

                if ($stmt->execute()) {
                    $message = "Successfully created new user";
                } else {
                    $message = "Error creating user";
                }
            } else {
                $message = "Passwords do not match";
            }
        } else {
            $message = "All fields are required and profile picture must be uploaded";
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

    <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <div>
        <h1>Enter your information</h1>
    </div>
    <div>
        <form class="form" action="" method="post" enctype="multipart/form-data">
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
</body>
</html>
