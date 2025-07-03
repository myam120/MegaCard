<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_beneficio'] ?? null;

    if ($id) {
        try {
            $stmt = $conn->prepare("DELETE FROM beneficios WHERE id_beneficio = ?");
            $stmt->execute([$id]);
            header("Location: ../admin/modulo_Beneficios.php?success=eliminado");
            exit;
        } catch (PDOException $e) {
            echo "Error al eliminar beneficio: " . $e->getMessage();
        }
    } else {
        header("Location: ../admin/modulo_Beneficios.php?error=no_id");
        exit;
    }
}
?>
