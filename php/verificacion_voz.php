<?php
    session_start();
    if (!isset($_SESSION['rol']) || !isset($_SESSION['telefono'])) {
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificación por Voz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #1c1c1c;
      color: #fff;
    }
    .container {
      margin-top: 10%;
      text-align: center;
    }
    .btn {
      margin-top: 20px;
    }
    #resultado {
      font-size: 1.3em;
      margin-top: 20px;
      color: #ffc107;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Verificación por Voz</h1>
    <p>Haz clic en el botón y di en voz alta: <strong>"Confirmar acceso"</strong></p>
    <button class="btn btn-warning" onclick="iniciarReconocimiento()">🎤 Verificar mi voz</button>
    <p id="resultado"></p>
  </div>

  <script>
    function iniciarReconocimiento() {
      const reconocimiento = new webkitSpeechRecognition() || new SpeechRecognition();
      reconocimiento.lang = 'es-ES';
      reconocimiento.interimResults = false;
      reconocimiento.maxAlternatives = 1;

      reconocimiento.onresult = function(event) {
        const texto = event.results[0][0].transcript.toLowerCase();
        document.getElementById('resultado').textContent = `Has dicho: "${texto}"`;

        if (texto.includes("confirmar acceso")) {
          const destino = localStorage.getItem("destino") || "login.php";
          window.location.href = destino;
        } else {
          document.getElementById('resultado').textContent += " ❌ Intenta decir exactamente: confirmar acceso";
        }
      };

      reconocimiento.onerror = function(event) {
        document.getElementById('resultado').textContent = `Error: ${event.error}`;
      };

      reconocimiento.start();
    }
  </script>
</body>
</html>
