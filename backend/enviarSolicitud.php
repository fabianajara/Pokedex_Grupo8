<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require 'dbConection.php';

session_start();

try {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'No estás autenticado.']);
        exit();
    }

    // Obtener los datos
    $id_usuario = $_SESSION['id_usuario'];
    if (isset($_POST['id_amigo'])) {
        $id_amigo = $_POST['id_amigo'];

        // Verificar que el id_amigo no sea el mismo que el id_usuario
        if ($id_usuario == $id_amigo) {
            echo json_encode(['success' => false, 'message' => 'No puedes enviarte una solicitud de amistad a ti mismo.']);
            exit();
        }

        // Verificar si ya existe una solicitud pendiente o si ya son amigos
        $sql_check = "SELECT * FROM pokedexG8.Amigos WHERE (id_usuario = :id_usuario AND id_amigo = :id_amigo) OR (id_usuario = :id_amigo AND id_amigo = :id_usuario)";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':id_usuario', $id_usuario);
        $stmt_check->bindParam(':id_amigo', $id_amigo);

        if (!$stmt_check->execute()) {
            echo json_encode(['success' => false, 'message' => 'Error al verificar solicitud de amistad.']);
            exit();
        }

        $existingRequest = $stmt_check->fetch(PDO::FETCH_ASSOC);
        if ($existingRequest) {
            echo json_encode(['success' => false, 'message' => 'Ya existe una solicitud pendiente o ya son amigos.']);
            exit();
        }

        // Insertar la nueva solicitud de amistad
        $sql = "INSERT INTO pokedexG8.Amigos (id_usuario, id_amigo, aceptado) VALUES (:id_usuario, :id_amigo, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_amigo', $id_amigo);

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Error al enviar la solicitud de amistad.']);
            exit();
        }

        echo json_encode(['success' => true, 'message' => 'Solicitud de amistad enviada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se ha recibido el ID del amigo.']);
    }
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    error_log("Error al enviar solicitud de amistad: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
}
?>