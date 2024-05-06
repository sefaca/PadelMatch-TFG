<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../components/Header/Header.css">
    <link rel="stylesheet" href="./style1.css">
    <title>Admin Panel</title>
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
    
    <div class="admin-container">
    <h1>Panel de Administrador</h1>
    
    <div class="user-management">
        <h2>Gestión de Usuarios</h2>
        <!-- Aquí puedes mostrar la lista de usuarios registrados -->
        <?php
        require_once '../../../../backend/utils/conection.php';

        // Obtener la lista de usuarios de la base de datos
        $con = base::conect();
        $sql = "SELECT * FROM Usuario";
        $stmt = $con->prepare($sql);
        if ($stmt->execute()) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($users) {
                echo '<ul>';
                foreach ($users as $user) {
                    echo '<li>' . $user['Nombre'] . ' - ' . $user['CorreoElectronico'] .  ' - ' . $user['NivelHabilidad'] . ' - ' . $user['Telefono'] . ' - ' . $user['LadoJuego'] . ' - ' . $user['ManoDominante'] .'</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No se encontraron usuarios.</p>';
            }
        } else {
            echo '<p>Error al recuperar la lista de usuarios.</p>';
        }
        ?>
    </div>

    <div class="match-management">
        <h2>Gestión de Partidos</h2>
        <!-- Aquí puedes mostrar la lista de partidos creados -->
        <?php
        // Obtener la lista de partidos de la base de datos
        $sql = "SELECT * FROM Partido";
        $stmt = $con->prepare($sql);
        if ($stmt->execute()) {
            $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($matches) {
                echo '<ul>';
                foreach ($matches as $match) {
                    echo '<li>' . $match['ID'] . ' - ' . $match['Fecha'] . ' - ' . $match['Hora'] . ' - ' . $match['Ubicacion'] . ' - ' . $match['NivelHabilidadRequerido'] .'</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No se encontraron partidos.</p>';
            }
        } else {
            echo '<p>Error al recuperar la lista de partidos.</p>';
        }
        ?>
    </div>
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
