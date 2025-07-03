<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_premio'] ?? '';
    $nombre = $_POST['nombre_premio'] ?? '';
    $descripcion = $_POST['descripcion_premio'] ?? '';
    $puntos = $_POST['puntos_necesarios'] ?? 0;

    // Obtener imagen actual desde la base de datos
    $stmt = $conn->prepare("SELECT imagen FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $imagenAnterior = $resultado['imagen'] ?? '';

    $rutaImagen = $imagenAnterior;

    // Si se sube una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = 'uploads/' . $nombreArchivo;

        // Mover nueva imagen
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $rutaImagen = $nombreArchivo;

            // Eliminar imagen anterior del servidor si existe
            if (!empty($imagenAnterior) && file_exists('uploads/' . $imagenAnterior)) {
                unlink('uploads/' . $imagenAnterior);
            }
        } else {
            echo "❌ Error al mover la nueva imagen.";
            exit;
        }
    }

    try {
        $stmt = $conn->prepare("UPDATE Premios SET
            nombre_premio = ?, descripcion_premio = ?, puntos_necesarios = ?, imagen = ?
            WHERE id_premio = ?");
        $stmt->execute([
            $nombre, $descripcion, $puntos, $rutaImagen, $id
        ]);

        header("Location: ../admin/modulo_Premios.php?success=editado");
        echo "✅ Premio editado exitosamente";
        exit;
    } catch (PDOException $e) {
        echo "❌ Error al editar premio: " . $e->getMessage();
    }
}
?>
