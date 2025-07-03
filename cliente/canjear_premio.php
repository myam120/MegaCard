<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['telefono'])) {    

}
$idPremio = $_POST['id_premio'] ?? null;
$telefono = $_SESSION['telefono'];

// Verificar datos obligatorios
if (!$telefono || !$idPremio) {
    header("Location: principal.php?error=DatosIncompletos");
    exit;
}

// 1. Obtener ID del cliente y puntos actuales
$stmt = $conn->prepare("SELECT id_cliente, puntos_cliente FROM clientes WHERE telefono_cliente = ?");
$stmt->execute([$telefono]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header("Location: principal.php?error=ClienteNoExiste");
    exit;
}

$idCliente = $cliente['id_cliente'];
$puntosCliente = (int) $cliente['puntos_cliente'];

// 2. Obtener información del premio
$stmt = $conn->prepare("SELECT puntos_necesarios FROM premios WHERE id_premio = ?");
$stmt->execute([$idPremio]);
$premio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$premio) {
    header("Location: principal.php?error=PremioNoExiste");
    exit;
}

$puntosRequeridos = (int) $premio['puntos_necesarios'];

// 3. Verificar si tiene puntos suficientes
if ($puntosCliente < $puntosRequeridos) {
    header("Location: principal.php?error=PuntosInsuficientes");
    exit;
}

// 4. Descontar puntos
$stmt = $conn->prepare("UPDATE clientes SET puntos_cliente = puntos_cliente - ? WHERE id_cliente = ?");
$stmt->execute([$puntosRequeridos, $idCliente]);

// 5. Registrar el canje
$stmt = $conn->prepare("INSERT INTO canjes (cliente_id, premio_id) VALUES (?, ?)");
$stmt->execute([$idCliente, $idPremio]);

header("Location: principal.php?success=PremioCanjeado");
exit;
?>
