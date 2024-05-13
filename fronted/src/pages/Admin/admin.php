<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../components/Header/Header.css">
    <link rel="stylesheet" href="./style1.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Admin Panel</title>
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
    
    <div class="admin-container">
    <h1>Panel de Administrador</h1>
        
        <div class="user-management">
            <h2>Gestión de Usuarios</h2>
            <?php
            require_once '../../../../backend/utils/conection.php';

            // Obtener la lista de usuarios de la base de datos
            $con = base::conect();
            $sql = "SELECT * FROM Usuario";
            $stmt = $con->prepare($sql);
            if ($stmt->execute()) {
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($users) {
                    echo '<table>';
                    echo '<tr><th>Nombre</th><th>Correo Electrónico</th><th>Nivel de Habilidad</th><th>Teléfono</th><th>Lado de Juego</th><th>Mano Dominante</th><th>Acción</th></tr>';
                    foreach ($users as $user) {
                        echo '<tr>';
                        echo '<td>' . $user['Nombre'] . '</td>';
                        echo '<td>' . $user['CorreoElectronico'] . '</td>';
                        echo '<td>' . $user['NivelHabilidad'] . '</td>';
                        echo '<td>' . $user['Telefono'] . '</td>';
                        echo '<td>' . $user['LadoJuego'] . '</td>';
                        echo '<td>' . $user['ManoDominante'] . '</td>';
                        echo '<td><a href="eliminar_usuario.php?id=' . $user['ID'] . '"><i class="fa-solid fa-trash"></i></a></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
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
            <?php
            require_once '../../../../backend/utils/conection.php';
            // Obtener la lista de partidos de la base de datos
            $sql = "SELECT * FROM Partido";
            $stmt = $con->prepare($sql);
            if ($stmt->execute()) {
                $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($matches) {
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Ubicación</th><th>Nivel de Habilidad Requerido</th><th>Acción</th></tr>';
                    foreach ($matches as $match) {
                        echo '<tr>';
                        echo '<td>' . $match['ID'] . '</td>';
                        echo '<td>' . $match['Fecha'] . '</td>';
                        echo '<td>' . $match['Hora'] . '</td>';
                        echo '<td>' . $match['Ubicacion'] . '</td>';
                        echo '<td>' . $match['NivelHabilidadRequerido'] . '</td>';
                        echo '<td><a href="eliminar_partido.php?id=' . $match['ID'] . '"><i class="fa-solid fa-trash"></i></a></td>';
                        echo '</tr>';
                        
                        // Eliminar reservas asociadas al partido
                        $sql_delete_reservas = "DELETE FROM Reserva WHERE Partido_ID = :partido_id";
                        $stmt_delete_reservas = $con->prepare($sql_delete_reservas);
                        $stmt_delete_reservas->bindParam(':partido_id', $match['ID'], PDO::PARAM_INT);
                        $stmt_delete_reservas->execute();
                        
                        // Eliminar el partido
                        $sql_delete_partido = "DELETE FROM Partido WHERE ID = :id";
                        $stmt_delete_partido = $con->prepare($sql_delete_partido);
                        $stmt_delete_partido->bindParam(':id', $match['ID'], PDO::PARAM_INT);
                        
                        echo '</tr>';
                    }
                    echo '</table>';
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
