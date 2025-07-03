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
  $total_stmt = $conn->query("SELECT COUNT(*) FROM premios");
  $total_registros = $total_stmt->fetchColumn();
  $total_paginas = ceil($total_registros / $por_pagina);

  // Consulta paginada
  $stmt = $conn->prepare("SELECT * FROM premios LIMIT :inicio, :por_pagina");
  $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
  $stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
  $stmt->execute();
  $premios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Premios - Admin</title>
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
            <a class="nav-link" aria-current="page" href="modulo_Beneficios.php">Beneficios</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="modulo_Premios.php">Premios</a>
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
      <h2 class="text-warning"><i class="fa-solid fa-trophy"></i> Gestión de Premios</h2>
      <button class="btn btn-orange mb-4" data-bs-toggle="modal" data-bs-target="#modalPremio">+ Agregar Premio</button>
    </div>

    <!-- Tabla premios -->
    <div class="table-responsive">
      <table class="table table-dark table-bordered table-hover">
        <thead>
          <tr>
            <th></th>
            <th>Premio</th>
            <th>Descripción</th>
            <th>Puntos</th>
            <th width="16%">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($premios)): ?>
            <tr>
              <td colspan="5" class="text-center text-warning">No se encontraron premios registrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($premios as $Premio): ?>
              <tr>
                <td><img src="../php/uploads/<?= htmlspecialchars($Premio['imagen']) ?>" alt="Imagen del premio" width="60"></td>
                <td><?= htmlspecialchars($Premio['nombre_premio']) ?></td>
                <td><?= htmlspecialchars($Premio['descripcion_premio']) ?></td>
                <td><?= (int)$Premio['puntos_necesarios'] ?></td>
                <td>
                  <button class="btn btn-sm btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#modalEditarPremio"
                          data-id="<?= $Premio['id_premio'] ?>"
                          data-nombre="<?= htmlspecialchars($Premio['nombre_premio']) ?>"
                          data-descripcion="<?= htmlspecialchars($Premio['descripcion_premio']) ?>"
                          data-puntos="<?= (int)$Premio['puntos_necesarios'] ?>"
                          data-imagen="<?= htmlspecialchars($Premio['imagen']) ?>">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                  </button>
                  <button class="btn btn-sm btn-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#modalEliminarPremio"
                          data-id="<?= $Premio['id_premio'] ?>">
                    <i class="fa-solid fa-xmark"></i> Eliminar
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

  <!-- Modal Agregar Premio -->
  <div class="modal fade" id="modalPremio" tabindex="-1" aria-labelledby="modalPremioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title text-warning" id="modalPremioLabel">Agregar Nuevo Premio</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAgregarPremio" enctype="multipart/form-data" method="POST">
            <div class="mb-3">
              <label for="nombrePremio" class="form-label text-warning">Nombre del premio</label>
              <input type="text" class="form-control bg-dark text-white" id="nombrePremio" name="nombre_premio" placeholder="Ingresa el nombre del premio" required>
            </div>
            <div class="mb-3">
              <label for="descripcionPremio" class="form-label text-warning">Descripción</label>
              <textarea class="form-control bg-dark text-white" id="descripcionPremio" name="descripcion_premio" required></textarea>
            </div>
            <div class="mb-3">
              <label for="puntosPremio" class="form-label text-warning">Puntos necesarios</label>
              <input type="number" class="form-control bg-dark text-white" id="puntosPremio" name="puntos_necesarios" required>
            </div>
            <div class="mb-3">
              <label for="imagenPremio" class="form-label text-warning">Imagen del premio</label>
              <input type="file" class="form-control bg-dark text-white" id="imagenPremio" name="imagen" accept="image/*" required>

            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-orange">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Premio -->
  <div class="modal fade" id="modalEditarPremio" tabindex="-1" aria-labelledby="modalPremioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title text-warning" id="modalPremioLabel">Editar Premio</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="../php/editar_premio.php" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nombrePremio" class="form-label text-warning">Nombre del premio</label>
              <input type="text" class="form-control bg-dark text-white" id="nombrePremio" name="nombre_premio" >
            </div>
            <div class="mb-3">
              <label for="descripcionPremio" class="form-label text-warning">Descripción</label>
              <textarea class="form-control bg-dark text-white" id="descripcionPremio" name="descripcion_premio" ></textarea>
            </div>
            <div class="mb-3">
              <label for="puntosPremio" class="form-label text-warning">Puntos necesarios</label>
              <input type="number" class="form-control bg-dark text-white" id="puntosPremio" name="puntos_necesarios" >
            </div>
            <div class="mb-3">
              <label class="form-label text-warning">Imagen actual</label><br>
              <img id="imagenActualPreview" src="" alt="Imagen actual" class="img-thumbnail mb-2" width="100">
              <input type="file" class="form-control bg-dark text-white" id="imagenPremio" name="imagen" accept="image/*">

            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-orange">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Premio -->
  <div class="modal fade" id="modalEliminarPremio" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="../php/eliminar_premio.php">
        <input type="hidden" name="id_premio" id="eliminarIdPremio">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar este premio?
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
  
    // MODAL EDITAR PREMIO
    const modalEditar = document.getElementById('modalEditarPremio');
    if (modalEditar) {
      modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const modal = this;

        // Llenar los campos del formulario con los datos del botón
        modal.querySelector('[name=nombre_premio]').value = button.getAttribute('data-nombre');
        modal.querySelector('[name=descripcion_premio]').value = button.getAttribute('data-descripcion');
        modal.querySelector('[name=puntos_necesarios]').value = button.getAttribute('data-puntos');

        // Vista previa de imagen actual
        const imagen = button.getAttribute('data-imagen');
        const imagenPreview = modal.querySelector('#imagenActualPreview');
        if (imagenPreview && imagen) {
          imagenPreview.src = `../php/uploads/${imagen}`;
        }

        // Limpiar el input file
        const inputFile = modal.querySelector('[name=imagen]');
        if (inputFile) inputFile.value = "";

        // Asegurar campo oculto para ID del premio
        let inputHidden = modal.querySelector('[name=id_premio]');
        if (!inputHidden) {
          inputHidden = document.createElement("input");
          inputHidden.type = "hidden";
          inputHidden.name = "id_premio";
          modal.querySelector('form').appendChild(inputHidden);
        }
        inputHidden.value = button.getAttribute('data-id');
      });
    }

    // MODAL ELIMINAR PREMIO
    const modalEliminar = document.getElementById('modalEliminarPremio');
    if (modalEliminar) {
      modalEliminar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const inputHidden = document.getElementById('eliminarIdPremio');
        if (inputHidden) inputHidden.value = id;
      });
    }

    // FORMULARIO AGREGAR PREMIO (AJAX)
    const formAgregar = document.getElementById('formAgregarPremio');
    if (formAgregar) {
      formAgregar.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formAgregar);

        fetch('../php/guardar_premio.php', {
          method: 'POST',
          body: formData,
        })
        .then(response => response.json())
        .then(data => {
          console.log("Respuesta JSON procesada:", data);
          if (data.success) {
            alert(data.message);
            window.location.href = 'modulo_Premios.php?success=agregado';
            $('#modalPremio').modal('hide');
            formAgregar.reset();
          } else {
            alert(data.message);
          }
        })
        .catch(err => {
          console.error('Error al parsear JSON:', err);
          alert('Ocurrió un error al guardar el premio (JSON mal formado).');
        });
      });
    }
  });
</script>


</body>
</html>
