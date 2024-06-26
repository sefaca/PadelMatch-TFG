<?php
    session_start();

    require('../../../../backend/utils/conection.php');

    if (isset($_SESSION['user_id'])) {
        $con = base::conect();
        $records = $con->prepare('SELECT id, CorreoElectronico, contrasena, nombre FROM Usuario WHERE id = :id');
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $user = null;

        if (is_array($results) && count($results) > 0) {
            $user = $results;
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
        <nav class="nav">
            <img class="logo" src="../../../../backend/utils/posibleLogo1.png" />
            <div class="links">
                <a href="../Login/Login.php">Login</a>
                <a href="../Register/Register.php">Sing Up</a>
                <a class="icon-nav-profile" href="../Profile/Profile.php">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
        </nav>
    </header>
    <div>
        <h1>Monta tus partidos de pádel</h1>
        <section class="grid-container">
            <article>
                <img src="../../../../backend/utils/personas-sentadas-al-aire-libre-bebidas-full-shot.jpg" alt="Imagen 1">
            </article>
            <article>
                <img class="img2" src="../../../../backend/utils/gente-jugando-al-padel-dentro.jpg" alt="Imagen 2">
            </article>
            <article>
                <img src="../../../../backend/utils/paleta-alto-angulo-bolas-campo.jpg" alt="Imagen 3">
            </article>
            
            <article>
                <p>¡Bienvenido a PadelMatch! Somos la plataforma ideal para organizar partidos de pádel que se adaptan a tu nivel y preferencias. Simplificamos la búsqueda de compañeros de juego, asegurando partidos equilibrados y divertidos para todos los amantes del pádel. Conéctate fácilmente con jugadores afines y disfruta de una experiencia única en la cancha</p>
            </article>
            <article>
                <p>En PadelMatch, creamos una comunidad apasionada de jugadores de pádel donde encuentras partidos a tu medida. Desde aquellos que buscan jugadores de nivel similar hasta los que buscan retos competitivos, nuestra plataforma facilita la organización de partidos personalizados, uniendo a aficionados de todos los niveles para disfrutar del deporte y la amistad.</p>
            </article>
            <article>
                <p>Descubre una nueva forma de disfrutar del pádel con PadelMatch. Nuestra plataforma te permite crear, unirte y organizar partidos con jugadores que comparten tu pasión. Olvídate de las complicaciones al encontrar o crear partidos; aquí, la diversión y la amistad van de la mano. Únete a nuestra comunidad y experimenta el pádel como nunca antes.</p>
            </article>
        </section>
        
        <?php if(!empty($user)): ?>
            <div class="welcome-message">
                <br>Bienvenido <?= $user['nombre'] ?>
                <br>Te has logueado correctamente.
            </div>
            
            <a class="button-logout" href="../Logout/Logout.php">Logout</a>
        <?php else: ?>
        <div class="buttons">
            <a href="../Register/Register.php"><button>Sign Up</button></a>
            <a href="../Login/Login.php"><button>Log In</button></a>
        </div>
        <?php endif; ?>
    </div>
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