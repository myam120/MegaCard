<?php
require '../php/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Captura método y contenido
$method = $_SERVER['REQUEST_METHOD'];
$input = file_get_contents("php://input");
$data = json_decode($input, true);
parse_str(file_get_contents("php://input"), $_PUT_OR_DELETE); // por si PUT o DELETE llegan como x-www-form-urlencoded

// Función auxiliar para responder
function responder($estado, $mensaje) {
    echo json_encode([$estado => $mensaje]);
    exit;
}

switch ($method) {

    case 'GET':
        // Consulta todos o uno por ?id=#
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
            $stmt->execute([$_GET['id']]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($cliente ?: ["mensaje" => "Cliente no encontrado"]);
        } else {
            $stmt = $conn->query("SELECT * FROM clientes");
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($clientes);
        }
        break;

    case 'POST':
        if (!is_array($data)) responder("error", "❌ No se pudo decodificar el JSON");

        $campos = ['nombre_cliente', 'apellidos_cliente', 'direccion_cliente', 'correo_cliente', 'telefono_cliente', 'estado_cliente', 'ciudad_cliente', 'puntos_cliente'];
        foreach ($campos as $campo) {
            if (empty($data[$campo])) responder("error", "❌ Falta el campo: $campo");
        }

        try {
            $stmt = $conn->prepare("INSERT INTO clientes (nombre_cliente, apellidos_cliente, direccion_cliente, correo_cliente, telefono_cliente, estado_cliente, ciudad_cliente, puntos_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre_cliente'],
                $data['apellidos_cliente'],
                $data['direccion_cliente'],
                $data['correo_cliente'],
                $data['telefono_cliente'],
                $data['estado_cliente'],
                $data['ciudad_cliente'],
                $data['puntos_cliente']
            ]);
            responder("mensaje", "✅ Cliente creado con éxito");
        } catch (PDOException $e) {
            responder("error", $e->getMessage());
        }
        break;

    case 'PUT':
        if (!isset($_GET['id'])) responder("error", "❌ Falta el parámetro id en la URL");

        $campos = ['nombre_cliente', 'apellidos_cliente', 'direccion_cliente', 'correo_cliente', 'telefono_cliente', 'estado_cliente', 'ciudad_cliente', 'puntos_cliente'];
        foreach ($campos as $campo) {
            if (empty($data[$campo])) responder("error", "❌ Falta el campo: $campo");
        }

        try {
            $stmt = $conn->prepare("UPDATE clientes SET nombre_cliente=?, apellidos_cliente=?, direccion_cliente=?, correo_cliente=?, telefono_cliente=?, estado_cliente=?, ciudad_cliente=?, puntos_cliente=? WHERE id_cliente=?");
            $stmt->execute([
                $data['nombre_cliente'],
                $data['apellidos_cliente'],
                $data['direccion_cliente'],
                $data['correo_cliente'],
                $data['telefono_cliente'],
                $data['estado_cliente'],
                $data['ciudad_cliente'],
                $data['puntos_cliente'],
                $_GET['id']
            ]);
            responder("mensaje", "✅ Cliente actualizado");
        } catch (PDOException $e) {
            responder("error", $e->getMessage());
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) responder("error", "❌ Falta el parámetro id en la URL");

        try {
            $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
            $stmt->execute([$_GET['id']]);
            responder("mensaje", "✅ Cliente eliminado");
        } catch (PDOException $e) {
            responder("error", $e->getMessage());
        }
        break;

    default:
        responder("error", "❌ Método no permitido");
}
