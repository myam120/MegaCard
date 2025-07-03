<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $telefono = $_POST['telefono_cliente'] ?? '';
    $nombre = $_POST['nombre_cliente'] ?? '';
    $apellidos = $_POST['apellidos_cliente'] ?? '';
    $direccion = $_POST['direccion_cliente'] ?? '';
    $correo = $_POST['correo_cliente'] ?? '';
    $estado = $_POST['estado_cliente'] ?? '';
    $ciudad = $_POST['ciudad_cliente'] ?? '';
    $contrasena = $_POST['contrasena_cliente'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($contrasena !== $confirmar) {
        // Puedes redirigir o mostrar mensaje
        header("Location: ../admin/modulo_clientes.php?error=1");
        exit;
    }

    try {
         // Opcional: encriptar contraseña con password_hash
        $hashed = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO clientes 
            (telefono_cliente, nombre_cliente, apellidos_cliente, direccion_cliente, correo_cliente, estado_cliente, ciudad_cliente, contrasena_cliente)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$telefono, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad, $hashed]);

        header("Location: ../admin/modulo_clientes.php?success=1");
        echo "✅ Cliente registrado correctamente.";
        exit;
    } catch (PDOException $e) {
        echo "Error al registrar cliente: " . $e->getMessage();
    }
}
?>
