<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../index.php");
    exit();
}

// Obtener todos los canjes con información del cliente y premio
$stmt = $conn->prepare("
    SELECT 
        c.fecha_canje,
        cl.nombre_cliente,
        cl.apellidos_cliente,
        cl.telefono_cliente,
        p.nombre_premio,
        p.puntos_necesarios
    FROM canjes c
    JOIN clientes cl ON c.cliente_id = cl.id_cliente
    JOIN premios p ON c.premio_id = p.id_premio
    ORDER BY c.fecha_canje DESC
");
$stmt->execute();
$canjes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Canjes - Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .table thead {
      color: #ffc107;
    }
    .table {
      background-color: #1e1e1e;
    }
  </style>
</head>
<body>
 <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-warning">
    <div class="container-fluid">
      <img src="../img/logo-Photoroom.png" width="200px" alt="">
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="principal.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="Modulo_Clientes.php">Clientes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="modulo_Beneficios.php">Beneficios</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="modulo_Premios.php">Premios</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="modulo_puntos.php">Alta puntos</a>
          </li>
           <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="historial_canjes.php">Historial de Canjes</a>
          </li>                   
        </ul>
      </div>
      <div class="d-flex">
        <span class="navbar-text me-3">
          <?php echo " " . " ". $_SESSION['rol']; ?>
        </span>
        <a href="cerrarSesion.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
      </div>
    </div>
  </nav>
<div class="container mt-5">
  <h3 class="text-warning mb-4">🎁 Historial de Canjes</h3>
  
  <div class="table-responsive">
    <table class="table table-dark table-bordered table-hover">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Premio</th>
          <th>Puntos Canjeados</th>
          <th>Fecha de Canje</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($canjes as $canje): ?>
          <tr>
            <td><?= htmlspecialchars($canje['nombre_cliente'] . ' ' . $canje['apellidos_cliente']) ?></td>
            <td><?= htmlspecialchars($canje['telefono_cliente']) ?></td>
            <td><?= htmlspecialchars($canje['nombre_premio']) ?></td>
            <td><?= (int)$canje['puntos_necesarios'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($canje['fecha_canje'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
