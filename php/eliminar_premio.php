<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_premio'] ?? null;

    if ($id) {
        try {       
            $stmt = $conn->prepare("SELECT imagen FROM Premios WHERE id_premio = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $imagen = $resultado['imagen'] ?? '';

            $rutaImagen = 'uploads/' . $imagen; 
            if (!empty($imagen) && file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }

            // 3. Eliminar el registro de la base de datos
            $stmt = $conn->prepare("DELETE FROM Premios WHERE id_premio = ?");
            $stmt->execute([$id]);

            header("Location: ../admin/modulo_Premios.php?success=eliminado");
            exit;
        } catch (PDOException $e) {
            echo "❌ Error al eliminar el premio: " . $e->getMessage();
        }
    } else {
        header("Location: ../admin/modulo_Premios.php?error=no_id");
        exit;
    }
}
?>
