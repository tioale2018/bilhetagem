<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");


if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
  $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header("Location: $url", true, 301);
  exit();
}

$servername = "localhost";
$server_name = $_SERVER['SERVER_NAME'];

$username = "root";
$password = "";
$database = "bdbilhetagem";


if ($server_name == 'homologadev.com.br') {
  //homologa homologadev
  # code...
  $username = "homologacom_userbilhetagem";
  $password = ")NaQ]pd-@PfQ";
  $database = "homologacom_bdbilhetagem";
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
  exit("Connection failed");
}

?>