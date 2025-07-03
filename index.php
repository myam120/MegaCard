<?php
session_start();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'php/db.php';

    $telefono = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar administrador
    $sql = "SELECT * FROM administrador WHERE telefono_administrador = ? AND password_administrador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Administrador';
        header("Location: admin/principal.php");
        exit;
    }

    // Validar cliente
    $sql = "SELECT * FROM clientes WHERE telefono_cliente = ? AND contrasena_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Cliente';
        $_SESSION['telefono'] = $telefono;
        header("Location: cliente/principal.php");
        exit;
    }

    $mensaje = "❌ Credenciales incorrectas.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>MegaCard - Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background-color: #121212;
      color: #f1f1f1;
    }

    .info {
      background-image: url(img/fondo.jpg);
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
      min-height: 650px;
    }

    .login-card {
      background-color: #1e1e1e;
      border: 1px solid #ffa50020;
    }

    .form-label {
      color: #ffc107;
    }

    .btn-primary {
      background-color: #ff9800;
      border: none;
    }

    .btn-primary:hover {
      background-color: #ffb74d;
    }

    #mensaje {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 d-flex flex-column flex-md-row">

      <!-- Imagen -->
      <div class="col-md-6 info order-1 order-md-0 d-flex justify-content-center align-items-center">
        <!-- Puedes poner texto encima si quieres -->
      </div>

      <!-- Formulario -->
      <div class="col-md-6 d-flex justify-content-center align-items-center p-4">
        <div class="card login-card p-4 w-100" style="max-width: 500px; height: 100%;">
          <h2 class="text-center mb-4" style="color: #ffc107; margin-top: 20%;">Iniciar sesión</h2>
          <form method="POST" class="form">
            <div class="mb-3">
              <label for="usuario" class="form-label">Teléfono</label>
              <input type="text" class="form-control bg-dark text-white border-warning" id="usuario" name="usuario"
                placeholder="Ingresa tu usuario" />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control bg-dark text-white border-warning" id="password"
                name="password" placeholder="Contraseña" />
            </div>

            <button id="loginBtn" class="btn btn-primary w-100">Iniciar sesión</button>
            <h1></h1>
            <button type="button" class="btn btn-outline-light">¿No tienes cuenta? Regístrate</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>