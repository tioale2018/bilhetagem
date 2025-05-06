<?php
// Define o cabeçalho correto
http_response_code(404);

// Opcional: log do erro ou analytics
// error_log("Página não encontrada: " . $_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Página não encontrada</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      color: #333;
      text-align: center;
      padding-top: 100px;
    }
  </style>
</head>
<body>
  <h1>404 - Página não encontrada</h1>
  <p>A URL solicitada não foi encontrada neste servidor.</p>
</body>
</html>
