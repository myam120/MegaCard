<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['rol']) || !isset($_SESSION['telefono'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener puntos
$telefono = $_SESSION['telefono'];
$puntos = 0;
if ($telefono) {
    $stmt = $conn->prepare("SELECT puntos_cliente FROM clientes WHERE telefono_cliente = ?");
    $stmt->execute([$telefono]);
    $puntos = $stmt->fetchColumn() ?? 0;
}

// Obtener premios
$stmt = $conn->query("SELECT * FROM premios");
$premios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Todos los Premios - MegaCard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .premio-img {
      max-height: 150px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card {
      background-color: #1e1e1e;
    }
    .btn-canjear {
      background-color: #ffc107;
      color: #121212;
      font-weight: bold;
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
            <a class="nav-link" aria-current="page" href="historial_canjes.php">Historial de canjes</a>
          </li>       
        </ul>
      </div>
      <div class="d-flex">
        <span class="navbar-text me-3 text-white">
          <?php echo " " . " ". $_SESSION['rol']; ?>
        </span>
        <a href="cerrarSesion.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
      </div>
    </div>
  </nav>

<!-- Contenido -->
<div class="container py-5">
  <h2 class="text-center text-warning mb-4">🎁 Todos los Premios Disponibles</h2>
  <p class="text-center">Tienes <strong class="text-warning"><?php echo htmlspecialchars($puntos); ?></strong> puntos acumulados.</p>

  <div class="row">
    <?php foreach ($premios as $premio): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card h-100 text-white">
          <img src="../php/uploads/<?php echo htmlspecialchars($premio['imagen']); ?>" 
               class="card-img-top premio-img" alt="Premio">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-warning"><?php echo htmlspecialchars($premio['nombre_premio']); ?></h5>
            <p class="card-text small"><?php echo htmlspecialchars($premio['descripcion_premio']); ?></p>
            <p class="text-warning text-center">🎯 <?php echo (int)$premio['puntos_necesarios']; ?> puntos</p>
            <form method="POST" action="canjear_premio.php" class="text-center mt-auto">
              <input type="hidden" name="id_premio" value="<?= $premio['id_premio'] ?>">
              <button type="submit" class="btn btn-canjear btn-sm">Canjear</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-4">
    <a href="principal.php" class="btn btn-outline-warning">← Regresar a inicio</a>
  </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-center py-3 mt-auto">
  <small>© 2025 Programa de Fidelización. Todos los derechos reservados.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
