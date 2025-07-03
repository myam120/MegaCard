<?php
session_start();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'php/db.php';

    $telefono = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar administrador
    $sql = "SELECT * FROM administrador WHERE telefono_administrador = ? AND password_administrador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Administrador';
        header("Location: public/admin/principal.php");
        exit;
    }

    // Validar cliente
    $sql = "SELECT * FROM clientes WHERE telefono_cliente = ? AND contrasena_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Cliente';
        $_SESSION['telefono'] = $telefono;
        header("Location: cliente/principal.php");
        exit;
    }

    $mensaje = "❌ Credenciales incorrectas.";
}
?>