<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require 'dbConection.php';

try {
    if (isset($_POST['nombre_usuario'])) {
        $nombre_usuario = trim($_POST['nombre_usuario']);
        
        // Verificar si el nombre de usuario es válido
        if (empty($nombre_usuario)) {
            echo json_encode(['exito' => false, 'mensaje' => 'El nombre de usuario no puede estar vacío.']);
            exit();
        }

        // Preparamos la consulta para buscar usuarios por nombre
        $sql = "SELECT id_usuario, nombre, username, usuario_imagen FROM pokedexG8.Usuario WHERE nombre LIKE ? OR username LIKE ?";
        $stmt = $pdo->prepare($sql);
        $nombre_usuario_param = "%" . $nombre_usuario . "%"; // Permitimos buscar nombres y usernames parciales
        $stmt->bindParam(1, $nombre_usuario_param);
        $stmt->bindParam(2, $nombre_usuario_param);

        if (!$stmt->execute()) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al ejecutar la consulta de búsqueda.']);
            exit();
        }

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($usuarios) > 0) {
            echo json_encode(['exito' => true, 'usuarios' => $usuarios]);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'No se encontraron usuarios con ese nombre.']);
        }
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se ha recibido el nombre de usuario.']);
    }
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    error_log("Error en la búsqueda de usuario: " . $e->getMessage());
    echo json_encode(['exito' => false, 'mensaje' => 'Error en el servidor']);
}
?>