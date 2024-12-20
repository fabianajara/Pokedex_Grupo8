<?php
// Incluir la conexión a la base de datos
include 'dbConection.php';

header('Content-Type: application/json');

// Verificar la solicitud GET (obtener todos los Pokémon)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    try {
        $sql = "SELECT * FROM pokedexG8.pokemones";
        $stmt = $pdo->query($sql);
        $pokemones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($pokemones);
    } catch (Exception $e) {
        echo json_encode(['mensaje' => 'Error al obtener los Pokémon', 'error' => $e->getMessage()]);
    }
}

// Verificar la solicitud GET (obtener un Pokémon específico)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    try {
        $id = $_GET['id'];  // Obtener el ID del Pokémon
        $sql = "SELECT * FROM pokedexG8.pokemones WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pokemon) {
            echo json_encode($pokemon);
        } else {
            echo json_encode(['mensaje' => 'Pokémon no encontrado']);
        }
    } catch (Exception $e) {
        echo json_encode(['mensaje' => 'Error al obtener el Pokémon', 'error' => $e->getMessage()]);
    }
}

// Verificar la solicitud PUT (editar un Pokémon)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Obtener los datos en formato JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Depuración de los datos recibidos
        file_put_contents('php://stderr', print_r($data, TRUE));  // Esto imprime los datos en el log de errores

        // Verificar que los datos sean válidos
        if (isset($data['id'], $data['nombre'], $data['tipo'], $data['peso'], $data['tamano'])) {
            $id = $data['id'];
            $nombre = $data['nombre'];
            $tipo = $data['tipo'];
            $peso = $data['peso'];
            $tamano = $data['tamano'];

            // Verificar que los campos no estén vacíos
            if (empty($nombre) || empty($tipo) || empty($peso) || empty($tamano)) {
                echo json_encode(['mensaje' => 'Todos los campos son requeridos']);
                exit;
            }

            // Verificar los valores antes de la consulta
            file_put_contents('php://stderr', "ID: $id, Nombre: $nombre, Tipo: $tipo, Peso: $peso, Tamaño: $tamano\n");

            // Preparar la consulta para actualizar el Pokémon
            $sql = "UPDATE pokedexG8.pokemones SET nombre = :nombre, tipo = :tipo, peso = :peso, tamano = :tamano WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':peso', $peso);
            $stmt->bindParam(':tamano', $tamano);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                // Obtener el número de filas afectadas
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    echo json_encode(['mensaje' => 'Pokémon actualizado correctamente', 'rowCount' => $rowCount]);
                } else {
                    echo json_encode(['mensaje' => 'No se encontró el Pokémon o no hubo cambios']);
                }
            } else {
                echo json_encode(['mensaje' => 'Error al actualizar el Pokémon']);
            }
        } else {
            echo json_encode(['mensaje' => 'Faltan datos para actualizar el Pokémon']);
        }
    } catch (Exception $e) {
        echo json_encode(['mensaje' => 'Error al actualizar el Pokémon', 'error' => $e->getMessage()]);
    }
}

// Verificar la solicitud DELETE (eliminar un Pokémon)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        // Preparar la consulta para eliminar el Pokémon
        $sql = "DELETE FROM pokedexG8.pokemones WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['mensaje' => 'Pokémon eliminado correctamente']);
        } else {
            echo json_encode(['mensaje' => 'Error al eliminar el Pokémon']);
        }
    } catch (Exception $e) {
        echo json_encode(['mensaje' => 'Error al eliminar el Pokémon', 'error' => $e->getMessage()]);
    }
}
