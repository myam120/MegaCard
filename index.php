<?php
<<<<<<< HEAD
  session_start();
  $mensaje = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
=======
session_start();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
    require 'php/db.php';

    $telefono = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar administrador
    $sql = "SELECT * FROM administrador WHERE telefono_administrador = ? AND password_administrador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Administrador';
<<<<<<< HEAD
        $_SESSION['telefono'] = $telefono;
        echo "<script>localStorage.setItem('destino', '../admin/principal.php');</script>";
        echo "<script>window.location.href = 'php/verificacion_voz.php';</script>";
=======
        header("Location: admin/principal.php");
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
        exit;
    }

    // Validar cliente
    $sql = "SELECT * FROM clientes WHERE telefono_cliente = ? AND contrasena_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$telefono, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['rol'] = 'Cliente';
        $_SESSION['telefono'] = $telefono;
<<<<<<< HEAD
        echo "<script>localStorage.setItem('destino', '../cliente/principal.php');</script>";
        echo "<script>window.location.href = 'php/verificacion_voz.php';</script>";
=======
        header("Location: cliente/principal.php");
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
        exit;
    }

    $mensaje = "❌ Credenciales incorrectas.";
<<<<<<< HEAD
  }
=======
}
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
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
<<<<<<< HEAD

            <?php if (!empty($mensaje)) echo "<div id='mensaje' class='text-danger'>$mensaje</div>"; ?>

=======
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
          <form method="POST" class="form">
            <div class="mb-3">
              <label for="usuario" class="form-label">Teléfono</label>
              <input type="text" class="form-control bg-dark text-white border-warning" id="usuario" name="usuario"
<<<<<<< HEAD
                placeholder="Ingresa tu número" maxlength="10" pattern="\d{10}" aotofocus required/>
=======
                placeholder="Ingresa tu usuario" />
>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
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
<<<<<<< HEAD
   <!-- ✅ Autenticación por voz -->
  <script>
    const rol = "<?php echo $_SESSION['rol'] ?? ''; ?>";
    const redirigir = {
      'Administrador': 'admin/principal.php',
      'Cliente': 'cliente/principal.php'
    };

    if (rol && !sessionStorage.getItem("voz_confirmada")) {
      alert("🗣 Autenticación por voz requerida. Di: 'sí soy yo' o 'acceder'.");

      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      const recognition = new SpeechRecognition();

      recognition.lang = 'es-ES';
      recognition.interimResults = false;
      recognition.maxAlternatives = 1;

      recognition.start();

      recognition.onresult = function (event) {
        const voz = event.results[0][0].transcript.toLowerCase();
        console.log("Texto reconocido:", voz);

        const aceptadas = ["sí soy yo", "acceder", "confirmo mi identidad"];

        if (aceptadas.some(frase => voz.includes(frase))) {
          sessionStorage.setItem("voz_confirmada", true);
          window.location.href = redirigir[rol];
        } else {
          alert("❌ Voz no reconocida correctamente. Intenta de nuevo.");
          sessionStorage.removeItem("voz_confirmada");
        }
      };

      recognition.onerror = function (event) {
        console.error("Error de reconocimiento:", event.error);
        alert("❌ Hubo un error al reconocer tu voz. Intenta nuevamente.");
      };
    }
  </script>
</body>
=======
</body>

>>>>>>> b41b3eca61513294f8e9c26aab7b0956142a8b3c
</html>