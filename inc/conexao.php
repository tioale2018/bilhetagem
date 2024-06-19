<?php
$servername = "localhost";
 $server_name = $_SERVER['SERVER_NAME'];

if ($server_name == 'localhost' || $server_name == '127.0.0.1' || $server_name == '192.168.2.16') {
  # code...
  $username = "root";
  $password = "";
  $database = "bdbilhetagem";
} else {
  # code...
  $username = "w3brandcom_userbilhertagem";
  $password = "tnH0[uPB{b3R";
  $database = "w3brandcom_bilhetagem";
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