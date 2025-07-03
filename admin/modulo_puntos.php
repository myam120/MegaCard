<?php
    session_start();
    require '../php/db.php'; 

    if (!isset($_SESSION['rol'])) {
        header("Location: ../index.php");
        exit();
    }
    // Obtener lista de clientes
    $stmt = $conn->query("SELECT telefono_cliente, nombre_cliente, apellidos_cliente FROM clientes ORDER BY nombre_cliente ASC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alta de Puntos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    body {
      background-color: #121212;
      color: #f1f1f1;
    }

    .btn-orange {
      background-color: #ff9800;
      border: none;
      color: white;
    }

    .btn-orange:hover {
      background-color: #ffa733;
    }

    .card-premio {
      background-color: #1e1e1e;
      border: 1px solid #ff980050;
      border-radius: 10px;
    }

    .table thead {
      color: #ffc107;
    }

    @media (max-width: 768px) {
      .premio-card-container {
        display: block;
      }
    }
  </style>
</head>
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
            <a class="nav-link active" aria-current="page" href="modulo_puntos.php">Alta puntos</a>
          </li>  
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="historial_canjes.php">Historial de Canjes</a>
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

<body class="bg-dark text-white">
  <div class="container mt-5">
    <h2 class="text-warning mb-4">Asignar Puntos por Compra</h2>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" id="mensajeAlerta">
                ✅ Se han asignado correctamente <?= htmlspecialchars($_GET['puntos']) ?> puntos al cliente.
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger" id="mensajeAlerta">
                ❌ Error al procesar los puntos. Revisa los datos ingresados.
            </div>
        <?php endif; ?>

    <form action="../php/procesar_puntos.php" method="POST" class="card p-4 bg-secondary">
      <div class="mb-3">
        <label for="telefono" class="form-label">Seleccionar Cliente</label>
        <select class="form-select" name="telefono" required>
          <option value="">-- Selecciona un cliente --</option>
          <?php foreach ($clientes as $cliente): ?>
            <option value="<?= $cliente['telefono_cliente'] ?>">
              <?= $cliente['nombre_cliente'] . ' ' . $cliente['apellidos_cliente'] ?> (<?= $cliente['telefono_cliente'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="monto" class="form-label">Monto de la Compra (MXN)</label>
        <input type="number" name="monto" class="form-control" min="0" step="1" required>
      </div>

      <button type="submit" class="btn btn-orange">Asignar Puntos</button>
    </form>
  </div>

  <script>
  // Oculta el mensaje después de 4 segundos (4000 milisegundos)
  setTimeout(() => {
    const alerta = document.getElementById('mensajeAlerta');
    if (alerta) {
      alerta.style.display = 'none';
    }
  }, 3000);
</script>
</body>
</html>
