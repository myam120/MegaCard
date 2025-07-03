<?php
session_start();
require '../php/db.php'; 

if (!isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit();
}

// Paginación
$por_pagina = 5; // Puedes cambiarlo a 10, 20, etc.
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina) - $por_pagina : 0;

// Total de registros
$total_stmt = $conn->query("SELECT COUNT(*) FROM beneficios");
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $por_pagina);

// Consulta paginada
$stmt = $conn->prepare("SELECT * FROM beneficios LIMIT :inicio, :por_pagina");
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$beneficios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Módulo Beneficios</title>
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
            <a class="nav-link active" aria-current="page" href="modulo_Beneficios.php">Beneficios</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="modulo_Premios.php">Premios</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="modulo_puntos.php">Alta puntos</a>
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

  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-warning">🏷️ Módulo de Beneficios</h2>
      <button class="btn btn-orange mb-4" data-bs-toggle="modal" data-bs-target="#modalBeneficio"><i class="fa-solid fa-plus"></i> Agregar Beneficio</button>
    </div>

    <!-- Tabla de Beneficios -->
    <div class="table-responsive">
      <table class="table table-dark table-bordered table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Descuento</th>
            <th width="16%">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($beneficios)): ?>
            <tr>
              <td colspan="4" class="text-center text-warning">No se encontraron beneficios registrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($beneficios as $beneficio): ?>
              <tr>
                <td><?= htmlspecialchars($beneficio['empresa']) ?></td>
                <td><?= htmlspecialchars($beneficio['descripcion']) ?></td>
                <td><?= htmlspecialchars($beneficio['descuento']) ?></td>
                <td>
                  <!-- Botón Editar -->
                  <button 
                    class="btn btn-sm btn-primary btn-editar-beneficio"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarBeneficio"
                    data-id="<?= $beneficio['id_beneficio'] ?>"
                    data-empresa="<?= htmlspecialchars($beneficio['empresa']) ?>"
                    data-descripcion="<?= htmlspecialchars($beneficio['descripcion']) ?>"
                    data-descuento="<?= htmlspecialchars($beneficio['descuento']) ?>">
                    <i class='fa-solid fa-pen-to-square'></i> Editar
                  </button>

                  <!-- Eliminar -->
                  <button class="btn btn-sm btn-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#modalEliminarBeneficio"
                          data-id="<?= $beneficio['id_beneficio'] ?>">
                    <i class='fa-solid fa-xmark'></i> Eliminar
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Paginación -->
      <nav aria-label="Paginación de beneficios">
        <ul class="pagination justify-content-center mt-4">
          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>

  </div>

   <!-- Modal Agregar Beneficio -->
  <div class="modal fade" id="modalBeneficio" tabindex="-1" aria-labelledby="modalBeneficioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title text-warning" id="modalBeneficioLabel"><i class="fa-solid fa-tags"></i> Agregar Nuevo Beneficio</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="../php/agregar_beneficio.php">
            <div class="mb-3">
              <label for="empresa" class="form-label text-warning">Empresa</label>
              <input type="text" class="form-control bg-dark text-white" id="empresa" name="empresa" placeholder="Ingresa el nombre de la empresa" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label text-warning">Descripción</label>
              <textarea class="form-control bg-dark text-white" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
              <label for="descuento" class="form-label text-warning">Descuento</label>
              <input type="text" class="form-control bg-dark text-white" id="descuento" name="descuento" required>
            </div>
          
            <div class="text-end">
              <button type="submit" class="btn btn-orange">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

   <!-- Modal Editar Beneficio -->
  <div class="modal fade" id="modalEditarBeneficio" tabindex="-1" aria-labelledby="modalBeneficioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title text-warning" id="modalBeneficioLabel"><i class='fa-solid fa-pen-to-square'></i> Editar Beneficio</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="../php/editar_beneficio.php">
            <input type="hidden" name="id_beneficio" id="editarIdBeneficio">
            <div class="mb-3">
              <label for="editarEmpresa" class="form-label text-warning">Empresa</label>
              <input type="text" class="form-control bg-dark text-white" id="editarEmpresa" name="empresa" placeholder="Ingresa el nombre de la empresa" required>
            </div>
            <div class="mb-3">
              <label for="editarDescripcion" class="form-label text-warning">Descripción</label>
              <textarea class="form-control bg-dark text-white" id="editarDescripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
              <label for="editarDescuento" class="form-label text-warning">Descuento</label>
              <input type="text" class="form-control bg-dark text-white" id="editarDescuento" name="descuento" required>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-orange">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Beneficio -->
  <div class="modal fade" id="modalEliminarBeneficio" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="../php/eliminar_beneficio.php">
        <input type="hidden" name="id_beneficio" id="eliminarIdBeneficio">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar este beneficio?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-orange">Eliminar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {

    // Editar
    document.querySelectorAll('.btn-editar-beneficio').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('editarIdBeneficio').value = btn.getAttribute('data-id');
        document.getElementById('editarEmpresa').value = btn.getAttribute('data-empresa');
        document.getElementById('editarDescripcion').value = btn.getAttribute('data-descripcion');
        document.getElementById('editarDescuento').value = btn.getAttribute('data-descuento');
      });
    });

    // Eliminar
    const modalEliminar = document.getElementById('modalEliminarBeneficio');
    modalEliminar.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      document.getElementById('eliminarIdBeneficio').value = id;
    });

  });
</script>

</body>
</html>