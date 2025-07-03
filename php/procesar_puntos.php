<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefono = $_POST['telefono'] ?? '';
    $monto = floatval($_POST['monto'] ?? 0);

    if ($telefono && $monto >= 0) {
        // Calcular puntos: 5 puntos por cada $100
        $puntos_nuevos = floor($monto / 100) * 5;

        // Actualizar en la BD
        $stmt = $conn->prepare("UPDATE clientes SET puntos_cliente = puntos_cliente + ? WHERE telefono_cliente = ?");
        $stmt->execute([$puntos_nuevos, $telefono]);

        // Redirigir con mensaje
        header("Location: ../admin/modulo_puntos.php?success=1&puntos=$puntos_nuevos");
        exit;
    } else {
        header("Location: ../admin/modulo_puntos.php?error=1");
        exit;
    }
}
?>
