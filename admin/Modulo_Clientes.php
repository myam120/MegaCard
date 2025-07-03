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
  $total_stmt = $conn->query("SELECT COUNT(*) FROM clientes");
  $total_registros = $total_stmt->fetchColumn();
  $total_paginas = ceil($total_registros / $por_pagina);

  // Consulta paginada
  $stmt = $conn->prepare("SELECT * FROM clientes LIMIT :inicio, :por_pagina");
  $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
  $stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
  $stmt->execute();
  $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
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
</style>

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
            <a class="nav-link active" aria-current="page" href="#">Clientes</a>
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

  <div class="container my-5">
    <h2 class="text-warning mb-4"><i class="fa-solid fa-users"></i> Gestión de Clientes</h2>

    <!-- Botón para crear nuevo cliente -->
    <div class="mb-3 d-flex justify-content-between">
      <button class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalCliente"><i
          class="fa-solid fa-user-plus"></i> Nuevo Cliente</button>
      <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar por nombre o teléfono">
    </div>

   <!-- Tabla de clientes -->
  <div class="table-responsive">
    <table class="table table-dark table-bordered table-hover" id="tablaClientes">
      <thead>
        <tr>
          <th>Teléfono</th>
          <th>Nombre completo</th>
          <th>Dirección</th>
          <th>Correo Electrónico</th>
          <th>Estado</th>
          <th>Ciudad</th>
          <th>Puntos</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($clientes)): ?>
          <tr>
            <td colspan="8" class="text-center text-warning">No se encontraron resultados.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($clientes as $cliente): ?>
            <tr>
              <td><?= htmlspecialchars($cliente['telefono_cliente']) ?></td>
              <td><?= htmlspecialchars($cliente['nombre_cliente'] . ' ' . $cliente['apellidos_cliente']) ?></td>
              <td><?= htmlspecialchars($cliente['direccion_cliente']) ?></td>
              <td><?= htmlspecialchars($cliente['correo_cliente']) ?></td>
              <td><?= htmlspecialchars($cliente['estado_cliente']) ?></td>
              <td><?= htmlspecialchars($cliente['ciudad_cliente']) ?></td>
              <td><?= (int)$cliente['puntos_cliente'] ?></td>
              <td>
                <button class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditarCliente"
                        data-id="<?= $cliente['id_cliente'] ?>"
                        data-telefono="<?= $cliente['telefono_cliente'] ?>"
                        data-nombre="<?= $cliente['nombre_cliente'] ?>"
                        data-apellidos="<?= $cliente['apellidos_cliente'] ?>"
                        data-direccion="<?= $cliente['direccion_cliente'] ?>"
                        data-correo="<?= $cliente['correo_cliente'] ?>"
                        data-estado="<?= $cliente['estado_cliente'] ?>"
                        data-ciudad="<?= $cliente['ciudad_cliente'] ?>"
                        data-puntos="<?= $cliente['puntos_cliente'] ?>">
                  <i class="fa-solid fa-pen-to-square"></i> Editar
                </button>

                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalPuntos">
                  <i class="fa-solid fa-plus"></i> Puntos
                </button>

                <button class="btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEliminarCliente"
                        data-id="<?= $cliente['id_cliente'] ?>">
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


  <!-- Modal Cliente -->
  <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white">
        <form method="POST" action="../php/agregar_cliente.php" style="color: black;">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="modalClienteLabel"><i class="fa-solid fa-user-plus"></i> Agregar
              nuevo cliente
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label text-warning">Teléfono</label>
              <input type="text" class="form-control bg-dark text-white" name="telefono_cliente"
                placeholder="Número Telefónico" pattern="^\d{10}$" maxlength="10"
                title="El número debe tener exactamente 10 dígitos y solo puede contener números." required>
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Nombre</label>
                <input type="text" class="form-control bg-dark text-white" name="nombre_cliente"
                  placeholder="Nombre del cliente" required>
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Apellidos</label>
                <input type="text" class="form-control bg-dark text-white" name="apellidos_cliente"
                  placeholder="Apellidos" required>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label text-warning">Dirección</label>
              <input type="text" class="form-control bg-dark text-white" name="direccion_cliente"
                placeholder="Ingrese su dirección" required>
            </div>
            <div class="mb-2">
              <label class="form-label text-warning">Correo Electrónico</label>
              <input type="email" class="form-control bg-dark text-white" name="correo_cliente"
                placeholder="Ingrese su correo electrónico" required>
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Estado</label>
                <input type="text" class="form-control bg-dark text-white" name="estado_cliente" placeholder="Estado"
                  required>
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Ciudad</label>
                <input type="text" class="form-control bg-dark text-white" name="ciudad_cliente" placeholder="Ciudad"
                  required>
              </div>
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Contraseña</label>
                <input type="password" class="form-control bg-dark text-white" name="contrasena_cliente"
                  placeholder="Ingrese una contraseña" minlength="8"
                  title="La contraseña debe tener al menos 8 caracteres." required>
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Comfirmar contraseña</label>
                <input type="password" class="form-control bg-dark text-white" name="confirmar_contrasena" minlength="8"
                  title="La contraseña debe tener al menos 8 caracteres." placeholder="Confirmar contraseña" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-orange">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar Cliente -->
  <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white">
        <form method="POST" action="../php/editar_cliente.php" style="color: black;">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="modalClienteLabel"><i class='fa-solid fa-pen-to-square'></i> Editar
              cliente</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_cliente">
            <div class="mb-2">
              <label class="form-label text-warning">Teléfono</label>
              <input type="text" class="form-control bg-dark text-white" name="telefono_cliente"
                placeholder="Número Telefónico" pattern="^\d{10}$" maxlength="10"
                title="El número debe tener exactamente 10 dígitos y solo puede contener números." required>
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Nombre</label>
                <input type="text" class="form-control bg-dark text-white" name="nombre_cliente"
                  placeholder="Nombre del cliente" required>
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Apellidos</label>
                <input type="text" class="form-control bg-dark text-white" name="apellidos_cliente"
                  placeholder="Apellidos" required>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label text-warning">Dirección</label>
              <input type="text" class="form-control bg-dark text-white" name="direccion_cliente"
                placeholder="Ingrese su dirección" required>
            </div>
            <div class="mb-2">
              <label class="form-label text-warning">Correo Electrónico</label>
              <input type="email" class="form-control bg-dark text-white" name="correo_cliente"
                placeholder="Ingrese su correo electrónico" required>
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Estado</label>
                <input type="text" class="form-control bg-dark text-white" name="estado_cliente" placeholder="Estado"
                  required>
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Ciudad</label>
                <input type="text" class="form-control bg-dark text-white" name="ciudad_cliente" placeholder="Ciudad"
                  required>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label text-warning">Puntos</label>
              <input type="number" class="form-control bg-dark text-white" name="puntos_cliente" value="0">
            </div>
            <div class="row">
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Cambiar contraseña</label>
                <input type="password" class="form-control bg-dark text-white" name="contrasena_cliente"
                  placeholder="Camnbiar contraseña" minlength="8"
                  title="La contraseña debe tener al menos 8 caracteres.">
              </div>
              <div class="mb-2 col-md-6">
                <label class="form-label text-warning">Comfirmar contraseña</label>
                <input type="password" class="form-control bg-dark text-white" name="confirmar_contrasena" minlength="8"
                  title="La contraseña debe tener al menos 8 caracteres." placeholder="Confirmar contraseña">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-orange">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Puntos -->
  <div class="modal fade" id="modalPuntos" tabindex="-1" aria-labelledby="modalPuntosLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form style="color: black;">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPuntosLabel">➕ Alta de Puntos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <p>Por cada $100 de compra, se otorgan 5 puntos.</p>
            <div class="mb-2">
              <label>Monto de la compra ($)</label>
              <input type="number" class="form-control" placeholder="Ej: 500">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Asignar Puntos</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Cliente -->
  <div class="modal fade" id="modalEliminarCliente" tabindex="-1" aria-labelledby="modalEliminarLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="../php/eliminar_cliente.php">
        <input type="hidden" name="id_cliente" id="eliminarIdCliente">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de que deseas eliminar este cliente?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-orange">Eliminar</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modalEditar = document.getElementById('modalEditarCliente');
      modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const modal = this;

        modal.querySelector('[name=telefono_cliente]').value = button.getAttribute('data-telefono');
        modal.querySelector('[name=nombre_cliente]').value = button.getAttribute('data-nombre');
        modal.querySelector('[name=apellidos_cliente]').value = button.getAttribute('data-apellidos');
        modal.querySelector('[name=direccion_cliente]').value = button.getAttribute('data-direccion');
        modal.querySelector('[name=correo_cliente]').value = button.getAttribute('data-correo');
        modal.querySelector('[name=estado_cliente]').value = button.getAttribute('data-estado');
        modal.querySelector('[name=ciudad_cliente]').value = button.getAttribute('data-ciudad');
        modal.querySelector('[name=puntos_cliente]').value = button.getAttribute('data-puntos');
        modal.querySelector('[name=id_cliente]').value = button.getAttribute('data-id');
      });
    });
    document.querySelector('form').addEventListener('submit', function (e) {
      const pass = document.querySelector('[name=contrasena]').value;
      const confirm = document.querySelector('[name=confirmar_contrasena]').value;
      if (pass !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const modalEliminar = document.getElementById('modalEliminarCliente');
      modalEliminar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const inputHidden = document.getElementById('eliminarIdCliente');
        inputHidden.value = id;
      });
    });
    document.getElementById('buscador').addEventListener('input', function () {
      const filtro = this.value.toLowerCase();
      const filas = document.querySelectorAll('#tablaClientes tbody tr');

      filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
      });
    });
    // Ocultar alerta después de 4 segundos
    setTimeout(() => {
      const alerta = document.getElementById('alerta');
      if (alerta) {
        const bsAlert = new bootstrap.Alert(alerta);
        bsAlert.close();
      }
    }, 3000);
  </script>

</body>

</html>