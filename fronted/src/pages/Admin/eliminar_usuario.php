<?php
require_once '../../../../backend/utils/conection.php';

// Verificar si se ha enviado el ID del usuario a eliminar
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Eliminar las reservas relacionadas con partidos creados por el usuario
    $con = base::conect();
    $sql = "DELETE reserva FROM reserva JOIN partido ON reserva.Partido_ID = partido.ID WHERE partido.Usuario_ID = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Eliminar los partidos creados por el usuario
    $sql = "DELETE FROM partido WHERE Usuario_ID = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Eliminar los registros relacionados en la tabla `jugador`
    $sql = "DELETE FROM jugador WHERE Usuario_ID = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Eliminar las reservas asociadas al usuario
    $con = base::conect();
    $sql = "DELETE FROM reserva WHERE Usuario_ID = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Después de eliminar los registros relacionados en la tabla `reserva`, `partido`, y `jugador`,
    // ahora puedes eliminar el usuario de la tabla `usuario`
    $sql = "DELETE FROM Usuario WHERE ID = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userId', $userId);

    if ($stmt->execute()) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar el usuario.";
    }
} else {
    echo "ID de usuario no proporcionado en la URL.";
}
?>



