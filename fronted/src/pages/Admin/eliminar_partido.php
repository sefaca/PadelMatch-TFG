<?php
// Verificar si se ha proporcionado un ID de partido válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    require_once '../../../../backend/utils/conection.php';

    // Obtener el ID de partido desde la URL
    $matchId = $_GET['id'];

    // Eliminar el partido de la base de datos
    $con = base::conect();
    $sql = "DELETE FROM Partido WHERE ID = :matchId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':matchId', $matchId);
    if ($stmt->execute()) {
        // Redirigir de vuelta al panel de administrador
        header("Location: admin.php");
        exit();
    } else {
        // Manejar el error si falla la eliminación
        echo "Error al eliminar el partido.";
    }
} else {
    // Manejar el caso en que no se proporcione un ID de partido válido
    echo "ID de partido no válido.";
}
?>
