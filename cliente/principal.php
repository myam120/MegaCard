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

    // Obtener premios de la base de datos
  $stmt = $conn->query("SELECT * FROM premios");
  $premios = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Obtener beneficios desde la base de datos
  $stmt_b = $conn->query("SELECT * FROM beneficios");
  $beneficios = $stmt_b->fetchAll(PDO::FETCH_ASSOC);
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
            <a class="nav-link active" aria-current="page" href="#">Inicio</a>
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

 <div class="container py-4">
  <div class="text-center mb-4">
    <h2>Mi cuenta</h2>
    <p class="text-warning">
       Tienes <strong><?php echo htmlspecialchars($puntos); ?></strong> puntos acumulados.
    </p>
    
    <p>Número de tarjeta:</p>
     <strong style="font-size: 1.5rem;"><?php echo " " . " ". $_SESSION['telefono']; ?></strong>
  </div>
   <!-- Tarjeta Digital -->
  <figure class="text-center"><img src="../img/tarjeta.jpeg" width="400px" alt=""></figure>  

  <!-- Premios -->
<h4 class="section-title text-center mb-4">🎁 Premios Disponibles</h4>
<div id="carouselPremios" class="carousel slide mb-4" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php foreach ($premios as $index => $premio): ?>
    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
      <div class="d-flex justify-content-center">
        <div class="card bg-dark text-white" style="width: 20rem;">
          <img src="../php/uploads/<?php echo htmlspecialchars($premio['imagen']); ?>" 
              class="premio-img card-img-top" alt="Premio">
          <div class="card-body text-center">
            <h5 class="card-title text-warning"><?php echo htmlspecialchars($premio['nombre_premio']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($premio['descripcion_premio']); ?></p>
            <p class="card-text text-warning">Canjea por <?php echo (int)$premio['puntos_necesarios']; ?> puntos</p>
            <form method="POST" action="canjear_premio.php">
              <input type="hidden" name="id_premio" value="<?= $premio['id_premio'] ?>">
              <button type="submit" class="btn btn-warning">Canjear</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselPremios" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselPremios" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

  <!-- Botón para ir a la vista completa de premios -->
  <div class="text-center mb-5">
    <a href="vista_premios.php" class="btn btn-outline-warning">Ir a vista premios</a>
  </div>



  <!-- Beneficios -->
  <h4 class="section-title text-center mb-4">💎 Beneficios</h4>
  <div class="list-group mb-5">
    <?php foreach ($beneficios as $beneficio): ?>
      <div class="col-12 col-md-12 mb-3">
        <div class="card bg-dark text-white h-100">
          <div class="card-body d-flex align-items-center">            
            <div>
              <h5 class="card-title text-warning mb-1"><?php echo htmlspecialchars($beneficio['empresa']); ?></h5>
              <p class="card-text small mb-1"><?php echo htmlspecialchars($beneficio['descripcion']); ?></p>
              <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($beneficio['descuento']); ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>    
    <figure class="text-center"><img src="../img/MegaCard.jpg" width="80%" alt=""></figure>
  </div>
</div>
  <!-- Footer -->
  <footer class="bg-dark text-center py-3 mt-auto">
    <small>© 2025 Programa de Fidelización. Todos los derechos reservados.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
