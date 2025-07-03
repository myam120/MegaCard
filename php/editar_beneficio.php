<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_beneficio'] ?? '';
    $empresa = $_POST['empresa'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $descuento = $_POST['descuento'] ?? '';
    try {
        $stmt = $conn->prepare("UPDATE beneficios SET
            empresa = ?, descripcion = ?, descuento = ?
            WHERE id_beneficio = ?");
        $stmt->execute([
            $empresa, $descripcion, $descuento, $id
        ]);

        header("Location: ../admin/modulo_Beneficios.php?success=editado");
        echo "✅ Beneficio editado exitosamente";
        exit;
    } catch (PDOException $e) {
        echo "Error al editar cliente: " . $e->getMessage();
    }
}
?>
