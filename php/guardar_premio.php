<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
// Validar que existan todos los campos
if (
    empty($_POST['nombre_premio']) ||
    empty($_POST['descripcion_premio']) ||
    empty($_POST['puntos_necesarios']) ||
    !isset($_FILES['imagen']) ||
    $_FILES['imagen']['error'] !== 0
) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Procesar imagen
$nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
$rutaDestino = 'uploads/' . $nombreImagen;

// Crear carpeta si no existe
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Mover imagen al destino
if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    echo json_encode(['success' => false, 'message' => 'Error al subir imagen']);
    exit;
}

// Extraer datos del formulario
$nombre = $_POST['nombre_premio'];
$descripcion = $_POST['descripcion_premio'];
$puntos = $_POST['puntos_necesarios'];

// Aquí va tu conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "megacard");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Insertar en la base de datos
$stmt = $conn->prepare("INSERT INTO premios (nombre_premio, descripcion_premio, puntos_necesarios, imagen) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $nombre, $descripcion, $puntos, $nombreImagen);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Premio guardado con éxito']);    
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar en base de datos']);
}

$stmt->close();
$conn->close();
