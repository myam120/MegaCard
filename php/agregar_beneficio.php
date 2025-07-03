<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $empresa = $_POST['empresa'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $descuento = $_POST['descuento'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO beneficios (empresa, descripcion, descuento) 
            VALUES (?, ?, ?)");
        $stmt->execute([$empresa, $descripcion, $descuento]);           

        header("Location: ../admin/modulo_Beneficios.php?success=1");
        echo "✅ Beneficio registrado correctamente.";
        exit;
    } catch (PDOException $e) {
        echo "Error al registrar cliente: " . $e->getMessage();
    }
}
?>
