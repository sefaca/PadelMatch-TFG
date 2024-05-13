<?php
session_start(); // Iniciar la sesión

// Verificar si se proporciona un ID de reserva válido en la URL
if (isset($_GET['reserva_id'])) {
    $reserva_id = $_GET['reserva_id'];

    require_once '../../../../backend/utils/conection.php';
    $con = base::conect();

    // Consulta SQL para obtener la información de la reserva utilizando el ID proporcionado
    $sql_reserva = "SELECT Fecha, Hora, Usuario_ID FROM Reserva WHERE ID = :reserva_id";
    $stmt_reserva = $con->prepare($sql_reserva);
    $stmt_reserva->bindParam(':reserva_id', $reserva_id);
    
    if ($stmt_reserva->execute()) {
        $reserva_data = $stmt_reserva->fetch(PDO::FETCH_ASSOC);
        if ($reserva_data) {
            $fecha_reserva = $reserva_data['Fecha'];
            $hora_reserva = $reserva_data['Hora'];
            $usuario_id_reserva = $reserva_data['Usuario_ID'];

            // Verificar si el usuario ya tiene una reserva para este partido
            if ($usuario_id_reserva != null) {
                echo "Error: Ya tienes una reserva para este partido.";
            } else {
                // Si el usuario no tiene una reserva para este partido, procede con la reserva
                $userId = $_SESSION['user_id']; // Obtener el ID del usuario actual
                $sql_update_reserva = "UPDATE Reserva SET Usuario_ID = :userId WHERE ID = :reserva_id";
                $stmt_update_reserva = $con->prepare($sql_update_reserva);
                $stmt_update_reserva->bindParam(':userId', $userId);
                $stmt_update_reserva->bindParam(':reserva_id', $reserva_id);
                
                if ($stmt_update_reserva->execute()) {
                    echo "¡Reserva realizada con éxito!";
                    // Redireccionar a la página de perfil después de 3 segundos
                    echo "<script>setTimeout(function(){ window.location.href = '../Profile/Profile.php#reservas'; }, 3000);</script>";
                } else {
                    echo "Error al realizar la reserva.";
                }
            }
        } else {
            // Si no se encuentra la reserva correspondiente, muestra un mensaje de error
            echo "Error: No se encontró la reserva correspondiente.";
        }
    } else {
        // Si hay un error al ejecutar la consulta, muestra un mensaje de error
        echo "Error al ejecutar la consulta.";
    }
} else {
    // Si no se proporciona un ID de reserva válido, muestra un mensaje de error
    echo "Error: No se proporcionó un ID de reserva válido.";
}
?>
