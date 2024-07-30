<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$server_name = $_SERVER['SERVER_NAME'];

if ($server_name == 'localhost' || $server_name == '127.0.0.1' || $server_name == '192.168.2.16' || $server_name == 'host.bilhetagem') {
  # code...
  $username = "root";
  $password = "";
  $database = "bdbilhetagem";

} elseif ($server_name == 'w3brand.com.br') {
  //homologa w3brand
  # code...
  $username = "brandw3com_userbilhetagem";
  $password = "@}T)Cupw0e_z";
  $database = "brandw3com_bdbilhetagem";
} elseif ($server_name == 'rapidticket.com.br') {
  //produção rapidticket
  $username = "rapidcom_userbilhetagem";
  $password = "i#U9ZPP-IB3V";
  $database = "rapidcom_bdbilhetagem";
}

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese', 'pt_BR.utf8');

try {
  $connPDO = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

?>