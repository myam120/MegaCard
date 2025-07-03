<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_cliente'] ?? null;

    if ($id) {
        try {
            $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
            $stmt->execute([$id]);
            header("Location: ../admin/modulo_clientes.php?success=eliminado");
            exit;
        } catch (PDOException $e) {
            echo "Error al eliminar cliente: " . $e->getMessage();
        }
    } else {
        header("Location: ../admin/modulo_clientes.php?error=no_id");
        exit;
    }
}
?>
