<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrador - MegaCard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
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
            <a class="nav-link" aria-current="page" href="modulo_puntos.php">Alta de puntos</a>
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

  <!-- Contenido principal -->
  <div class="container my-5">
    <h2 class="mb-4">Bienvenido, Administrador</h2>
    <p>Selecciona una opción para comenzar:</p>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card h-100 bg-dark text-white">
          <div class="card-body">
            <div class="card-icon mb-2"><i class="fa-solid fa-users"></i> </div>
            <h5 class="card-title">Gestión de Clientes</h5>
            <p class="card-text">Altas, bajas, modificaciones y asignación de puntos.</p>
            <a href="Modulo_Clientes.php" class="btn btn-warning">Ir al módulo</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card h-100 bg-dark text-white">
          <div class="card-body">
            <div class="card-icon mb-2">🎁</div>
            <h5 class="card-title">Gestión de Premios</h5>
            <p class="card-text">Crear, editar y eliminar premios disponibles.</p>
            <a href="modulo_Premios.php" class="btn btn-warning">Ir al módulo</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card h-100 bg-dark text-white">
          <div class="card-body">
            <div class="card-icon mb-2">🏷️</div>
            <h5 class="card-title">Gestión de Beneficios</h5>
            <p class="card-text">Administrar empresas asociadas y sus beneficios.</p>
            <a href="modulo_Beneficios.php" class="btn btn-warning">Ir al módulo</a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card h-100 bg-dark text-white">
          <div class="card-body">
            <div class="card-icon mb-2">📈</div>
            <h5 class="card-title">Historial de canjes</h5>
            <p class="card-text">Visualiza datos como clientes frecuentes y premios más canjeados.</p>
            <a href="historial_canjes.php" class="btn btn-warning">Ir al módulo</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-center py-3 mt-auto">
    <small>© 2025 Programa de Fidelización. Todos los derechos reservados.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>