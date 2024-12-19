<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require 'dbConection.php'; // Asegúrate de tener la conexión a la base de datos aquí

try {
    // Obtener los datos del formulario
    $data = $_POST;

    $nombre = trim($data['nombre']);
    $username = trim($data['username']);
    $password = trim($data['password']);
    $rol = trim($data['rol']); // El rol se enviará como un número: 1, 2, o 3.
    $usuario_imagen = isset($data['usuario_imagen']) ? trim($data['usuario_imagen']) : null; // Imagen como URL opcional

    if (empty($nombre) || empty($username) || empty($password) || empty($rol)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit();
    }

    // Validar el rol para asegurarse de que es válido
    if (!in_array($rol, [1, 2, 3])) {
        echo json_encode(['success' => false, 'message' => 'Rol no válido']);
        exit();
    }

    // Validar si el nombre de usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pokedexG8.Usuario WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está en uso.']);
        exit();
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validar la URL de la imagen (si se proporciona)
    if ($usuario_imagen && !filter_var($usuario_imagen, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => 'La URL de la imagen no es válida']);
        exit();
    }

    // Preparar la consulta para insertar el nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO pokedexG8.Usuario (nombre, username, contrasena, rol, usuario_imagen) 
                            VALUES (:nombre, :username, :contrasena, :rol, :usuario_imagen)");

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':contrasena', $hashedPassword);
    $stmt->bindParam(':rol', $rol);

    // Si se proporcionó una URL de imagen, la vinculamos
    if ($usuario_imagen) {
        $stmt->bindParam(':usuario_imagen', $usuario_imagen);
    } else {
        // Si no hay URL de imagen, establecer como NULL
        $nullValue = null;
        $stmt->bindParam(':usuario_imagen', $nullValue, PDO::PARAM_NULL);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
