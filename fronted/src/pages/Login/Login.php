<?php 

session_start();

require('../../../../backend/utils/conection.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $con = base::conect();
    $records = $con->prepare('SELECT id, CorreoElectronico, contrasena FROM Usuario WHERE CorreoElectronico=:email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['contrasena'])) {
        $_SESSION['user_id'] = $results['id'];
        header('Location: ../Profile/Profile.php');
    } else {
        $message = 'Sorry, doestn match';
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
            <a href="../Home/Home.php">Home</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
    <div>
        <h1>Log In</h1>
    </div>

    <?php if(!empty($message)): ?>
        <p><?php echo $message ?></p>
    <?php endif; ?>



    <form class="form" action="login.php" method="post">
        <label for="name">Email:</label>
        <input class="checkbox" type="text" id="email" name="email" required>
        <label for="name">Password:</label>
        <input class="checkbox" type="password" id="password" name="password" required>
        
        <input class="submit" type="submit" value="Login" name="login_button">
        <a href="../Register/Register.php">Or Register</a>
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