<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_cliente'] ?? '';
    $telefono = $_POST['telefono_cliente'] ?? '';
    $nombre = $_POST['nombre_cliente'] ?? '';
    $apellidos = $_POST['apellidos_cliente'] ?? '';
    $direccion = $_POST['direccion_cliente'] ?? '';
    $correo = $_POST['correo_cliente'] ?? '';
    $estado = $_POST['estado_cliente'] ?? '';
    $ciudad = $_POST['ciudad_cliente'] ?? '';
    $puntos = $_POST['puntos_cliente'] ?? 0;
    $contrasena = $_POST['contrasena_cliente'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($contrasena !== $confirmar) {
        header("Location: ../admin/modulo_clientes.php?error=contrasena");
        exit;
    }

    try {
        $hashed = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE clientes SET
            telefono_cliente = ?, nombre_cliente = ?, apellidos_cliente = ?, direccion_cliente = ?, correo_cliente = ?,
            estado_cliente = ?, ciudad_cliente = ?, puntos_cliente = ?, contrasena_cliente = ?
            WHERE id_cliente = ?");
        $stmt->execute([
            $telefono, $nombre, $apellidos, $direccion, $correo,
            $estado, $ciudad, $puntos, $hashed, $id
        ]);

        header("Location: ../admin/modulo_clientes.php?success=editado");
        echo "✅ Cliente editado exitosamente";
        exit;
    } catch (PDOException $e) {
        echo "Error al editar cliente: " . $e->getMessage();
    }
}
?>
