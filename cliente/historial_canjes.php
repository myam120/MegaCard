<?php
    session_start();
    require '../php/db.php'; 

    if (!isset($_SESSION['rol']) || !isset($_SESSION['telefono'])) {
        header("Location: ../index.php");
        exit();
    }

    // Obtener ID del cliente a partir del teléfono
    $telefono = $_SESSION['telefono'];
    $stmtCliente = $conn->prepare("SELECT id_cliente FROM clientes WHERE telefono_cliente = ?");
    $stmtCliente->execute([$telefono]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "⚠️ Cliente no encontrado.";
        exit();
    }

    $idCliente = $cliente['id_cliente'];

    // Obtener historial de canjes del cliente
    $stmt = $conn->prepare("
        SELECT c.fecha_canje AS fecha, p.nombre_premio 
        FROM canjes c 
        JOIN premios p ON c.premio_id = p.id_premio 
        WHERE c.cliente_id = ? 
        ORDER BY c.fecha_canje DESC
    ");
    $stmt->execute([$idCliente]);
    $canjes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Clientes - MegaCard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
   <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .card-digital {
      background: #ff9800;
      border-radius: 16px;
      padding: 20px;
      color: #121212;
      text-align: center;
    }
    .card-digital .phone {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .section-title {
      color: #ffc107;
      margin-top: 20px;
    }
    .premio-img {
      max-width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
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
            <a class="nav-link active" aria-current="page" href="historial_canjes.php">Historial de canjes</a>
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
<div class="mt-4">
  <h4 class="text-warning">🎁 Historial de Canjes</h4>
  <ul class="list-group bg-dark text-white">
    <div class="col-12 col-md-12 mb-3">
    <?php foreach ($canjes as $canje): ?>
      <li class="list-group-item bg-dark text-white">
        <?= htmlspecialchars($canje['nombre_premio']) ?> — <?= date('d/m/Y H:i', strtotime($canje['fecha'])) ?>
      </li>
    <?php endforeach; ?>
    </div>
  </ul>
</div>
</body>
</html>