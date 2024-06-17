<?php
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST'] == '192.168.0.12') {
  // Configurações para ambiente local
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "bdbilhetagem";
} else {
  // Configurações para ambiente online
  $servername = "online_server_hostname";
  $username = "online_db_username";
  $password = "online_db_password";
  $database = "online_db_name";
}

date_default_timezone_set('America/Sao_Paulo');

try {
  $connPDO = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

?>